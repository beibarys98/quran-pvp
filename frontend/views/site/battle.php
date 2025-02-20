<?php

/** @var yii\web\View $this */
/** @var $battle */
/** @var $mode */

use common\models\QuranId;
use yii\helpers\Html;

$this->title = Yii::$app->name;
?>
<div class="site-index">
    <div class="m-1 p-1 text-center" style="height: 40vh; border: 1px solid black; border-radius: 10px; overflow: hidden; display: flex; flex-direction: column;">

        <!-- First verse (static) -->
        <div style="font-size: 24px; border: 1px solid black; border-radius: 5px; padding: 5px;">
            <?= $mode ? QuranId::findOne(0)->ayahText : QuranId::findOne(0)->readText ?>
        </div>

        <?php
        $quranVerses = QuranId::find()
            ->andWhere(['suraId' => $battle->suraId])
            ->orderBy('verseID')
            ->limit($battle->turn - 1)
            ->all();
        ?>

        <div style="flex-grow: 1; overflow-y: auto; margin-top: 5px; padding: 5px;">
            <?php foreach ($quranVerses as $verse): ?>
                <div style="font-size: 24px; border: 1px solid black; border-radius: 5px; padding: 5px; margin-bottom: 5px;">
                    <p><?= $mode ? $verse->ayahText : $verse->readText ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="row m-1 p-2" style="height: 10vh; border: 1px solid black; border-radius: 10px;">
        <!-- Opponent (Left Side) -->
        <div class="col-4 p-1">
            <div style="
                    height: 7vh;
                    border: 3px solid black;
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
            <div style="
            height: 7vh;
            border: 3px solid black;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-weight: bold;
        ">
                Timer
            </div>
        </div>

        <!-- You (Right Side) -->
        <div class="col-4 p-1">
            <div style="
                    height: 7vh;
                    border: 3px solid black;
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

    <div class="m-1 p-1 " style="height: 38vh; border: 1px solid black; border-radius: 10px;">
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

            <div style="flex-grow: 1; overflow-y: auto; margin-top: 5px; padding: 5px;">
                <?php
                $labels = ['a', 'b', 'c']; // Labels for each option
                foreach ($answerOptions as $index => $option):
                    ?>
                    <div class="row align-items-center">
                        <div class="col-1" style="font-size: 24px; border: 1px solid black; border-radius: 5px; padding: 5px; margin-bottom: 5px;">
                            <?= $labels[$index] ?>
                        </div>
                        <div class="col-11">
                            <div style="font-size: 24px; border: 1px solid black; border-radius: 5px; padding: 5px; margin-bottom: 5px;">
                                <p><?= $mode ? $option->ayahText : $option->readText ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>
        <?php
        $isPlayerOneTurn = ($battle->turn % 2 == 1); // Odd turns -> Player One's turn
        $isMyTurn = ($isPlayerOneTurn && $battle->playerOne0->id == Yii::$app->user->id) ||
            (!$isPlayerOneTurn && $battle->playerTwo0->id == Yii::$app->user->id);
        $buttonClass = $isMyTurn ? 'btn btn-lg btn-light w-100' : 'btn btn-lg btn-light w-100 disabled';
        ?>

          
    </div>

</div>
