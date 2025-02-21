<?php

/** @var yii\web\View $this */
/** @var $battle */

use common\models\QuranId;
use common\models\QuranSurah;
use common\models\Rating;
use yii\helpers\Html;

$this->title = Yii::$app->name;

$siteIndexUrl = \yii\helpers\Url::to(['site/index']); // Get URL in PHP

$this->registerJs(<<<JS
    let lastTurn = {$battle->turn};
    let siteIndexUrl = "{$siteIndexUrl}"; // Assign URL properly

    function checkTurnUpdate() {
        $.ajax({
            url: window.location.href,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.turn !== lastTurn) {
                    lastTurn = response.turn; // Prevent multiple reloads
                    location.reload();
                }
            },
            error: function() {
                window.location.href = siteIndexUrl;
            },
            complete: function() {
                setTimeout(checkTurnUpdate, 1000); // Check every 3 seconds
            }
        });
    }

    setTimeout(checkTurnUpdate, 1000);
JS);
?>
<div class="site-index">
    <div class="text-center fw-bold" style="font-size: 24px;">
        <?= Rating::findOne(['user_id' => Yii::$app->user->id])->mode ? QuranSurah::findOne($battle->suraId)->arabic : QuranSurah::findOne($battle->suraId)->latin ?>
    </div>
    <div class="m-1 p-1 text-center" style="height: 40vh; border: 1px solid black; border-radius: 10px; overflow: hidden; display: flex; flex-direction: column;">
        <!-- First verse (static) -->
        <div style="font-size: 24px; border: 1px solid black; border-radius: 10px;">
            <?= Rating::findOne(['user_id' => Yii::$app->user->id])->mode ? QuranId::findOne(0)->ayahText : QuranId::findOne(0)->readText ?>
        </div>

        <?php
        $quranVerses = QuranId::find()
            ->andWhere(['suraId' => $battle->suraId])
            ->orderBy('verseID')
            ->limit($battle->turn - 1)
            ->all();
        ?>

        <div id="verseContainer" style="flex-grow: 1; overflow-y: auto;">
            <?php foreach ($quranVerses as $verse): ?>
                <div class="mt-1" style="font-size: 24px; border: 1px solid black; border-radius: 10px;">
                    <p><?= Rating::findOne(['user_id' => Yii::$app->user->id])->mode ? $verse->ayahText : $verse->readText ?></p>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
        $this->registerJs(<<<JS
            function scrollToBottom() {
                let container = document.getElementById("verseContainer");
                container.scrollTop = container.scrollHeight;
            }
            scrollToBottom();
        JS);
        ?>
    </div>

    <div class="row m-1 p-1" style="height: 7vh; border: 1px solid black; border-radius: 10px;">
        <!-- Opponent (Left Side) -->
        <div class="col-4 p-1">
            <div style="
                    height: 5vh;
                    border: 1px solid black;
                    border-radius: 10px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    text-align: center;
                    font-weight: bold;
                    background-color:
            <?= ($battle->turn % 2 == 0 && $battle->playerTwo0->id != Yii::$app->user->id) ||
            ($battle->turn % 2 == 1 && $battle->playerOne0->id != Yii::$app->user->id)
                ? 'orangered' : 'white' ?>;
                    ">
                <?=
                $battle->playerOne0->id == Yii::$app->user->id
                    ? $battle->playerTwo0->username
                    : $battle->playerOne0->username;
                ?>
            </div>
        </div>

        <!-- Timer (Middle) -->
        <div class="col-4 p-1">
            <div id="timer" style="
                height: 5vh;
                border: 1px solid black;
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                text-align: center;
                font-weight: bold;
                font-size: 24px;">
                5
            </div>
        </div>

