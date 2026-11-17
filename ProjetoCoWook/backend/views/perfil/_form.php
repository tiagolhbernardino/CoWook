<?php

use common\models\User;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Perfil $modelPerfil */
/** @var common\models\User $modelUser */
/** @var yii\widgets\ActiveForm $form */

$isNew = $modelPerfil->isNewRecord;
?>

<div class="perfil-form">
    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <!-- Coluna 1: Dados de Conta e Autenticação (Tabela User) -->
        <div class="col-md-6">
            <div class="card card-primary card-outline shadow-sm">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-lock mr-2 text-primary"></i> 1. Credenciais de Acesso (User)
                    </h3>
                </div>
                <div class="card-body">
                    <?= $form->field($modelUser, 'username')->textInput([
                        'maxlength' => true,
                        'placeholder' => 'Ex: joao.silva',
                    ]) ?>

                    <?= $form->field($modelUser, 'email')->input('email', [
                        'maxlength' => true,
                        'placeholder' => 'Ex: joao.silva@cowook.pt',
                    ]) ?>

                    <?= $form->field($modelUser, 'password')->passwordInput([
                        'maxlength' => true,
                        'placeholder' => $isNew ? 'Mínimo de 6 caracteres' : 'Deixe em branco para manter a atual',
                    ])->hint($isNew ? false : 'Preencha apenas se desejar alterar a palavra-passe atual.') ?>

                    <?= $form->field($modelUser, 'status')->dropDownList([
                        User::STATUS_ACTIVE => 'Ativo (Pode iniciar sessão)',
                        User::STATUS_INACTIVE => 'Inativo (Acesso suspenso)',
                        User::STATUS_DELETED => 'Eliminado (Soft Delete)',
                    ], ['class' => 'form-control custom-select']) ?>
                </div>
            </div>
        </div>

        <!-- Coluna 2: Dados Pessoais e Role (Tabela Perfil) -->
        <div class="col-md-6">
            <div class="card card-info card-outline shadow-sm">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-id-card mr-2 text-info"></i> 2. Dados do Perfil e Role
                    </h3>
                </div>
                <div class="card-body">
                    <?= $form->field($modelPerfil, 'nome')->textInput([
                        'maxlength' => true,
                        'placeholder' => 'Ex: João Manuel da Silva',
                    ]) ?>

                    <?= $form->field($modelPerfil, 'role')->dropDownList([
                        'sys_admin' => 'Administrador Global (SysAdmin)',
                        'rececionista' => 'Rececionista (Balcão)',
                        'equipa_de_limpeza' => 'Equipa de Limpeza (Operacional)',
                        'cliente' => 'Cliente (Apenas Front-Office)',
                    ], ['class' => 'form-control custom-select']) ?>

                    <?= $form->field($modelPerfil, 'nif')->textInput([
                        'maxlength' => 9,
                        'placeholder' => 'Ex: 123456789 (Opcional)',
                    ]) ?>

                    <?= $form->field($modelPerfil, 'morada')->textarea([
                        'rows' => 3,
                        'placeholder' => 'Rua, Número, Código Postal e Localidade',
                    ]) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body d-flex justify-content-between align-items-center">
            <?= Html::a('<i class="fas fa-arrow-left mr-1"></i> Cancelar e Voltar', ['index'], ['class' => 'btn btn-secondary']) ?>
            <?= Html::submitButton(
                $isNew ? '<i class="fas fa-check mr-1"></i> Registar Colaborador' : '<i class="fas fa-save mr-1"></i> Guardar Alterações',
                ['class' => $isNew ? 'btn btn-success btn-lg font-weight-bold' : 'btn btn-primary btn-lg font-weight-bold']
            ) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>