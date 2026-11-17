<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \frontend\models\SignupForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;

$this->title = 'Registo';
?>

<!-- Contentor geral para centrar o cartão, com mais margem/padding no topo para afastar da navbar -->
<div style="display: flex; justify-content: center; align-items: center; min-height: 85vh; padding: 60px 20px 30px 20px;">

    <!-- Cartão idêntico ao mockup: fundo escuro, contorno branco/cinzento, cantos super arredondados -->
    <div style="background-color: #0d0d0d; border: 2px solid #cccccc; border-radius: 45px; padding: 35px 30px; max-width: 420px; width: 100%; box-shadow: 0 15px 35px rgba(0,0,0,0.8); color: #ffffff;">

        <!-- Título "Registo" em destaque a laranja e centrado -->
        <h1 style="color: #ef6603; font-size: 2.3rem; font-weight: 700; text-align: center; margin-bottom: 25px;">Registo</h1>

        <!-- Início do formulário -->
        <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

        <!-- Campo: Nome Completo -->
        <div style="margin-bottom: 12px;">
            <label style="color: #ffffff; font-size: 0.85rem; font-weight: 500; margin-bottom: 4px; display: block;">Nome Completo</label>
            <?= $form->field($model, 'username', ['options' => ['tag' => false]])->textInput([
                'autofocus' => true,
                'style' => 'background-color: #161616; border: 1px solid #777777; color: #ffffff; border-radius: 6px; padding: 8px 12px; width: 100%; font-size: 0.9rem;'
            ])->label(false) ?>
        </div>

        <!--Nome do perfil-->
        <div style="margin-bottom: 12px;">
            <label style="color: #ffffff; font-size: 0.85rem; font-weight: 500; margin-bottom: 4px; display: block;">Nome de Perfil</label>
            <?= $form->field($model, 'nome', ['options' => ['tag' => false]])->textInput([
                'autofocus' => true,
                'style' => 'background-color: #161616; border: 1px solid #777777; color: #ffffff; border-radius: 6px; padding: 8px 12px; width: 100%; font-size: 0.9rem;'
            ])->label(false) ?>
        </div>

        <!-- Campo: Email -->
        <div style="margin-bottom: 12px;">
            <label style="color: #ffffff; font-size: 0.85rem; font-weight: 500; margin-bottom: 4px; display: block;">Email</label>
            <?= $form->field($model, 'email', ['options' => ['tag' => false]])->input('email', [
                'style' => 'background-color: #161616; border: 1px solid #777777; color: #ffffff; border-radius: 6px; padding: 8px 12px; width: 100%; font-size: 0.9rem;'
            ])->label(false) ?>
        </div>

        <!-- Campo: Palavra-passe -->
        <div style="margin-bottom: 12px;">
            <label style="color: #ffffff; font-size: 0.85rem; font-weight: 500; margin-bottom: 4px; display: block;">Palavra-passe</label>
            <?= $form->field($model, 'password', ['options' => ['tag' => false]])->passwordInput([
                'style' => 'background-color: #161616; border: 1px solid #777777; color: #ffffff; border-radius: 6px; padding: 8px 12px; width: 100%; font-size: 0.9rem;'
            ])->label(false) ?>
        </div>

        <!-- Campo: Reintroduzir Palavra-passe -->
        <div style="margin-bottom: 12px;">
            <label style="color: #ffffff; font-size: 0.85rem; font-weight: 500; margin-bottom: 4px; display: block;">Reintroduza a palavra passe</label>
            <?= $form->field($model, 'password_repeat', ['options' => ['tag' => false]])->passwordInput([
                'style' => 'background-color: #161616; border: 1px solid #777777; color: #ffffff; border-radius: 6px; padding: 8px 12px; width: 100%; font-size: 0.9rem;'
            ])->label(false) ?>
        </div>

        <!-- Campo: Morada -->
        <div style="margin-bottom: 18px;">
            <label style="color: #ffffff; font-size: 0.85rem; font-weight: 500; margin-bottom: 4px; display: block;">Morada</label>
            <?= $form->field($model, 'morada', ['options' => ['tag' => false]])->textInput([
                'style' => 'background-color: #161616; border: 1px solid #777777; color: #ffffff; border-radius: 6px; padding: 8px 12px; width: 100%; font-size: 0.9rem;'
            ])->label(false) ?>
        </div>

        <!-- Botão de Registar (com o estilo arredondado do mockup) -->
        <div style="text-align: center; margin-top: 15px; margin-bottom: 15px;">
            <?= Html::submitButton('Registar', [
                'style' => 'background-color: #161616; color: #ffffff; border: 1px solid #cccccc; border-radius: 10px; padding: 8px 35px; font-size: 0.95rem; font-weight: 600; cursor: pointer;'
            ]) ?>
        </div>

        <!-- Rodapé igual ao mockup ("Tem dúvidas? CLICA AQUI") -->
        <div style="text-align: center; font-size: 0.8rem; color: #cccccc;">
            <span>Tem dúvidas? </span>
            <a href="<?= Url::to(['site/login']) ?>" style="color: #1a66cc; text-decoration: none; font-weight: 600;">CLICA AQUI</a>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>