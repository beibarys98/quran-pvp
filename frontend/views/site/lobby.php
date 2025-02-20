<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var $mode */

$this->title = "Quran-PVP";
?>

<div class="lobby-container" style="margin-top: 30vh;">
    <h1>Сізге қарсылас іздеудеміз</h1>

    <!-- Loading Animation -->
    <div class="loading-container">
        <div class="loading-spinner"></div>
    </div>

    <!-- Cancel Button -->
    <form style="margin-top: 30vh;" id="cancelForm" method="post" action="<?= Url::to(['site/cancel']) ?>">
        <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>
        <button type="submit" class="btn btn-lg btn-danger px-5">Артқа</button>
    </form>
</div>

<script>
    // Animate "Waiting for an opponent..."
    let dotCount = 0;
    setInterval(() => {
        dotCount = (dotCount + 1) % 4;
        document.getElementById('dots').innerText = ".".repeat(dotCount);
    }, 500);

    function checkForOpponent() {
        fetch('<?= Url::to(['site/find-opponent', 'mode' => $mode]) ?>')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = data.battleUrl; // Redirect to battle page with mode
                } else {
                    setTimeout(checkForOpponent, 2000); // Retry after 2 seconds
                }
            });
    }

    // Start checking for an opponent
    checkForOpponent();
</script>

<style>
    /* Center everything vertically and horizontally */
    .lobby-container {
        display: flex;
        flex-direction: column;
        align-items: center;    /* Horizontal center */
        text-align: center;     /* Ensure text is centered */
    }

    /* Loading spinner animation */
    .loading-container {
        margin: 20px 0; /* Space around spinner */
    }

    /* Spinner animation */
    .loading-spinner {
        width: 40px;
        height: 40px;
        border: 5px solid lightgray;
        border-top-color: red;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    /* Keyframes for rotation */
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
