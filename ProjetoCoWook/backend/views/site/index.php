<?php

// Ativa a verificação estrita de tipos de dados do PHP 8
declare(strict_types=1);

/** @var yii\web\View $this */

// Importa os modelos User e Perfil para consultar estatísticas na base de dados
use common\models\User;
use common\models\Perfil;
// Importa os helpers Html e Url para gerar botões, formulários e links seguros
use yii\helpers\Html;
use yii\helpers\Url;

// Define o título da página no cabeçalho do AdminLTE
$this->title = 'Dashboard';

// Obtém o nome do utilizador logado (tenta primeiro o nome do Perfil, depois o username)
$username = Yii::$app->user->identity?->perfil?->nome ?? Yii::$app->user->identity?->username ?? 'Utilizador';

// Gera dinamicamente o URL para abrir o Front-Office trocando 'backend' por 'frontend'
$frontendUrl = str_replace('/backend/web', '/frontend/web', Yii::$app->request->baseUrl) . '/index.php';

// Inicializa as variáveis de contagem a zero
$totalColaboradores = 0;
$colaboradoresAtivos = 0;
$colaboradoresInativos = 0;

// Só executa as contagens na base de dados se o utilizador tiver a permissão 'gerirUtilizadores' (SysAdmin)
if (Yii::$app->user->can('gerirUtilizadores')) {
    // Conta o número total de registos na tabela 'perfil'
    $totalColaboradores = Perfil::find()->count();

    // Conta quantos perfis têm conta ativa (status = 10)
    $colaboradoresAtivos = Perfil::find()
        ->joinWith('user')
        ->where(['{{%user}}.status' => User::STATUS_ACTIVE])
        ->count();

    // Conta quantos perfis estão inativos ou desativados por Soft Delete (status != 10)
    $colaboradoresInativos = Perfil::find()
        ->joinWith('user')
        ->where(['!=', '{{%user}}.status', User::STATUS_ACTIVE])
        ->count();
}
?>

<!-- Contentor principal do Dashboard -->
<div class="site-index container-fluid">

    <!-- Banner de Boas-Vindas -->
    <div class="p-4 p-lg-5 mb-4 text-white rounded shadow-sm" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="font-weight-bold mb-2">Bem-vindo de volta, <?= Html::encode($username) ?>!</h1>
                <p class="mb-3" style="opacity: 0.85; font-size: 1.05rem;">
                    Painel operacional CoWook. Os menus e atalhos estão ajustados às tuas permissões de acesso.
                </p>

                <!-- Botões exclusivos do SysAdmin -->
                <?php if (Yii::$app->user->can('gerirUtilizadores')): ?>
                    <a href="<?= Url::to(['perfil/create']) ?>" class="btn btn-light font-weight-bold text-primary mr-2 mb-2 shadow-sm">
                        <i class="fas fa-user-plus mr-1"></i> Adicionar Colaborador
                    </a>
                    <a href="<?= Url::to(['perfil/index']) ?>" class="btn btn-outline-light font-weight-bold mr-2 mb-2">
                        <i class="fas fa-users mr-1"></i> Ver Todos os Colaboradores
                    </a>
                <?php endif; ?>

                <!-- Botão para abrir o Front-Office -->
                <a href="<?= Html::encode($frontendUrl) ?>" class="btn btn-warning font-weight-bold text-dark mr-2 mb-2 shadow-sm">
                    <i class="fas fa-external-link-alt mr-1"></i> Ir para o Front-Office
                </a>

                <!-- Formulário de Logout via POST -->
                <?= Html::beginForm(['/site/logout'], 'post', ['class' => 'd-inline']) ?>
                <?= Html::submitButton(
                    '<i class="fas fa-sign-out-alt mr-1"></i> Terminar Sessão (' . Html::encode($username) . ')',
                    ['class' => 'btn btn-danger font-weight-bold mb-2 shadow-sm']
                ) ?>
                <?= Html::endForm() ?>
            </div>

            <div class="col-lg-4 text-lg-right mt-3 mt-lg-0 d-none d-lg-block">
                <i class="fas fa-user-shield fa-5x" style="opacity: 0.25;"></i>
            </div>
        </div>
    </div>

    <!-- Cartões Exclusivos do SysAdmin -->
    <?php if (Yii::$app->user->can('gerirUtilizadores')): ?>
        <h5 class="mb-3 text-secondary font-weight-bold">
            <i class="fas fa-users-cog mr-1"></i> Administração de Utilizadores
        </h5>
        <div class="row">
            <div class="col-md-4 col-sm-6 col-12">
                <div class="small-box bg-info shadow-sm">
                    <div class="inner">
                        <h3><?= $totalColaboradores ?></h3>
                        <p>Total de Utilizadores Registados</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <a href="<?= Url::to(['perfil/index']) ?>" class="small-box-footer">
                        Ver listagem <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-md-4 col-sm-6 col-12">
                <div class="small-box bg-success shadow-sm">
                    <div class="inner">
                        <h3><?= $colaboradoresAtivos ?></h3>
                        <p>Contas Ativas</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <a href="<?= Url::to(['perfil/index', 'PerfilSearch[status]' => User::STATUS_ACTIVE]) ?>" class="small-box-footer">
                        Filtrar ativos <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-md-4 col-sm-6 col-12">
                <div class="small-box bg-danger shadow-sm">
                    <div class="inner">
                        <h3><?= $colaboradoresInativos ?></h3>
                        <p>Inativos / Soft Deleted</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-slash"></i>
                    </div>
                    <a href="<?= Url::to(['perfil/index', 'PerfilSearch[status]' => User::STATUS_DELETED]) ?>" class="small-box-footer">
                        Ver desativados <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Cartões Operacionais (Receção e Limpeza) -->
    <h5 class="mb-3 mt-2 text-secondary font-weight-bold">
        <i class="fas fa-briefcase mr-1"></i> Painel Operacional
    </h5>
    <div class="row">
        <?php if (Yii::$app->user->can('verMapaOcupacao')): ?>
            <div class="col-md-6 col-sm-6 col-12">
                <div class="small-box bg-primary shadow-sm">
                    <div class="inner">
                        <h3>Balcão</h3>
                        <p>Mapa de Ocupação e Check-in Manual</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                        Módulo de Receção <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        <?php endif; ?>

        <?php if (Yii::$app->user->can('verSalasEmLimpeza')): ?>
            <div class="col-md-6 col-sm-6 col-12">
                <div class="small-box bg-secondary shadow-sm">
                    <div class="inner">
                        <h3>Manutenção</h3>
                        <p>Listagem de Salas em Limpeza</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-broom"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                        Módulo de Limpeza <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>

</div>