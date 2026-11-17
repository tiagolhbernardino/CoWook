<?php

declare(strict_types=1);

namespace frontend\models;

use common\models\User;
use Yii;
use yii\base\Model;
use common\models\Perfil; // <-- ADICIONA ESTA LINHA AQUI!
use yii\mail\MailerInterface;

/**
 * Signup form
 */
class SignupForm extends Model
{
    // --- CAMPOS DA TABELA 'user' ---
    public string $username = '';
    public string $email = '';
    public string $password = '';
    public string $password_repeat='';

    // --- CAMPOS DA TABELA 'perfil' ---
    public string $nome='';
    public string $morada='';// <-- Obrigatória de acordo com a base de dados
    public ?string $nif = null;// <-- Opcional (pode vir a nulo)

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            // --- 1. VALIDAÇÕES DO USERNAME (Nome de Utilizador) ---
            ['username', 'trim'], // Retira espaços em branco desnecessários no início e no fim do texto.
            ['username', 'required', 'message' => 'O nome de utilizador é obrigatório.'], // Obriga a preencher o campo.
            ['username', 'unique', 'targetClass' => User::class, 'message' => 'Este nome de utilizador já foi tomado.'], // Verifica na BD se o username já existe para evitar duplicados.
            ['username', 'string', 'min' => 2, 'max' => 255], // Define que o nome tem de ter entre 2 e 255 caracteres.

            // --- 2. VALIDAÇÕES DO EMAIL ---
            ['email', 'trim'], // Limpa espaços em branco nas pontas do email.
            ['email', 'required', 'message' => 'O email é obrigatório.'], // Obriga a preencher o email.
            ['email', 'email', 'message' => 'Introduza um email válido.'], // Valida se o texto introduzido tem formato real de email (contém '@', domínios, etc.).
            ['email', 'string', 'max' => 255], // Limita o email a um máximo de 255 caracteres.
            ['email', 'unique', 'targetClass' => User::class, 'message' => 'Este endereço de email já está registado.'], // Garante que o email não está associado a outra conta na BD.

            // --- 3. VALIDAÇÕES DA PALAVRA-PASSE ---
            ['password', 'required', 'message' => 'A palavra-passe é obrigatória.'], // Obriga a preencher a password.
            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']], // Respeita o tamanho mínimo de password configurado globalmente no Yii2.

            // --- 4. VALIDAÇÃO DE CONFIRMAÇÃO DA PALAVRA-PASSE ---
            ['password_repeat', 'required', 'message' => 'Por favor, reintroduza a palavra-passe.'], // Obriga a preencher a caixa de repetição da password.
            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'message' => 'As palavras-passe não coincidem.'], // Compara o valor deste campo com o campo 'password' para garantir que são idênticos.

            // --- 5. VALIDAÇÕES DO NOME COMPLETO (Campo do Perfil) ---
            ['nome', 'trim'], // Remove espaços extras.
            ['nome', 'required', 'message' => 'O nome completo é obrigatório.'], // Obriga a preencher o nome.
            ['nome', 'string', 'max' => 150], // Limita o nome a um máximo de 150 caracteres (compatível com a base de dados).

            // --- 6. VALIDAÇÕES DA MORADA (Campo Obrigatório do Perfil) ---
            ['morada', 'trim'], // Remove espaços extras nas pontas.
            ['morada', 'required', 'message' => 'A morada é obrigatória.'], // Torna a morada estritamente obrigatória para avançar.
            ['morada', 'string', 'max' => 255], // Limita a morada a um máximo de 255 caracteres.

            // --- 7. VALIDAÇÕES DO NIF (Campo Opcional do Perfil) ---
            ['nif', 'trim'], // Remove espaços em branco caso o utilizador os deixe sem querer.
            ['nif', 'default', 'value' => null], // Se o campo for deixado totalmente vazio, assume automaticamente o valor null na BD.
            ['nif', 'match', 'pattern' => '/^[0-9]{9}$/', 'message' => 'O Número de Contribuinte deve ter exatamente 9 números.'], // Se preenchido, valida através de Expressão Regular (Regex) se são exatamente 9 carateres numéricos de 0 a 9.
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'nome' => 'Nome Completo',
            'username' => 'Nome Utilizador',
            'email' => 'Email',
            'password' => 'Palavra-passe',
            'password_repeat' => 'Reintroduza a palavra passe',
            'morada' => 'Morada',
            'nif' => 'Numero de Contribuinte',
        ];
    }

    /**
     * Signs user up.
     *
     * @param MailerInterface $mailer the mailer component.
     * @param string $supportEmail the support email address.
     * @param string $appName the application name.
     *
     * @return bool|null whether the creating new account was successful and email was sent.
     */
    public function signup():bool
    {
        // 1. Valida os campos do formulário
        if (!$this->validate()) {
            return false;
        }

        // 2. Inicia uma transação na base de dados (ou grava tudo, ou anula se houver erro)
        $transaction = Yii::$app->db->beginTransaction();

        try {
            // --- PASSO A: Gravar na tabela 'user' ---
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->setPassword($this->password); // Encripta a password de forma segura
            $user->generateAuthKey();
            $user->status = User::STATUS_ACTIVE; // Ativa logo o utilizador

            if (!$user->save()) {
                $transaction->rollBack();
                return false;
            }

            // --- PASSO B: Gravar na tabela 'perfil' ---
            $perfil = new Perfil();
            $perfil->id_perfil = $user->id; // Associa ao ID gerado na tabela user
            $perfil->nome = $this->nome;
            $perfil->morada = $this->morada;
            $perfil->nif = $this->nif;

            if (!$perfil->save()) {
                $transaction->rollBack();
                return false;
            }

            // --- PASSO C: Atribuir a role 'cliente' via RBAC ---
            $auth = Yii::$app->authManager;
            $clienteRole = $auth->getRole('cliente');
            if ($clienteRole !== null) {
                $auth->assign($clienteRole, $user->getId());
            }

            // 3. Confirma todas as operações na base de dados com sucesso
            $transaction->commit();
            return true;

        } catch (\Exception $e) {
            // Se falhar alguma coisa, desfaz as alterações pendentes
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * Sends confirmation email to user.
     *
     * @param MailerInterface $mailer the mailer component.
     * @param User $user user model to which the email should be sent.
     * @param string $supportEmail the support email address.
     * @param string $appName the application name.
     *
     * @return bool whether the email was sent.
     */
    protected function sendEmail(MailerInterface $mailer, User $user, string $supportEmail, string $appName): bool
    {
        return $mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user],
            )
            ->setFrom([$supportEmail => $appName . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account registration at ' . $appName)
            ->send();
    }
}
