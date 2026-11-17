<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Perfil $modelPerfil */
/** @var common\models\User $modelUser */

$this->title = 'Editar Colaborador: ' . $modelPerfil->nome;
$this->params['breadcrumbs'][] = ['label' => 'Colaboradores', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $modelPerfil->nome, 'url' => ['view', 'id_perfil' => $modelPerfil->id_perfil]];
$this->params['breadcrumbs'][] = 'Editar';
?>
<div class="perfil-update container-fluid">
    <?= $this->render('_form', [
        'modelPerfil' => $modelPerfil,
        'modelUser' => $modelUser,
    ]) ?>
</div>