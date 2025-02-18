<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\models\LoginForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'quran-pvp';
?>
<div class="site-login">
    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'action' => ['site/login'],
                'method' => 'post',
            ]); ?>

            <div class="input-group mb-3">
                <input name="username" type="text" class="form-control" placeholder="Логин ойлап табыңыз!" aria-label="Recipient's username" aria-describedby="button-addon2">
                <button class="btn btn-outline-primary" type="submit" id="button-addon2">Button</button>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
