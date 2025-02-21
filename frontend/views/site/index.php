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
    <div style='border: 1px solid black; border-radius: 10px;' class='p-1'>
        <div style="font-weight: bold;" class="text-center">
            рейтинг
        </div>
        <?= GridView::widget([
            'dataProvider' => $dataProviderCombined,
            'summary' => false,
            'tableOptions' => ['class' => 'table table-hover'],
            'columns' => [
                [
                    'value' => function ($model) {
                        $user = User::findOne($model['day_user_id']);
                        $username = $user->username ?? '---';
                        $score = $model['day'] ?? '---';
                        $class = ($model['day_user_id'] == Yii::$app->user->id) ? 'fw-bold' : '';

                        return "<div style='display: flex; justify-content: space-between; align-items: center; font-size: 10px;' class='{$class}'>
								<span>{$username}</span>
								<span style='color: orangered;'>{$score}</span>
							</div>";
                    },
                    'label' => 'күн',
                    'enableSorting' => false,
                    'attribute' => 'day',
                    'format' => 'raw',
                    'contentOptions' => ['style' => 'width: 25%; vertical-align: middle;'],
                    'headerOptions' => ['class' => 'text-center'], // Optional: center header text
                ],

                [
                    'value' => function ($model) {
                        $user = User::findOne($model['week_user_id']);
                        $username = $user->username ?? '---';
                        $score = $model['week'] ?? '---';
                        if ($score >= 1000000000) {
                            $score2 = round($score / 1000000000) . 'b';
                        } elseif ($score >= 1000000) {
                            $score2 = round($score / 1000000) . 'm';
                        } elseif ($score >= 1000) {
                            $score2 = round($score / 1000) . 'k';
                        } else {
                            $score2 = $score;
                        }
                        $class = ($model['week_user_id'] == Yii::$app->user->id) ? 'fw-bold' : '';
                        return "<div style='display: flex; justify-content: space-between; align-items: center; font-size: 10px;' class='{$class}'>
								<span>{$username}</span>
								<span style='color: lightseagreen;'>{$score}</span>
							</div>";
                    },
                    'label' => 'апта',
                    'enableSorting' => false,
                    'attribute' => 'week',
                    'format' => 'raw',
                    'contentOptions' => ['style' => 'width: 25%; vertical-align: middle;'],
                    'headerOptions' => ['class' => 'text-center'], // Optional: center header text
                ],
                [
                    'value' => function ($model) {
                        $user = User::findOne($model['month_user_id']);
                        $username = $user->username ?? '---';
                        $score = $model['month'] ?? '---';
                        if ($score >= 1000000000) {
                            $score2 = round($score / 1000000000) . 'b';
                        } elseif ($score >= 1000000) {
                            $score2 = round($score / 1000000) . 'm';
                        } elseif ($score >= 1000) {
                            $score2 = round($score / 1000) . 'k';
                        } else {
                            $score2 = $score;
                        }
                        $class = ($model['month_user_id'] == Yii::$app->user->id) ? 'fw-bold' : '';
                        return "<div style='display: flex; justify-content: space-between; align-items: center; font-size: 10px;' class='{$class}'>
								<span>{$username}</span>
								<span style='color: dodgerblue;'>{$score}</span>
							</div>";
                    },
                    'label' => 'ай',
                    'enableSorting' => false,
                    'attribute' => 'month',
                    'format' => 'raw',
                    'contentOptions' => ['style' => 'width: 25%; vertical-align: middle;'],
                    'headerOptions' => ['class' => 'text-center'], // Optional: center header text

                ],
                [
                    'value' => function ($model) {
                        $user = User::findOne($model['all_time_user_id']);
                        $username = $user->username ?? '---';
                        $score = $model['all_time'] ?? '---';
                        if ($score >= 1000000000) {
                            $score2 = round($score / 1000000000) . 'b';
                        } elseif ($score >= 1000000) {
                            $score2 = round($score / 1000000) . 'm';
                        } elseif ($score >= 1000) {
                            $score2 = round($score / 1000) . 'k';
                        } else {
                            $score2 = $score;
                        }
                        $class = ($model['all_time_user_id'] == Yii::$app->user->id) ? 'fw-bold' : '';
                        return "<div style='display: flex; justify-content: space-between; align-items: center; font-size: 10px;' class='{$class}'>
								<span>{$username}</span>
								<span style='color: mediumpurple;'>{$score}</span>
							</div>";
                    },
                    'label' => 'барлығы',
                    'enableSorting' => false,
                    'attribute' => 'all_time',
                    'format' => 'raw',
                    'contentOptions' => ['style' => 'width: 25%; vertical-align: middle;'],
                    'headerOptions' => ['class' => 'text-center'], // Optional: center header text
                ],
            ],
        ]) ?>
    </div>

    <div style='border: 1px solid black; border-radius: 10px;' class='p-1 mt-3'>
        <div style="font-weight: bold;" class="text-center">
            рейтингтегі орныңыз
        </div>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'showHeader' => false,
            'summary' => false,
            'tableOptions' => ['class' => 'table table-hover'],
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
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'summary' => false,
            'tableOptions' => ['class' => 'table table-hover text-center'], // Center all table text
            'columns' => [
                [
                    'label' => 'шайқастар',
                    'attribute' => 'battles',
                    'headerOptions' => ['class' => 'text-center'], // Center header
                    'contentOptions' => ['class' => 'text-center', 'style' => 'width: 50%;'], // Center value
                    'enableSorting' => false,
                ],
                [
                    'label' => 'жеңістер',
                    'attribute' => 'wins',
                    'headerOptions' => ['class' => 'text-center'], // Center header
                    'contentOptions' => ['class' => 'text-center', 'style' => 'width: 50%;'], // Center value
                    'enableSorting' => false,
                ]
            ],
        ]); ?>

        <div class="text-center fw-bold">
            <?php
            $rating = Rating::findOne(['user_id' => Yii::$app->user->id]);
            $expPercentage = ($rating->exp / 3) * 100; // Convert exp to percentage
            ?>

            <!-- Progress Bar -->
            <div class="progress mt-2" style="height: 20px; width: 100%; margin: 0 auto;">
                <div class="progress-bar" role="progressbar" style="width: <?= $expPercentage ?>%; background-color: gold"
                     aria-valuenow="<?= $expPercentage ?>" aria-valuemin="0" aria-valuemax="100">
                </div>
            </div>
            <?= $rating->user->username . ' (' . $rating->level . ' lvl)' ?>
        </div>
    </div>

    <div style='border: 1px solid black; border-radius: 10px;' class='p-1 mt-3'>
        <div class="row text-center align-items-center p-3 mx-1 toggle-container"
             style="border: 1px solid black; border-radius: 10px; cursor: pointer;">
            <div class="col-4 text-end">
                Транслит.
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

        <?php $form = ActiveForm::begin(['action' => ['site/random'], 'method' => 'get']); ?>
        <input type="hidden" id="hiddenToggle" name="mode" value="0">
        <div class="row mt-3">
            <div class="col-6">
                <?php
                $userId = Yii::$app->user->id;
                ?>
                <?= Html::a('Досыңмен шайқас', 'javascript:void(0);', [
                    'class' => 'btn btn-lg btn-success w-100',
                    'target' => '_blank',
                    'id' => 'whatsappInvite'
                ]) ?>
            </div>

            <script>
                document.getElementById("whatsappInvite").addEventListener("click", function() {
                    let mode = document.getElementById("hiddenToggle").value;
                    let userId = "<?= $userId ?>";
                    let message = encodeURIComponent("Ас саламу алейкум уа рахматуллахи уа баракатух! Досым, сені Құран Шайқасына шақырамын!\n\n" +
                        "<?= Yii::$app->urlManager->createAbsoluteUrl(['site/friend']) ?>" + "?inviter_id=" + userId + "&mode=" + mode
                    );
                    let whatsappUrl = "https://api.whatsapp.com/send?text=" + message;
                    window.open(whatsappUrl, '_blank');
                });
            </script>

            <div class="col-6">
                <?= Html::submitButton('Бөтенмен шайқас', ['class' => 'btn btn-lg btn-danger w-100']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

</div>
