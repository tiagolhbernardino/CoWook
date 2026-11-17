<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var string $content */

use common\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\helpers\Html;

?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>" class="h-100" data-bs-theme="light">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <?php $this->head() ?>
        <title><?= Html::encode($this->title) ?></title>

        <!-- CORREÇÃO: O _head renderiza-se DENTRO da tag <head> -->
        <?= $this->render('_head') ?>
    </head>
    <body class="d-flex flex-column h-100">
    <?php $this->beginBody() ?>

    <!-- Cabeçalho (Navbar do template) -->
    <?= $this->render('_header') ?>

    <main id="main" class="flex-grow-1" role="main">
        <!-- Nota: Se o template do HTML Codex usar secções de largura total (full-width),
             podes querer remover a classe 'container' desta div para o design não ficar limitado às margens. -->
        <div class="container">
            <?php if (!empty($this->params['breadcrumbs'])): ?>
                <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
            <?php endif ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </main>

    <!-- Rodapé do template -->
    <?= $this->render('_footer') ?>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>