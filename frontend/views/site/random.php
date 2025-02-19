<?php

/** @var yii\web\View $this */
/** @var $dataProviderCombined */
/** @var $dataProvider */
/** @var $mode */

use common\models\Rating;
use common\models\User;
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = Yii::$app->name;
?>
<div class="site-index">
    <?= $mode ?>
</div>
