<?php

/** @var \yii\web\View $this */
/** @var string $content */

use common\widgets\Alert;
use frontend\assets\AppAsset;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header>
    <?php
    NavBar::begin([
        'options' => [
            'class' => 'navbar navbar-expand navbar-dark bg-dark fixed-bottom',
        ],
    ]);
    $menuItems = [];

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav me-auto'],
        'items' => $menuItems,
    ]);
    if (Yii::$app->user->isGuest) {
        echo Html::tag('div',
            Html::a('Quran-PVP', ['#'], [
                'class' => ['btn', 'btn-link', 'login', 'text-decoration-none'],
                'style' => 'color: green; height: 40px; display: inline-flex; align-items: center;'
            ]),
            ['class' => ['d-flex']]
        );
    } else {
        echo Html::beginForm(['/site/logout'], 'post', ['class' => 'd-flex'])
            . Html::submitButton('Шығу', [
                'class' => ['btn', 'btn-link', 'logout', 'text-decoration-none'],
                'style' => 'color: red; height: 40px; display: inline-flex; align-items: center;'
            ])
            . Html::endForm();
    }

    NavBar::end();
    ?>
</header>

<main role="main" class="flex-shrink-0">
    <div class="container position-relative">
        <?= Alert::widget([
                'options' => [
                    'class' => 'alert-overlay',
                ],
                'closeButton' => false
        ]) ?>
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</main>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();?>

<script>
    setTimeout(function() {
        var alert = document.querySelector('.alert-overlay');
        if (alert) {
            alert.style.display = 'none';
        }
    }, 5000);
</script>
