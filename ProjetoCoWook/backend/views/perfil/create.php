<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Perfil $modelPerfil */
/** @var common\models\User $modelUser */

$this->title = 'Adicionar Novo Colaborador';
$this->params['breadcrumbs'][] = ['label' => 'Colaboradores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="perfil-create container-fluid">
    <?= $this->render('_form', [
        'modelPerfil' => $modelPerfil,
        'modelUser' => $modelUser,
    ]) ?>
</div>