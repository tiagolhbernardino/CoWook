<?php

namespace common\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm é o modelo partilhado responsável por validar e processar os dados do formulário de login
 * (utilizado tanto no Frontend como no Backend).
 */
class LoginForm extends Model
{
    // Atributos públicos do formulário de login (substituímos o 'username' por 'email')
    public string $email = '';
    public string $password = '';
    public bool $rememberMe = true;

    // Variável privada para guardar a instância do utilizador em cache durante a execução
    private $_user = false;

    /**
     * {@inheritdoc}
     * Define as regras de validação para os campos do formulário de login.
     */
    public function rules(): array
    {
        return [
            // Validações para o campo 'email'
            ['email', 'trim'],
            ['email', 'required', 'message' => 'O email é obrigatório.'],
            ['email', 'email', 'message' => 'Introduza um email válido.'],

            // Validações para o campo 'password'
            ['password', 'required', 'message' => 'A palavra-passe é obrigatória.'],

            // Validação personalizada que chama a função validatePassword abaixo
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Valida a palavra-passe inserida pelo utilizador.
     * Esta função é acionada automaticamente pelas regras definidas em rules().
     *
     * @param string $attribute O atributo a ser validado ('password')
     * @param array $params Parâmetros adicionais da regra
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            // Se o utilizador não existir ou a password estiver incorreta, adiciona uma mensagem de erro
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Email ou palavra-passe incorretos.');
            }
        }
    }

    /**
     * Efetua o login do utilizador se a validação passar com sucesso.
     *
     * @return bool True se o login for bem-sucedido, False caso contrário
     */
    public function login(): bool
    {
        if ($this->validate()) {
            // Regista a sessão do utilizador no Yii2 (mantém logado por 30 dias se 'rememberMe' for verdadeiro)
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }

        return false;
    }

    /**
     * Encontra e devolve o utilizador com base no email introduzido.
     * Utiliza cache interna ($this->_user) para evitar consultas repetidas à base de dados.
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === false) {
            // Procura na tabela de utilizadores pelo email correspondente e estado ativo (status 10)
            $this->_user = User::findOne([
                'email' => $this->email,
                'status' => User::STATUS_ACTIVE,
            ]);
        }

        return $this->_user;
    }
}