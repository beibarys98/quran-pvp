<?php

namespace frontend\controllers;

use common\models\Battle;
use common\models\QuranId;
use common\models\Rating;
use common\models\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

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

        //user stats
        $dataProvider2 = new ActiveDataProvider([
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

    public function actionFriend(){

    }

    public function actionRandom($mode = 'transliteration'){
        $playerId = Yii::$app->user->id;
        $player = Rating::findOne($playerId);
        $player->status = 'random';
        $player->save(false);

        $minLevel = max(0, $player->level - 3);
        $maxLevel = min(114, $player->level + 3);
        $maxWaitTime = 60;
        $startTime = time();
        do {
            $opponent = Rating::find()
                ->andWhere(['status' => 'random'])
                ->andWhere(['between', 'level', $minLevel, $maxLevel])
                ->andWhere(['!=', 'id', $playerId])
                ->orderBy(new \yii\db\Expression('RAND()'))
                ->limit(1)
                ->one();

            if ($opponent) {
                break;
            }

            usleep(500000);
        } while (time() - $startTime < $maxWaitTime);

        if (!$opponent) {
            Yii::$app->session->setFlash('error', 'No opponent found. Try again later.');
            return $this->redirect(['site/index']);
        }

        $turn = (bool)rand(0, 1);

        $suraIds = range(114 - $player->level, 114);
        $randomSuraId = $suraIds[array_rand($suraIds)];

        $battle = new Battle();
        $battle->playerOne = $turn ? $player->id : $opponent->id;
        $battle->playerTwo = $turn ? $opponent->id : $player->id;
        $battle->suraId = $randomSuraId;
        $battle->save(false);

        return $this->render('random', [
            'battle' => $battle,
            'mode' => $mode,
        ]);
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
