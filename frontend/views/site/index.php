<?php

/** @var yii\web\View $this */
/** @var $dataProviderCombined */
/** @var $dataProvider */

use common\models\Rating;
use common\models\User;
use yii\bootstrap5\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = Yii::$app->name;
?>
<div class="site-index">
    <div style="font-weight: bold;" class="text-center">
        рейтинг
    </div>
    <?= GridView::widget([
        'dataProvider' => $dataProviderCombined,
        'summary' => false,
        'tableOptions' => ['class' => 'table table-bordered'],
        'columns' => [
            [
                'value' => function ($model) {
                    $user = User::findOne($model['day_user_id']);
                    $username = $user->username ?? '-';
                    $shortUsername = mb_strimwidth($username, 0, 6, '…');
                    $score = $model['day'] ?? '- -';
                    $class = ($model['day_user_id'] == Yii::$app->user->id) ? 'fw-bold' : '';

                    return "<span class='{$class}'>{$shortUsername} 
                                <span style='float: right; color: orangered;'>{$score}</span>
                            </span>";
                },
                'label' => 'күн',
                'enableSorting' => false,
                'attribute' => 'day',
                'format' => 'raw',
                'contentOptions' => ['style' => 'width: 25%;'],
            ],
            [
                'value' => function ($model) {
                    $user = User::findOne($model['week_user_id']);
                    $username = $user->username ?? '-';
                    $shortUsername = mb_strimwidth($username, 0, 7, '…');
                    $score = $model['week'] ?? '- -';
                    $class = ($model['week_user_id'] == Yii::$app->user->id) ? 'fw-bold' : '';
                    return "<span class='{$class}'>{$shortUsername}
                                <span style='float: right; color: lightseagreen;'>{$score}</span>
                            </span>";
                },
                'label' => 'апта',
                'enableSorting' => false,
                'attribute' => 'week',
                'format' => 'raw',
                'contentOptions' => ['style' => 'width: 25%;'],
            ],
            [
                'value' => function ($model) {
                    $user = User::findOne($model['month_user_id']);
                    $username = $user->username ?? '-';
                    $shortUsername = mb_strimwidth($username, 0, 6, '…');
                    $score = $model['month'] ?? '- -';
                    $class = ($model['month_user_id'] == Yii::$app->user->id) ? 'fw-bold' : '';
                    return "<span class='{$class}'>{$shortUsername}
                                <span style='float: right; color: dodgerblue;'>{$score}</span>
                            </span>";
                },
                'label' => 'ай',
                'enableSorting' => false,
                'attribute' => 'month',
                'format' => 'raw',
                'contentOptions' => ['style' => 'width: 25%;'],

            ],
            [
                'value' => function ($model) {
                    $user = User::findOne($model['all_time_user_id']);
                    $username = $user->username ?? '-';
                    $shortUsername = mb_strimwidth($username, 0, 6, '…');
                    $score = $model['all_time'] ?? '- -';
                    if ($score >= 1000000000) {
                        $score2 = round($score / 1000000000, 1) . 'b';
                    } elseif ($score >= 1000000) {
                        $score2 = round($score / 1000000, 1) . 'm';
                    } elseif ($score >= 1000) {
                        $score2 = round($score / 1000, 1) . 'k';
                    } else {
                        $score2 = $score;
                    }
                    $class = ($model['all_time_user_id'] == Yii::$app->user->id) ? 'fw-bold' : '';
                    return "<span class='{$class}'>{$shortUsername}
                                <span style='float: right; color: mediumpurple;'>{$score2}</span>
                            </span>";
                },
                'label' => 'барлығы',
                'enableSorting' => false,
                'attribute' => 'all_time',
                'format' => 'raw',
                'contentOptions' => ['style' => 'width: 25%;'],
            ],
        ],
    ]) ?>
    <div style="font-weight: bold;" class="text-center">
        рейтингтегі орныңыз
    </div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'showHeader' => false,
        'summary' => false,
        'tableOptions' => ['class' => 'table table-bordered'],
        'columns' => [
            [
                'value' => function ($model) {
                    $rank = Yii::$app->controller->getUserRank('day');
                    return "<span>#$rank
                                <span style='float: right; color: orangered'>$model->day</span>
                            </span>";
                },
                'attribute' => 'day',
                'format' => 'raw',
                'contentOptions' => ['style' => 'width: 25%;'],
            ],
            [
                'value' => function ($model) {
                    $rank = Yii::$app->controller->getUserRank('week');
                    return "<span>#$rank
                                <span style='float: right; color: lightseagreen'>$model->week</span>
                            </span>";
                },
                'attribute' => 'week',
                'format' => 'raw',
                'contentOptions' => ['style' => 'width: 25%;'],
            ],
            [
                'value' => function ($model) {
                    $rank = Yii::$app->controller->getUserRank('month');
                    return "<span>#$rank
                                <span style='float: right; color: dodgerblue'>$model->month</span>
                            </span>";
                },
                'attribute' => 'month',
                'format' => 'raw',
                'contentOptions' => ['style' => 'width: 25%;'],
            ],
            [
                'value' => function ($model) {
                    $rank = Yii::$app->controller->getUserRank('all_time');
                    $score = $model->all_time;
                    if ($score >= 1000000000) {
                        $score2 = round($score / 1000000000, 1) . 'b';
                    } elseif ($score >= 1000000) {
                        $score2 = round($score / 1000000, 1) . 'm';
                    } elseif ($score >= 1000) {
                        $score2 = round($score / 1000, 1) . 'k';
                    } else {
                        $score2 = $score;
                    }
                    return "<span>#$rank
                                <span style='float: right; color: mediumpurple'>$score2</span>
                            </span>";
                },
                'attribute' => 'all_time',
                'format' => 'raw',
                'contentOptions' => ['style' => 'width: 25%;'],
            ]
        ],
    ]); ?>
    <div class="text-center fw-bold mb-3 mt-5" style="font-size: 24px;">
        <?php
        $rating = Rating::findOne(['user_id' => Yii::$app->user->id]);
        $expPercentage = ($rating->exp / 3) * 100; // Convert exp to percentage
        ?>
        <?= $rating->user->username . ' (' . $rating->level . ' lvl)' ?>

        <!-- Progress Bar -->
        <div class="progress mt-2" style="height: 20px; width: 100%; margin: 0 auto;">
            <div class="progress-bar" role="progressbar" style="width: <?= $expPercentage ?>%; background-color: dimgrey"
                 aria-valuenow="<?= $expPercentage ?>" aria-valuemin="0" aria-valuemax="100">
            </div>
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => false,
        'tableOptions' => ['class' => 'table table-bordered'],
        'columns' => [
            [
                'label' => 'шайқастар',
                'attribute' => 'battles',
                'contentOptions' => ['style' => 'width: 50%;'],
                'enableSorting' => false,
            ],
            [
                'label' => 'жеңістер',
                'attribute' => 'wins',
                'contentOptions' => ['style' => 'width: 50%;'],
                'enableSorting' => false,
            ]
        ],
    ]); ?>


    <div class="row text-center align-items-center p-3 mx-1 mt-5 toggle-container"
         style="border: 1px solid black; border-radius: 10px; cursor: pointer;">
        <div class="col-4 text-end">
            Транслитерация
        </div>
        <div class="col-4 d-flex justify-content-center">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="toggleText" name="mode">
            </div>
        </div>
        <div class="col-4 text-start">
            Араб тілі
        </div>
    </div>

    <?php $form = ActiveForm::begin(['action' => ['site/random'], 'method' => 'get']); ?>
    <input type="hidden" id="hiddenToggle" name="mode" value="0">
    <div class="row mt-3">
        <div class="col-6">
            <?php
            $userId = Yii::$app->user->id;
            $message = urlencode("Ас саламу алейкум уа рахматуллахи уа баракатух! Досым, сені Құран Шайқасына шақырамын!\n\n" .
                Yii::$app->urlManager->createAbsoluteUrl(['site/friend', 'inviter_id' => $userId])
            );
            $whatsappUrl = "https://api.whatsapp.com/send?text=$message";
            ?>
            <?= Html::a('Досыңмен шайқас', $whatsappUrl, [
                'class' => 'btn btn-lg btn-success w-100',
                'target' => '_blank' // Opens WhatsApp in a new tab
            ]) ?>
        </div>


        <div class="col-6">
            <?= Html::submitButton('Бөтенмен шайқас', ['class' => 'btn btn-lg btn-danger w-100']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

    <script>
        document.querySelector(".toggle-container").addEventListener("click", function(event) {
            if (event.target.id !== "toggleText") {
                let toggle = document.getElementById("toggleText");
                toggle.checked = !toggle.checked;
                document.getElementById("hiddenToggle").value = toggle.checked ? '1' : '0';
            }
        });

        document.getElementById("toggleText").addEventListener("change", function() {
            document.getElementById("hiddenToggle").value = this.checked ? '1' : '0';
        });
    </script>

</div>
