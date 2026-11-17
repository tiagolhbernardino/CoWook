<?php

/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

declare(strict_types=1);

namespace frontend\assets;

use common\assets\ColorModeAsset;
use yii\bootstrap5\BootstrapAsset;
use yii\web\AssetBundle;
use yii\web\YiiAsset;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        // 1. Ícones externos (FontAwesome e Bootstrap Icons que todos os templates do HTML Codex usam)
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css',
        'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css',

        // 2. Bibliotecas de animação do template (que colaste na pasta frontend/web/lib/)
        'lib/animate/animate.min.css',
        'lib/owlcarousel/assets/owl.carousel.min.css',

        // 3. Ficheiros CSS principais do template (que colaste na pasta frontend/web/css/)
        'css/bootstrap.min.css', // O Bootstrap já com as cores do teu template
        'css/style.css',
    ];

    // Lista de todos os ficheiros JavaScript (carregados automaticamente no final do <body>)
    public $js = [
        // 1. Bibliotecas JS que fazem funcionar os carrosséis de imagens e animações ao fazer scroll
        'lib/wow/wow.min.js',
        'lib/easing/easing.min.js',
        'lib/waypoints/waypoints.min.js',
        'lib/owlcarousel/owl.carousel.min.js',

        // 2. O ficheiro JS principal do template (que colaste em frontend/web/js/)
        'js/main.js',
    ];

    // Garante que o jQuery e o Bootstrap do próprio Yii2 carregam ANTES do template para não dar erros
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset',
        'yii\bootstrap5\BootstrapPluginAsset',
    ];
}
