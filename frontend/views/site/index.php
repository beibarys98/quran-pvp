<?php

/** @var yii\web\View $this */
/** @var $dataProviderCombined */
/** @var $dataProvider */
/** @var $dataProvider2 */

use common\models\User;
use yii\grid\GridView;

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
    <div style="font-weight: bold;" class="text-center mt-5">
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
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => false,
        'tableOptions' => ['class' => 'table table-bordered'],
        'columns' => [
            [
                'label' => 'деңгей',
                'attribute' => 'level',
                'contentOptions' => ['style' => 'width: 33%;'],
                'enableSorting' => false,
            ],
            [
                'label' => 'тартыс',
                'attribute' => 'battles',
                'contentOptions' => ['style' => 'width: 33%;'],
                'enableSorting' => false,
            ],
            [
                'label' => 'жеңіс',
                'attribute' => 'wins',
                'contentOptions' => ['style' => 'width: 33%;'],
                'enableSorting' => false,
            ]
        ],
    ]); ?>
</div>
