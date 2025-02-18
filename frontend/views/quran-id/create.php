<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\QuranId $model */

$this->title = Yii::t('app', 'Create Quran Id');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Quran Ids'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="quran-id-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
