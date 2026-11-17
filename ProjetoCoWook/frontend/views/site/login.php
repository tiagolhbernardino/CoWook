<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\models\LoginForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;

$this->title = 'Login';
?>

<!-- Contentor geral para centrar o cartão no meio do ecrã, com margem de topo para afastar da navbar -->
<div style="display: flex; justify-content: center; align-items: center; min-height: 82vh; padding: 60px 20px 30px 20px;">

    <!-- Cartão idêntico ao do Registo: fundo escuro, contorno cinzento, cantos super arredondados -->
    <div style="background-color: #0d0d0d; border: 2px solid #cccccc; border-radius: 45px; padding: 40px 30px; max-width: 420px; width: 100%; box-shadow: 0 15px 35px rgba(0,0,0,0.8); color: #ffffff;">

        <!-- Título "Login" em destaque a laranja e centrado -->
        <h1 style="color: #ef6603; font-size: 2.3rem; font-weight: 700; text-align: center; margin-bottom: 30px;">Login</h1>

        <!-- Início do formulário de Login -->
        <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

        <!-- Campo: Nome Utilizador / Username -->
        <div style="margin-bottom: 15px;">
            <label style="color: #ffffff; font-size: 0.85rem; font-weight: 500; margin-bottom: 4px; display: block;">Email</label>
            <?= $form->field($model, 'email', ['options' => ['tag' => false]])->textInput([
                'autofocus' => true,
                'style' => 'background-color: #161616; border: 1px solid #777777; color: #ffffff; border-radius: 6px; padding: 9px 12px; width: 100%; font-size: 0.9rem;'
            ])->label(false) ?>
        </div>

        <!-- Campo: Palavra-passe / Senha -->
        <div style="margin-bottom: 20px;">
            <label style="color: #ffffff; font-size: 0.85rem; font-weight: 500; margin-bottom: 4px; display: block;">Senha</label>
            <?= $form->field($model, 'password', ['options' => ['tag' => false]])->passwordInput([
                'style' => 'background-color: #161616; border: 1px solid #777777; color: #ffffff; border-radius: 6px; padding: 9px 12px; width: 100%; font-size: 0.9rem;'
            ])->label(false) ?>
        </div>

        <!-- Checkbox: Manter sessão iniciada -->
        <div style="margin-bottom: 20px; text-align: center;">
            <?= $form->field($model, 'rememberMe')->checkbox([
                'label' => 'Manter a sessão iniciada',
                'labelOptions' => ['style' => 'color: #cccccc; font-size: 0.85rem; cursor: pointer;']
            ]) ?>
        </div>

        <!-- Botão de Login (com o estilo arredondado idêntico ao registo) -->
        <div style="text-align: center; margin-bottom: 20px;">
            <?= Html::submitButton('LOGIN', [
                'style' => 'background-color: #161616; color: #ffffff; border: 1px solid #cccccc; border-radius: 10px; padding: 8px 35px; font-size: 0.95rem; font-weight: 600; cursor: pointer;'
            ]) ?>
        </div>

        <!-- Link de recuperação de palavra-passe -->
        <div style="text-align: center; font-size: 0.8rem; color: #cccccc;">
            <a href="<?= Url::to(['site/request-password-reset']) ?>" style="color: #1a66cc; text-decoration: none; font-weight: 600;">Esqueceu a palavra-passe?</a>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>