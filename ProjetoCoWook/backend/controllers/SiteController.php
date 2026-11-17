<?php

declare(strict_types=1);

namespace backend\controllers;

use common\models\LoginForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\ErrorAction;
use yii\web\Response;

/**
 * SiteController do Backend.
 * Gere as rotas principais do painel de administração, incluindo o índice, login, logout e erros.
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     * Define os comportamentos e filtros de segurança aplicados às ações do controlador.
     */
    public function behaviors(): array
    {
        return [
            // Configuração do controlo de acessos (Access Control Filter)
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        // Ações permitidas a qualquer visitante (não autenticado)
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        // Ações estritamente restritas a utilizadores com sessão iniciada (representado por '@')
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            // Controlo dos verbos HTTP permitidos para aceder a determinadas ações
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    // Garante que o pedido de logout é feito obrigatoriamente por POST (proteção contra CSRF)
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     * Mapeia ações externas geridas por classes do Yii2 (como o gestor de erros).
     */
    public function actions(): array
    {
        return [
            'error' => [
                'class' => ErrorAction::class,
            ],
        ];
    }

    /**
     * Exibe a página principal (dashboard) do painel de administração do backend.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        return $this->render('index');
    }

    /**
     * Processa a autenticação (login) dos utilizadores administradores/staff no backend.
     *
     * @return string|Response
     */
    public function actionLogin(): string|Response
    {
        // Força o uso do layout 'blank' para esconder menus, barras laterais e cabeçalhos,
        // apresentando apenas o formulário de login limpo e centralizado.
        $this->layout = 'blank';

        // Se o utilizador já tiver a sessão iniciada, redireciona-o imediatamente para a página inicial
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        // Instancia o modelo de formulário de login
        $model = new LoginForm();

        // Submete os dados recolhidos por POST para o modelo e tenta efetuar o login
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        // Limpa o campo da palavra-passe por motivos de segurança caso o login falhe
        $model->password = '';

        // Renderiza a vista de login ('login.php') passando o modelo correspondente
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Termina a sessão ativa do utilizador atual no backend.
     *
     * @return Response
     */
    public function actionLogout(): Response
    {
        // Executa a função nativa do Yii2 para terminar a sessão do utilizador
        Yii::$app->user->logout();

        // Redireciona o utilizador de volta para a página inicial (ou de login)
        return $this->goHome();
    }
}