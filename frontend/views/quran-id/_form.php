<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\QuranId $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="quran-id-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id')->textInput() ?>

    <?= $form->field($model, 'suraId')->textInput() ?>

    <?= $form->field($model, 'verseID')->textInput() ?>

    <?= $form->field($model, 'ayahText')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'readText')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
