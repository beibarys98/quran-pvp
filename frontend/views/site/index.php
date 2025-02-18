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
        'tableOptions' => ['class' => 'table table-hover'],
        'columns' => [
            [
                'value' => function ($model) {
                    $user = User::findOne($model['day_user_id']);
                    $username = $user->username ?? '-';
                    $shortUsername = mb_strimwidth($username, 0, 6, '…');
                    $score = $model['day'] ?? '- -';
                    $class = ($model['day_user_id'] == Yii::$app->user->id) ? 'text-success fw-bold' : '';
                    return "<span class='{$class}'>{$shortUsername} <span style='color: orangered;'>{$score}</span></span>";
                },
                'label' => 'күн',
                'enableSorting' => false,
                'attribute' => 'day',
                'format' => 'raw',
                'contentOptions' => ['style' => 'width: 25%;'],
            ],
            [
                'attribute' => 'week',
                'label' => 'апта',
                'value' => function ($model) {
                    $user = User::findOne($model['week_user_id']);
                    $username = $user->username ?? '-';
                    $shortUsername = mb_strimwidth($username, 0, 7, '…');
                    $score = $model['week'] ?? '- -';
                    $class = ($model['week_user_id'] == Yii::$app->user->id) ? 'text-success fw-bold' : '';
                    return "<span class='{$class}'>{$shortUsername} <span style='color: lightseagreen;'>{$score}</span></span>";
                },
                'format' => 'raw',
                'contentOptions' => ['style' => 'width: 25%;'],
                'enableSorting' => false,
            ],
            [
                'attribute' => 'month',
                'label' => 'ай',
                'value' => function ($model) {
                    $user = User::findOne($model['month_user_id']);
                    $username = $user->username ?? '-';
                    $shortUsername = mb_strimwidth($username, 0, 6, '…');
                    $score = $model['month'] ?? '- -';
                    $class = ($model['month_user_id'] == Yii::$app->user->id) ? 'text-success fw-bold' : '';
                    return "<span class='{$class}'>{$shortUsername} <span style='color: dodgerblue;'>{$score}</span></span>";
                },
                'format' => 'raw',
                'contentOptions' => ['style' => 'width: 25%;'],
                'enableSorting' => false,
            ],
            [
                'attribute' => 'all_time',
                'label' => 'барлығы',
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
                    $class = ($model['all_time_user_id'] == Yii::$app->user->id) ? 'text-success fw-bold' : '';
                    return "<span class='{$class}'>{$shortUsername} <span style='color: mediumpurple;'>{$score2}</span></span>";
                },
                'format' => 'raw',
                'contentOptions' => ['style' => 'width: 25%;'],
                'enableSorting' => false,
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
        'tableOptions' => ['class' => 'table table-hover'],
        'columns' => [
            [
                'attribute' => 'day',
                'value' => function () {
                    $rank = Yii::$app->controller->getUserRank('day');
                    switch ($rank) {
                        case 1:
                            return "<span style='color: gold; font-weight: bold;'>$rank</span>";
                        case 2:
                            return "<span style='color: silver; font-weight: bold;'>$rank</span>";
                        case 3:
                            return "<span style='color: #cd7f32; font-weight: bold;'>$rank</span>"; // Bronze color
                        default:
                            return $rank;
                    }
                },
                'format' => 'raw',
                'contentOptions' => ['style' => 'width: 25%;'],
            ],
            [
                'attribute' => 'week',
                'value' => function () {
                    $rank = Yii::$app->controller->getUserRank('week');
                    switch ($rank) {
                        case 1:
                            return "<span style='color: gold; font-weight: bold;'>$rank</span>";
                        case 2:
                            return "<span style='color: silver; font-weight: bold;'>$rank</span>";
                        case 3:
                            return "<span style='color: #cd7f32; font-weight: bold;'>$rank</span>"; // Bronze color
                        default:
                            return $rank;
                    }
                },
                'format' => 'raw',
                'contentOptions' => ['style' => 'width: 25%;'],
            ],
            [
                'attribute' => 'month',
                'value' => function () {
                    $rank = Yii::$app->controller->getUserRank('month');
                    switch ($rank) {
                        case 1:
                            return "<span style='color: gold; font-weight: bold;'>$rank</span>";
                        case 2:
                            return "<span style='color: silver; font-weight: bold;'>$rank</span>";
                        case 3:
                            return "<span style='color: #cd7f32; font-weight: bold;'>$rank</span>"; // Bronze color
                        default:
                            return $rank;
                    }
                },
                'format' => 'raw',
                'contentOptions' => ['style' => 'width: 25%;'],
            ],
            [
                'attribute' => 'all_time',
                'value' => function () {
                    $rank = Yii::$app->controller->getUserRank('all_time');
                    switch ($rank) {
                        case 1:
                            return "<span style='color: gold; font-weight: bold;'>$rank</span>";
                        case 2:
                            return "<span style='color: silver; font-weight: bold;'>$rank</span>";
                        case 3:
                            return "<span style='color: #cd7f32; font-weight: bold;'>$rank</span>"; // Bronze color
                        default:
                            return $rank;
                    }
                },
                'format' => 'raw',
                'contentOptions' => ['style' => 'width: 25%;'],
            ]
        ],
    ]); ?>
    <div style="font-weight: bold;" class="text-center mt-5">
        <?= User::findOne(Yii::$app->user->id)->username ?>
    </div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => false,
        'tableOptions' => ['class' => 'table table-hover'],
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
