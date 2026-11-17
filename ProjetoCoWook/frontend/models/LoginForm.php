<?php

namespace frontend\models;

use common\models\User;
use Yii;
use yii\base\Model;
use common\models\Perfil; // <-- ADICIONA ESTA LINHA AQUI!
use yii\mail\MailerInterface;


class LoginForm extends Model
{
    public string $email = "";
    public string $password="";
    public bool $rememberMe=true;

    private $_user = false;


    public function rules():array{
        return [
            //Valdidacoes do username para o login
            ['email','trim'],
            ['email','required','message'=>'O email é obrigatório'],
            ['email','email','message' => 'Introduza um email válido'],

            //validacoes da password para o login
            ['password','required','message'=>'Palavra passe obrigatória'],

            // É ESTA A LINHA QUE FAZ A LIGAÇÃO! Diz ao Yii2 para correr a tua função validatePassword
            ['password','validatePassword'],
        ];
    }

    public function attributeLabels():array
    {
        return[
            'email'=>'Email',
            'password'=>'Palavra passe'

        ];
    }

    public function validatePassword($attribute,$params)
    {
        if(!$this->hasErrors()){
            $user = $this->getUser();

        if(!$user||!$user->validatePassword($this->password)){
            $this->addError($attribute,'Email ou password incorretos');
        }}
    }


    public function getUser()
    {
        if ($this->_user === false) {
            // Em vez de findByUsername, procuramos diretamente pelo campo email e filtramos pelo status ativo (10)
            $this->_user = User::findOne([
                'email' => $this->email,
                'status' => User::STATUS_ACTIVE // Garante que só faz login se estiver ativo (10)
            ]);
        }

        return $this->_user;
    }


    public function login(){
        if($this->validate()){
            return Yii::$app->user->login($this->getUser(),$this->rememberMe ? 60*24*30 :0);
        }
        return false;
    }
}