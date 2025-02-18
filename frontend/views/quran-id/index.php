<?php

use common\models\QuranId;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\search\QuranIdSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Quran Ids');
?>
<div class="quran-id-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pager' => [
            'class' => yii\bootstrap5\LinkPager::class,
        ],
        'columns' => [
            [
                'attribute' => 'suraId',
                'contentOptions' => ['style' => 'width:5%'],
            ],
            [
                'attribute' => 'verseID',
                'contentOptions' => ['style' => 'width:5%'],
            ],
            [
                'attribute' => 'ayahText',
                'format' => 'ntext',
                'contentOptions' => ['style' => 'width:45%; word-break: break-word; white-space: normal;'],
            ],
            [
                'attribute' => 'readText',
                'format' => 'ntext',
                'contentOptions' => ['style' => 'width:45%; word-break: break-word; white-space: normal;'],
            ],
        ],
    ]); ?>


</div>
