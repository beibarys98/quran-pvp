<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var User $model */

use common\models\User;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Quran-PVP';
?>
<div class="site-signup">
    <div style="margin: 0 auto; width: 300px; margin-top: 75vh;">
        <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

        <div class="input-group">
            <?= Html::activeTextInput($model, 'username', [
                'class' => 'form-control',
                'placeholder' => 'Логин ойлап табыңыз!',
                'aria-label' => 'Recipient\'s username',
                'aria-describedby' => 'button-addon2',
            ]) ?>
            <button class="btn btn-outline-primary" type="submit" id="button-addon2">Кіру</button>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
