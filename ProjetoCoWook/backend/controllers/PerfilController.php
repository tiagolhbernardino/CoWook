<?php

// Define o namespace onde se encontra este controlador (aplicação backend)
namespace backend\controllers;

// Importa a classe global Yii para aceder à base de dados, sessão, utilizador e RBAC
use Yii;
// Importa o modelo Perfil (tabela 'perfil')
use common\models\Perfil;
// Importa o modelo User (tabela 'user')
use common\models\User;
// Importa o modelo de pesquisa PerfilSearch para filtrar e ordenar a GridView
use backend\models\PerfilSearch;
// Importa a classe base Controller para controladores web do Yii2
use yii\web\Controller;
// Importa a exceção HTTP 404 caso alguém procure um ID que não existe
use yii\web\NotFoundHttpException;
// Importa o filtro VerbFilter para obrigar que certas ações sejam feitas por POST
use yii\filters\VerbFilter;
// Importa o filtro AccessControl para gerir as permissões RBAC de acesso ao controlador
use yii\filters\AccessControl;

/**
 * PerfilController implementa o CRUD completo de utilizadores/colaboradores
 * com suporte a Soft Delete e atribuição de cargos RBAC.
 */
class PerfilController extends Controller
{
    /**
     * Configura os comportamentos e regras de segurança executados antes de qualquer ação.
     */
    public function behaviors()
    {
        // Junta os comportamentos da classe pai com as regras de segurança deste controlador
        return array_merge(
            parent::behaviors(),
            [
                // Filtro de Controlo de Acessos (RBAC)
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            // Permite o acesso a todas as ações deste controlador APENAS a quem tem
                            // a permissão 'gerirUtilizadores' (atribuída exclusivamente ao 'sys_admin')
                            'allow' => true,
                            'roles' => ['gerirUtilizadores'],
                        ],
                    ],
                    // Função executada automaticamente se alguém sem a permissão 'gerirUtilizadores'
                    // tentar aceder a qualquer rota deste controlador (ex: escrevendo o URL manualmente)
                    'denyCallback' => function ($rule, $action) {
                        // Caso 1: O utilizador é da Receção ou Limpeza (já tem sessão e acesso ao Back-Office)
                        if (!Yii::$app->user->isGuest && Yii::$app->user->can('acederBackoffice')) {
                            // Cria uma mensagem de aviso amarela no topo do ecrã
                            Yii::$app->session->setFlash(
                                'warning',
                                'Acesso restrito: O seu cargo não tem permissão para gerir utilizadores.'
                            );
                            // Redireciona de volta para o Dashboard sem terminar a sessão do colaborador
                            return $this->redirect(['site/index']);
                        }

                        // Caso 2: É um visitante não autenticado ou um Cliente sem acesso ao Back-Office
                        Yii::$app->user->logout();
                        Yii::$app->session->setFlash('error', 'Por favor, inicie sessão com uma conta de Administrador.');
                        return $this->redirect(['site/login']);
                    },
                ],
                // Filtro de Verbos HTTP: protege as ações de desativar e reativar contra cliques acidentais por GET
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],  // A ação actionDelete só aceita pedidos via método POST
                        'restore' => ['POST'], // A ação actionRestore só aceita pedidos via método POST
                    ],
                ],
            ]
        );
    }

    /**
     * Lista todos os perfis registados numa tabela (GridView) com pesquisa e filtros.
     */
    public function actionIndex()
    {
        // Instancia o modelo de pesquisa que faz o JOIN entre 'perfil', 'user' e 'auth_assignment'
        $searchModel = new PerfilSearch();

        // Executa a pesquisa passando os parâmetros GET do URL (filtros da tabela e paginação)
        $dataProvider = $searchModel->search($this->request->queryParams);

        // Renderiza a vista 'backend/views/perfil/index.php' enviando os dados pesquisados
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Mostra a ficha detalhada de um único colaborador.
     *
     * @param int $id_perfil O ID do perfil a consultar
     */
    public function actionView($id_perfil)
    {
        // Procura o perfil pelo ID e envia-o para a vista 'backend/views/perfil/view.php'
        return $this->render('view', [
            'model' => $this->findModel($id_perfil),
        ]);
    }

    /**
     * Cria em simultâneo um registo na tabela 'user', um na tabela 'perfil' e atribui a Role RBAC.
     */
    public function actionCreate()
    {
        // Cria uma nova instância vazia do modelo Perfil
        $modelPerfil = new Perfil();
        // Define 'rececionista' como cargo pré-selecionado por defeito no dropdown do formulário
        $modelPerfil->role = 'rececionista';

        // Cria uma nova instância do modelo User ativando o cenário 'create' (que torna a password obrigatória)
        $modelUser = new User(['scenario' => 'create']);
        // Define o estado inicial da conta como Ativo (10) por defeito
        $modelUser->status = User::STATUS_ACTIVE;

        // Verifica se o formulário foi submetido via método HTTP POST
        if ($this->request->isPost) {
            // Guarda todos os dados enviados pelo formulário na variável $post
            $post = $this->request->post();

            // Carrega os dados do POST para dentro dos dois modelos ($modelUser e $modelPerfil)
            if ($modelUser->load($post) && $modelPerfil->load($post)) {
                // Valida ambos os modelos em simultâneo (usa '&' simples para garantir que ambos mostram erros se falharem)
                $isValid = $modelUser->validate() & $modelPerfil->validate();

                // Se os dados dos dois modelos cumprirem todas as regras (rules)
                if ($isValid) {
                    // Inicia uma transação SQL para garantir que não são criados utilizadores sem perfil (ou vice-versa)
                    $transaction = Yii::$app->db->beginTransaction();
                    try {
                        // Converte a password em texto limpo para uma hash encriptada segura (bcrypt)
                        $modelUser->setPassword($modelUser->password);
                        // Gera a chave de autenticação aleatória exigida pelo Yii2 para cookies/sessões
                        $modelUser->generateAuthKey();
                        // Gera um token de verificação de email
                        $modelUser->generateEmailVerificationToken();

                        // Passo 1: Guarda o utilizador na tabela 'user' (passa 'false' porque já validámos acima)
                        if ($modelUser->save(false)) {
                            // Copia o ID auto-incrementado acabado de criar em 'user' para a chave primária/estrangeira 'id_perfil'
                            $modelPerfil->id_perfil = $modelUser->id;

                            // Passo 2: Guarda os dados pessoais na tabela 'perfil'
                            if ($modelPerfil->save(false)) {
                                // Passo 3: Obtém o gestor RBAC e atribui a Role escolhida no formulário
                                $auth = Yii::$app->authManager;
                                // Procura o objeto da Role escolhida (ex: 'sys_admin', 'rececionista', 'equipa_de_limpeza')
                                $roleObj = $auth->getRole($modelPerfil->role ?: 'rececionista');
                                // Se a Role existir na base de dados, grava a associação na tabela 'auth_assignment'
                                if ($roleObj !== null) {
                                    $auth->assign($roleObj, $modelUser->id);
                                }

                                // Confirma e grava definitivamente todas as operações na base de dados
                                $transaction->commit();
                                // Mostra mensagem verde de sucesso no ecrã seguinte
                                Yii::$app->session->setFlash('success', 'Colaborador criado com sucesso!');
                                // Redireciona para a ficha de detalhes do colaborador acabado de criar
                                return $this->redirect(['view', 'id_perfil' => $modelPerfil->id_perfil]);
                            }
                        }
                        // Se alguma das gravações falhar, desfaz todas as alterações na base de dados
                        $transaction->rollBack();
                    } catch (\Exception $e) {
                        // Em caso de exceção SQL inesperada, anula a transação e mostra o erro ao administrador
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('error', 'Erro ao guardar colaborador: ' . $e->getMessage());
                    }
                }
            }
        }

        // Se for o primeiro acesso à página (GET) ou se houver erros de validação, mostra o formulário 'create.php'
        return $this->render('create', [
            'modelPerfil' => $modelPerfil,
            'modelUser' => $modelUser,
        ]);
    }

    /**
     * Atualiza os dados de um colaborador existente ('user', 'perfil' e Role RBAC).
     *
     * @param int $id_perfil O ID do perfil a editar
     */
    public function actionUpdate($id_perfil)
    {
        // Procura o registo na tabela 'perfil' pelo ID (ou lança erro 404 se não existir)
        $modelPerfil = $this->findModel($id_perfil);
        // Obtém o registo associado na tabela 'user' através da relação getUser()
        $modelUser = $modelPerfil->user;
        // Preenche o atributo virtual 'role' com o cargo RBAC atual do utilizador para aparecer selecionado no dropdown
        $modelPerfil->role = $modelPerfil->getRoleName();

        // Verifica se o formulário de edição foi submetido por POST
        if ($this->request->isPost) {
            // Obtém os dados enviados pelo formulário
            $post = $this->request->post();

            // Carrega os novos valores para dentro dos modelos User e Perfil
            if ($modelUser->load($post) && $modelPerfil->load($post)) {
                // Valida ambos os modelos em simultâneo
                $isValid = $modelUser->validate() & $modelPerfil->validate();

                // Se todas as validações passarem com sucesso
                if ($isValid) {
                    // Inicia uma transação na base de dados
                    $transaction = Yii::$app->db->beginTransaction();
                    try {
                        // Na edição, a password é opcional: só geramos nova hash se o administrador escreveu uma nova password
                        if (!empty($modelUser->password)) {
                            $modelUser->setPassword($modelUser->password);
                        }

                        // Guarda as alterações na tabela 'user' e na tabela 'perfil'
                        if ($modelUser->save(false) && $modelPerfil->save(false)) {
                            // Atualiza o cargo RBAC: primeiro remove os cargos antigos deste utilizador
                            $auth = Yii::$app->authManager;
                            $auth->revokeAll($modelUser->id);

                            // Obtém a nova Role selecionada no dropdown e atribui-a ao utilizador
                            $roleObj = $auth->getRole($modelPerfil->role ?: 'rececionista');
                            if ($roleObj !== null) {
                                $auth->assign($roleObj, $modelUser->id);
                            }

                            // Confirma as alterações na base de dados
                            $transaction->commit();
                            // Define mensagem verde de confirmação
                            Yii::$app->session->setFlash('success', 'Dados do colaborador atualizados com sucesso!');
                            // Redireciona para a página de visualização do perfil atualizado
                            return $this->redirect(['view', 'id_perfil' => $modelPerfil->id_perfil]);
                        }
                        // Se falhar o save(), desfaz a transação
                        $transaction->rollBack();
                    } catch (\Exception $e) {
                        // Se ocorrer um erro de base de dados, desfaz a transação e exibe o erro
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('error', 'Erro ao atualizar colaborador: ' . $e->getMessage());
                    }
                }
            }
        }

        // Renderiza a vista 'update.php' passando os modelos carregados com os dados atuais
        return $this->render('update', [
            'modelPerfil' => $modelPerfil,
            'modelUser' => $modelUser,
        ]);
    }

    /**
     * Aplica o Soft Delete ao colaborador: altera o status na tabela 'user' para 0 (STATUS_DELETED)
     * sem apagar fisicamente os registos das tabelas 'perfil' ou 'user'.
     *
     * @param int $id_perfil O ID do perfil a desativar
     */
    public function actionDelete($id_perfil)
    {
        // Localiza o perfil do colaborador
        $modelPerfil = $this->findModel($id_perfil);
        // Obtém a conta de utilizador associada
        $modelUser = $modelPerfil->user;

        // Se a conta de utilizador existir, altera o estado para STATUS_DELETED (0)
        if ($modelUser !== null) {
            $modelUser->status = User::STATUS_DELETED;
            // Guarda a alteração sem correr validações de formulário (false)
            $modelUser->save(false);
            // Mostra aviso amarelo a confirmar que a conta foi desativada por Soft Delete
            Yii::$app->session->setFlash('warning', 'O colaborador "' . $modelPerfil->nome . '" foi desativado (Soft Delete).');
        }

        // Redireciona de volta para a listagem de colaboradores
        return $this->redirect(['index']);
    }

    /**
     * Reativa um colaborador que tinha sido desativado por Soft Delete,
     * repondo o status na tabela 'user' para 10 (STATUS_ACTIVE).
     *
     * @param int $id_perfil O ID do perfil a reativar
     */
    public function actionRestore($id_perfil)
    {
        // Localiza o perfil do colaborador
        $modelPerfil = $this->findModel($id_perfil);
        // Obtém a conta de utilizador associada
        $modelUser = $modelPerfil->user;

        // Se a conta existir, repõe o estado para STATUS_ACTIVE (10)
        if ($modelUser !== null) {
            $modelUser->status = User::STATUS_ACTIVE;
            // Guarda na base de dados
            $modelUser->save(false);
            // Mostra mensagem verde a confirmar a reativação da conta
            Yii::$app->session->setFlash('success', 'O colaborador "' . $modelPerfil->nome . '" foi reativado com sucesso!');
        }

        // Redireciona de volta para a listagem de colaboradores
        return $this->redirect(['index']);
    }

    /**
     * Método auxiliar protegido que procura um modelo Perfil pela sua chave primária (id_perfil).
     * Se o registo não existir na base de dados, lança automaticamente um erro HTTP 404.
     *
     * @param int $id_perfil O ID do perfil a procurar
     * @return Perfil O modelo carregado
     * @throws NotFoundHttpException Se o perfil não for encontrado
     */
    protected function findModel($id_perfil)
    {
        // Procura na tabela 'perfil' onde a coluna 'id_perfil' seja igual ao valor recebido
        if (($model = Perfil::findOne(['id_perfil' => $id_perfil])) !== null) {
            return $model;
        }

        // Caso não encontre, interrompe a execução com erro 404
        throw new NotFoundHttpException('O colaborador solicitado não existe.');
    }
}