<?php

/**
 * Declaração de estrito cumprimento de tipos em PHP (strict_types=1).
 */
declare(strict_types=1);

/** @var yii\web\View $this */
/** @var string $content Conteúdo principal injetado pela view (ex: a página de login) */

// Importa os assets do backend (para carregar o Bootstrap, CSS e JS) e os helpers do HTML
use backend\assets\AppAsset;
use yii\helpers\Html;

// Regista o pacote de assets principal na vista
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>" class="h-100">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Regista as tags meta de segurança CSRF -->
        <?php $this->registerCsrfMetaTags() ?>

        <!-- Título da página web -->
        <title><?= Html::encode($this->title) ?></title>

        <!-- Injeta os estilos CSS e cabeçalhos necessários do Yii2 -->
        <?php $this->head() ?>
    </head>
    <body class="h-100 d-flex align-items-center justify-content-center bg-light">
    <?php $this->beginBody() ?>

    <!-- Contentor principal centralizado no ecrã para albergar o formulário de login limpo -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <?= $content ?>
            </div>
        </div>
    </div>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage(); ?>