<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class Perfil extends ActiveRecord
{
    /**
     * Atributo virtual para receber o cargo RBAC escolhido no formulário.
     */
    public $role = 'rececionista';

    /**
     * Define o nome exato da tabela no MySQL.
     * O prefixo {{%}} usa o prefixo configurado no Yii2 (ex: tbl_).
     */
    public static function tableName()
    {
        return '{{%perfil}}';
    }

    public function rules()
    {
        return [
            // O id_perfil deixa de ser obrigatório no formulário (é preenchido via código)
            [['nome', 'morada'], 'required'],

            // O id_perfil tem de ser um número inteiro
            [['id_perfil'], 'integer'],

            // O nome pode ter no máximo 150 caracteres
            [['nome'], 'string', 'max' => 150],

            // A morada pode ter no máximo 255 caracteres
            [['morada'], 'string', 'max' => 255],

            // O NIF é opcional
            [['nif'], 'string', 'max' => 9],
            ['nif', 'default', 'value' => null],

            // Regra para permitir receber o cargo (Role RBAC) no formulário
            ['role', 'default', 'value' => 'cliente'],
            ['role', 'in', 'range' => ['sys_admin', 'rececionista', 'equipa_de_limpeza', 'cliente']],

            // Garante que o id_perfil é único na tabela
            [['id_perfil'], 'unique'],

            // Chave estrangeira lógica: garante que o id_perfil existe na tabela 'user'
            [['id_perfil'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['id_perfil' => 'id']],
        ];
    }

    /**
     * Nomes amigáveis para os campos (labels).
     */
    public function attributeLabels()
    {
        return [
            'id_perfil' => 'ID Perfil',
            'nome' => 'Nome Completo',
            'nif' => 'Número de Contribuinte (Opcional)',
            'morada' => 'Morada',
            'role' => 'Cargo / Perfil (RBAC)',
        ];
    }

    /**
     * Devolve o nome da Role RBAC atual deste utilizador (para preencher o dropdown no Update).
     */
    public function getRoleName(): string
    {
        if ($this->id_perfil) {
            $roles = Yii::$app->authManager->getRolesByUser($this->id_perfil);
            if (!empty($roles)) {
                return (string) array_key_first($roles);
            }
        }

        return $this->role ?: 'rececionista';
    }

    /**
     * Relação ActiveRecord: Um Perfil pertence a um Utilizador (User).
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'id_perfil']);
    }
}