<!--        --><?php
//        $myTurn = ($battle->turn % 2 == 0 && $battle->playerTwo0->id == Yii::$app->user->id) ||
//            ($battle->turn % 2 == 1 && $battle->playerOne0->id == Yii::$app->user->id);
//
//        $playerId = Yii::$app->user->id;
//        $siteIndexUrl2 = \yii\helpers\Url::to(['site/end', 'battleId' => $battle->id, 'loserId' => $playerId]);
//
//        if ($myTurn):
//            $this->registerJs(<<<JS
//                let timeLeft = 5;
//                let siteIndexUrl2 = "{$siteIndexUrl2}";
//                function countdown() {
//                    document.getElementById("timer").innerText = timeLeft;
//                    if (timeLeft <= 0) {
//                        window.location.href = siteIndexUrl2;
//                    } else {
//                        timeLeft--;
//                        setTimeout(countdown, 1000);
//                    }
//                }
//                countdown();
//            JS);
//        endif;
//        ?>

        <!-- You (Right Side) -->
        <div class="col-4 p-1">
            <div style="
                    height: 5vh;
                    border: 1px solid black;
                    border-radius: 10px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    text-align: center;
                    font-weight: bold;
                    background-color:
            <?= ($battle->turn % 2 == 0 && $battle->playerTwo0->id == Yii::$app->user->id) ||
            ($battle->turn % 2 == 1 && $battle->playerOne0->id == Yii::$app->user->id)
                ? 'dodgerblue' : 'white' ?>;
                    ">
                <?=
                $battle->playerOne0->id == Yii::$app->user->id
                    ? $battle->playerOne0->username
                    : $battle->playerTwo0->username;
                ?>
            </div>
        </div>
    </div>

    <div class="m-1 p-1 text-center" style="height: 30vh; border: 1px solid black; border-radius: 10px; overflow: hidden; display: flex; flex-direction: column;">
        <?php
        $maxVerse = QuranId::find()
            ->andWhere(['suraId' => $battle->suraId])
            ->orderBy(['verseID' => SORT_DESC])
            ->one(); // Get the last verse in the Surah

        if ($battle->turn > $maxVerse->verseID) {
            // If turn exceeds available verses, pick a random correct verse
            $correctVerse = QuranId::find()
                ->andWhere(['suraId' => $battle->suraId])
                ->orderBy(new \yii\db\Expression('RAND()'))
                ->one();
        } else {
            $correctVerse = QuranId::find()
                ->andWhere(['suraId' => $battle->suraId])
                ->andWhere(['verseID' => $battle->turn])
                ->one();
        }

        $incorrectVerses = QuranId::find()
            ->andWhere(['suraId' => $battle->suraId])
            ->andWhere(['not', ['verseID' => $correctVerse->verseID]]) // Exclude correct answer
            ->orderBy('RAND()') // Randomize
            ->limit(2)
            ->all();

        $answerOptions = array_merge([$correctVerse], $incorrectVerses);
        shuffle($answerOptions);
        ?>
        <?php
        $isPlayerOneTurn = ($battle->turn % 2 == 1); // Odd turns -> Player One's turn
        $isMyTurn = ($isPlayerOneTurn && $battle->playerOne0->id == Yii::$app->user->id) ||
            (!$isPlayerOneTurn && $battle->playerTwo0->id == Yii::$app->user->id);
        $buttonClass = $isMyTurn ? '' : 'disabled';
        ?>
        <div style="flex-grow: 1; overflow-y: auto;">
            <?php foreach ($answerOptions as $index => $option): ?>
                <?= Html::a(Rating::findOne(['user_id' => Yii::$app->user->id])->mode ? $option->ayahText : $option->readText, ['site/turn', 'battleId' => $battle->id, 'choice' => $answerOptions[$index]->verseID], [
                    'class' => 'btn btn-lg btn-light w-100 mb-1 '. $buttonClass,
                    'style' => 'border-color: black; pointer-events: ' . ($isMyTurn ? 'auto' : 'none') . ';'
                ]) ?>
            <?php endforeach; ?>
        </div>
    </div>

</div>
