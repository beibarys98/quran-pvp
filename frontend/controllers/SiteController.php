<?php

namespace frontend\controllers;

use common\models\Battle;
use common\models\QuranId;
use common\models\Rating;
use common\models\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
            'captcha' => [
                'class' => \yii\captcha\CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect('/site/signup');
        }

        //rating
        $topCounts = Rating::find()->orderBy(['day' => SORT_DESC])->limit(3)->all();
        $topWeeks = Rating::find()->orderBy(['week' => SORT_DESC])->limit(3)->all();
        $topMonths = Rating::find()->orderBy(['month' => SORT_DESC])->limit(3)->all();
        $topAllTime = Rating::find()->orderBy(['all_time' => SORT_DESC])->limit(3)->all();

        $combinedData = [];
        for ($i = 0; $i < 3; $i++) {
            $combinedData[] = [
                'day' => $topCounts[$i]->day ?? null,
                'day_user_id' => $topCounts[$i]->user_id ?? null,
                'week' => $topWeeks[$i]->week ?? null,
                'week_user_id' => $topWeeks[$i]->user_id ?? null,
                'month' => $topMonths[$i]->month ?? null,
                'month_user_id' => $topMonths[$i]->user_id ?? null,
                'all_time' => $topAllTime[$i]->all_time ?? null,
                'all_time_user_id' => $topAllTime[$i]->user_id ?? null,
            ];
        }

        $dataProviderCombined = new ArrayDataProvider([
            'allModels' => $combinedData,
            'pagination' => false,
        ]);

        //user's place
        $dataProvider = new ActiveDataProvider([
            'query' => Rating::find()->andWhere(['user_id' => Yii::$app->user->identity->id]),
        ]);

        //daily update
        $user = Rating::findOne(['user_id' => Yii::$app->user->id]);
        $currentDate = date('d');
        $lastUpdatedDate = date('d', strtotime($user->updated_at));
        if ($currentDate !== $lastUpdatedDate){
            $models = Rating::find()->all();
            foreach($models as $model){
                $currentDate = date('d');
                $lastUpdatedDate = date('d', strtotime($model->updated_at));
                $currentWeek = date('W');
                $lastUpdatedWeek = date('W', strtotime($model->updated_at));
                $currentMonth = date('m');
                $lastUpdatedMonth = date('m', strtotime($model->updated_at));

                if ($currentDate !== $lastUpdatedDate) {
                    $model->day = 0;
                    $model->save();
                }
                if ($currentWeek !== $lastUpdatedWeek) {
                    $model->week = 0;
                    $model->save();
                }
                if ($currentMonth !== $lastUpdatedMonth) {
                    $model->month = 0;
                    $model->save();
                }
            }
        }

        $currentPlayer = Rating::findOne(['user_id' => Yii::$app->user->id]);

        if ($currentPlayer && $currentPlayer->flag !== null) {
            switch ($currentPlayer->flag) {
                case 0:
                    Yii::$app->session->setFlash('error', 'Сіз жеңілдіңіз.');
                    break;
                case 1:
                    Yii::$app->session->setFlash('success', 'Сіз жеңдіңіз!');
                    break;
                case 2:
                    Yii::$app->session->setFlash('info', 'Екеуіңіз де жеңдіңіз!');
                    break;
            }

            $currentPlayer->flag = null;
            $currentPlayer->save(false);
        }

        return $this->render('index', [
            'dataProviderCombined' => $dataProviderCombined,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function getUserRank($category)
    {
        $userId = Yii::$app->user->id;
        $query = Rating::find()->orderBy([$category => SORT_DESC]);
        $models = $query->all();
        $rank = 1;

        foreach ($models as $model) {
            if ($model->user_id === $userId) {
                return $rank;
            }
            $rank++;
        }

        return null;
    }

    public function actionInvite()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $player = Rating::findOne(['user_id' => Yii::$app->user->id]);
        if (!$player) {
            return ['success' => false, 'message' => 'Player not found.'];
        }

        $player->status = 'friend';
        if ($player->save(false)) {
            return ['success' => true];
        }

        return ['success' => false, 'message' => 'Failed to update status.'];
    }

    public function actionFriend($inviter_id = null){
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        $userId = Yii::$app->user->id;
        $player = Rating::findOne(['user_id' => $userId]);

        if ($inviter_id) {
            $opponent = Rating::findOne(['user_id' => $inviter_id]);
            $player->status = 'battle';
            $opponent->status = 'battle';
            $player->save(false);
            $opponent->save(false);

            // Randomly decide who plays first
            $turn = (bool)rand(0, 1);

            // Select a random Surah based on the player's level
            $suraIds = range(114 - $opponent->level, 114);
            $randomSuraId = $suraIds[array_rand($suraIds)];

            // Create a new battle
            $battle = new Battle();
            $battle->playerOne = $turn ? $player->user_id : $opponent->user_id;
            $battle->playerTwo = $turn ? $opponent->user_id : $player->user_id;
            $battle->suraId = $randomSuraId;
            $battle->save(false);

            return $this->redirect(['site/battle', 'id' => $battle->suraId]);
        }
    }

    public function actionRandom($mode = '0') {
        $playerId = Yii::$app->user->id;
        $player = Rating::findOne(['user_id' => $playerId]);
        $player->status = 'lobby'; // Set status to "lobby"
        $player->save(false);

        return $this->redirect(['site/lobby', 'mode' => $mode]);
    }

    public function actionLobby($mode = '0') {
        return $this->render('lobby', [
            'mode' => $mode,
        ]);
    }

    public function actionCancel() {
        $playerId = Yii::$app->user->id;
        $player = Rating::findOne(['user_id' => $playerId]);

        if ($player) {
            $player->status = 'index'; // Reset status
            $player->save(false);
        }

        return $this->redirect(['site/index']); // Go back to homepage
    }

    public function actionFindOpponent($mode = '0') {
        $playerId = Yii::$app->user->id;
        $player = Rating::findOne(['user_id' => $playerId]);

        $existingBattle = Battle::find()
            ->andWhere(['or', ['playerOne' => $playerId], ['playerTwo' => $playerId]])
            ->orderBy(['id' => SORT_DESC]) // Get latest battle
            ->one();

        if ($existingBattle) {
            return $this->asJson([
                'success' => true,
                'battleUrl' => Url::to(['site/battle', 'id' => $existingBattle->id, 'mode' => $mode])
            ]);
        }

        $minLevel = max(0, $player->level - 3);
        $maxLevel = min(114, $player->level + 3);

        $opponent = Rating::find()
            ->andWhere(['status' => 'lobby']) // Look for players in "lobby"
            ->andWhere(['between', 'level', $minLevel, $maxLevel])
            ->andWhere(['!=', 'user_id', $playerId])
            ->orderBy(new \yii\db\Expression('RAND()'))
            ->limit(1)
            ->one();

        if ($opponent) {
            // Set both players' status to "battle"
            $player->status = 'battle';
            $opponent->status = 'battle';
            $player->save(false);
            $opponent->save(false);

            // Randomly decide who plays first
            $turn = (bool)rand(0, 1);

            // Select a random Surah based on the player's level
            $suraIds = range(114 - $player->level, 114);
            $randomSuraId = $suraIds[array_rand($suraIds)];

            // Create a new battle
            $battle = new Battle();
            $battle->playerOne = $turn ? $player->user_id : $opponent->user_id;
            $battle->playerTwo = $turn ? $opponent->user_id : $player->user_id;
            $battle->suraId = $randomSuraId;
            $battle->save(false);

            return $this->asJson([
                'success' => true,
                'battleUrl' => Url::to(['site/battle', 'id' => $battle->id, 'mode' => $mode]),
            ]);
        }

        return $this->asJson(['success' => false]); // No opponent yet, retry in 2s
    }

    public function actionBattle($id, $mode = '0') {
        $battle = Battle::findOne($id);
        if (!$battle) {
            throw new NotFoundHttpException("Battle not found.");
        }

        // If it's an AJAX request, return turn and opponent's status
        if (Yii::$app->request->isAjax) {
            return $this->asJson([
                'turn' => $battle->turn
            ]);
        }

        return $this->render('battle', [
            'battle' => $battle,
            'mode' => $mode,
        ]);
    }

    public function actionTurn($battleId, $choice){
        $battle = Battle::findOne($battleId);
        if (!$battle) {
            throw new NotFoundHttpException('Battle not found.');
        }

        // Get the correct answer
        $correctVerse = QuranId::find()
            ->andWhere(['suraId' => $battle->suraId])
            ->andWhere(['verseID' => $battle->turn])
            ->one();

        // Check if the choice is correct
        if ($choice == $correctVerse->verseID) {
            // Correct answer: move to the next turn
            $battle->turn += 1;
            $battle->save(false);

            // Check if we reached the end of the Surah
            $maxVerse = QuranId::find()
                ->andWhere(['suraId' => $battle->suraId])
                ->orderBy(['verseID' => SORT_DESC])
                ->one();

            if ($battle->turn > $maxVerse->verseID) {
                return $this->actionEnd($battleId);
            }
        } else {
            // Determine whose turn it is
            $isPlayerOneTurn = ($battle->turn % 2 == 1); // Odd = Player One, Even = Player Two
            $currentPlayer = $isPlayerOneTurn ? $battle->playerOne0 : $battle->playerTwo0;

            return $this->actionEnd($battleId, $currentPlayer->id);
        }

        return $this->redirect(['site/battle', 'id' => $battleId]);
    }

    public function actionEnd($battleId, $loserId = null) {
        $battle = Battle::findOne($battleId);
        if (!$battle) {
            throw new NotFoundHttpException('Battle not found.');
        }

        // Fetch both players
        $playerOne = Rating::findOne(['user_id' => $battle->playerOne0->id]);
        $playerTwo = Rating::findOne(['user_id' => $battle->playerTwo0->id]);

        if ($loserId) {
            // Determine the winner
            $winner = ($playerOne->user_id == $loserId) ? $playerTwo : $playerOne;
            $loser = ($playerOne->user_id == $loserId) ? $playerOne : $playerTwo;

            // Update winner stats
            $winner->day += 3;
            $winner->week += 3;
            $winner->month += 3;
            $winner->all_time += 3;
            $winner->battles += 1;
            $winner->wins += 1;
            $winner->status = 'index';
            $winner->flag = 1;
            if ((114 - $winner->level) == $battle->suraId) {
                $winner->exp += 1;

                if ($winner->exp >= 3) {
                    $winner->level += 1;
                    $winner->exp = 0;
                }
            }
            $winner->save(false);

            // Update loser stats
            $loser->battles += 1;
            $loser->status = 'index';
            $loser->flag = 0;
            $loser->save(false);
        } else {
            // If it's a draw, both players win
            foreach ([$playerOne, $playerTwo] as $player) {
                $player->day += 3;
                $player->week += 3;
                $player->month += 3;
                $player->all_time += 3;
                $player->battles += 1;
                $player->wins += 1;
                $player->status = 'index';
                $player->flag = 2;
                if ((114 - $player->level) == $battle->suraId) {
                    $player->exp += 1;

                    if ($player->exp >= 3) {
                        $player->level += 1;
                        $player->exp = 0;
                    }
                }
                $player->save(false);
            }
        }

        $battle->delete();

        return $this->redirect(['site/index']);
    }


    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionSignup()
    {
        $model = new User();
        $rating = new Rating();
        if ($model->load(Yii::$app->request->post())) {
            $existingUser = User::findOne(['username' => $model->username]);

            if ($existingUser && Yii::$app->user->login($existingUser, 3600 * 24 * 30)) {
                Yii::$app->session->setFlash('danger', 'Тіркелген аккаунтқа кірдіңіз!');
                return $this->redirect(['site/index']);
            }

            $model->generateAuthKey();
            $model->setPassword('password');
            $model->save(false);

            $rating->user_id = $model->id;
            $rating->save(false);

            Yii::$app->session->setFlash('success', 'Тіркелу сәтті өтті!');
            if (Yii::$app->user->login($model)) {
                return $this->redirect(['site/index']);
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }
}
