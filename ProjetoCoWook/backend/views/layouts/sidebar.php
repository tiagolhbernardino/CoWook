<?php

// Importa a classe Html do Yii2 para codificar texto de forma segura (prevenção de XSS)
use yii\helpers\Html;
// Importa a classe Url do Yii2 para gerar links dinâmicos para os controladores/ações
use yii\helpers\Url;

// Obtém a instância do modelo User correspondente ao utilizador autenticado na sessão atual
$user = Yii::$app->user->identity;

// Tenta obter o nome completo na tabela 'perfil'; se não existir, usa o 'username' da tabela 'user'; se for nulo, usa 'Colaborador'
$nomeExibicao = $user?->perfil?->nome ?? $user?->username ?? 'Colaborador';

// Chama o método getRoleName() do modelo Perfil para descobrir qual é a Role RBAC atribuída a este utilizador na BD
$role =$user?->perfil?->getRoleName() ?? '';

// Converte o nome técnico da role (ex: 'sys_admin') num título legível para mostrar debaixo do nome na barra lateral
$cargoLabel = match ($role) {
    'sys_admin' => 'SysAdmin',                 // Se for sys_admin, exibe 'SysAdmin'
    'rececionista' => 'Rececionista',          // Se for rececionista, exibe 'Rececionista'
    'equipa_de_limpeza' => 'Equipa de Limpeza',// Se for equipa_de_limpeza, exibe 'Equipa de Limpeza'
    default => 'Online',                       // Caso contrário, exibe 'Online'
};
?>

<!-- Início do contentor principal da barra lateral esquerda do template AdminLTE 3 -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <!-- Secção do Logótipo no topo da barra lateral (clicar leva ao Dashboard 'site/index') -->
    <a href="<?= Url::to(['site/index']) ?>" class="brand-link">
        <!-- Imagem padrão do logótipo carregada a partir dos ficheiros de recursos ($assetDir) do AdminLTE -->
        <img src="<?= $assetDir ?>/img/AdminLTELogo.png" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <!-- Nome do projeto exibido ao lado do logótipo -->
        <span class="brand-text font-weight-bold">CoWook Admin</span>
    </a>

    <!-- Contentor interno da barra lateral -->
    <div class="sidebar">

        <!-- Painel com a fotografia, nome e cargo do utilizador que tem sessão iniciada -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center">
            <!-- Contentor da fotografia de perfil (avatar) -->
            <div class="image">
                <img src="<?= $assetDir ?>/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <!-- Contentor do nome e do cargo RBAC do utilizador -->
            <div class="info">
                <!-- Imprime o nome do utilizador de forma segura com Html::encode() -->
                <a href="<?= Url::to(['site/index']) ?>" class="d-block font-weight-bold">
                    <?= Html::encode($nomeExibicao) ?>
                </a>
                <!-- Mostra um ícone verde e o nome do cargo RBAC atual -->
                <small class="text-success">
                    <i class="fas fa-circle fa-xs mr-1"></i> <?= Html::encode($cargoLabel) ?>
                </small>
            </div>
        </div>

        <!-- Menu de navegação lateral gerido pelo widget oficial do AdminLTE 3 -->
        <nav class="mt-2">
            <?= \hail812\adminlte\widgets\Menu::widget([
                // Lista de todos os itens do menu lateral
                'items' => [
                    // Item 1: Dashboard (Visível para todos os que têm acesso ao Back-Office)
                    [
                        'label' => 'Dashboard',           // Texto que aparece no menu
                        'icon' => 'tachometer-alt',       // Ícone FontAwesome
                        'url' => ['site/index'],          // Rota para a ação actionIndex do SiteController
                    ],

                    // =========================================================================
                    // SECÇÃO 1: ADMINISTRAÇÃO GLOBAL (Visível APENAS para o 'sys_admin')
                    // =========================================================================
                    [
                        'label' => 'ADMINISTRAÇÃO GLOBAL', // Título separador da secção
                        'header' => true,                  // Define que este item é apenas um cabeçalho de texto
                        // Só desenha este cabeçalho se o utilizador tiver a permissão 'gerirUtilizadores'
                        'visible' => Yii::$app->user->can('gerirUtilizadores'),
                    ],
                    [
                        'label' => 'Adicionar Colaborador', // Texto do botão no menu
                        'icon' => 'user-plus',              // Ícone de adicionar utilizador
                        'url' => ['perfil/create'],         // Rota para actionCreate do PerfilController
                        // Esconde o botão à Receção e à Limpeza; só o SysAdmin o vê
                        'visible' => Yii::$app->user->can('gerirUtilizadores'),
                    ],
                    [
                        'label' => 'Lista de Colaboradores', // Texto do botão no menu
                        'icon' => 'users',                   // Ícone de grupo de utilizadores
                        'url' => ['perfil/index'],           // Rota para actionIndex do PerfilController
                        // Esconde o botão à Receção e à Limpeza; só o SysAdmin o vê
                        'visible' => Yii::$app->user->can('gerirUtilizadores'),
                    ],

                    // =========================================================================
                    // SECÇÃO 2: BALCÃO / RECEÇÃO (Visível para 'rececionista' e 'sys_admin')
                    // =========================================================================
                    [
                        'label' => 'BALCÃO / RECEÇÃO',       // Título separador da secção
                        'header' => true,                    // Define como cabeçalho
                        // Visível para quem tem a permissão 'verMapaOcupacao' (Rececionista e SysAdmin por herança)
                        'visible' => Yii::$app->user->can('verMapaOcupacao'),
                    ],
                    [
                        'label' => 'Mapa de Ocupação',       // Texto do menu
                        'icon' => 'calendar-alt',            // Ícone de calendário
                        'url' => ['#'],                      // Link de placeholder (sem funcionalidade nesta entrega)
                        // Escondido para a Equipa de Limpeza; visível para Rececionista e SysAdmin
                        'visible' => Yii::$app->user->can('verMapaOcupacao'),
                    ],
                    [
                        'label' => 'Reservas Presenciais',   // Texto do menu
                        'icon' => 'clipboard-check',         // Ícone de bloco de notas
                        'url' => ['#'],                      // Link de placeholder
                        // Escondido para a Equipa de Limpeza; visível para Rececionista e SysAdmin
                        'visible' => Yii::$app->user->can('registarReservasManuais'),
                    ],

                    // =========================================================================
                    // SECÇÃO 3: LIMPEZA (Visível para 'equipa_de_limpeza', 'rececionista' e 'sys_admin')
                    // =========================================================================
                    [
                        'label' => 'MANUTENÇÃO / LIMPEZA',   // Título separador da secção
                        'header' => true,                    // Define como cabeçalho
                        // Visível para todos os que têm a permissão 'verSalasEmLimpeza'
                        'visible' => Yii::$app->user->can('verSalasEmLimpeza'),
                    ],
                    [
                        'label' => 'Salas em Limpeza',       // Texto do menu
                        'icon' => 'broom',                   // Ícone de vassoura
                        'url' => ['#'],                      // Link de placeholder
                        // Visível para Limpeza, Receção e SysAdmin
                        'visible' => Yii::$app->user->can('verSalasEmLimpeza'),
                    ],
                ],
            ]) ?>
        </nav>
        <!-- Fim do menu lateral -->
    </div>
    <!-- Fim do contentor interno da barra lateral -->
</aside>