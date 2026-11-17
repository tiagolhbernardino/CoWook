<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Perfil;

class PerfilSearch extends Perfil
{
    public $username;
    public $email;
    public $status;

    public function rules()
    {
        return [
            [['id_perfil', 'status'], 'integer'],
            [['nome', 'nif', 'morada', 'username', 'email'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Perfil::find()->joinWith(['user']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 10],
            'sort' => [
                'defaultOrder' => ['id_perfil' => SORT_DESC],
                'attributes' => [
                    'id_perfil',
                    'nome',
                    'nif',
                    'morada',
                    'username' => [
                        'asc' => ['{{%user}}.username' => SORT_ASC],
                        'desc' => ['{{%user}}.username' => SORT_DESC],
                    ],
                    'email' => [
                        'asc' => ['{{%user}}.email' => SORT_ASC],
                        'desc' => ['{{%user}}.email' => SORT_DESC],
                    ],
                    'status' => [
                        'asc' => ['{{%user}}.status' => SORT_ASC],
                        'desc' => ['{{%user}}.status' => SORT_DESC],
                    ],
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id_perfil' => $this->id_perfil,
            '{{%user}}.status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'nome', $this->nome])
            ->andFilterWhere(['like', 'nif', $this->nif])
            ->andFilterWhere(['like', 'morada', $this->morada])
            ->andFilterWhere(['like', '{{%user}}.username', $this->username])
            ->andFilterWhere(['like', '{{%user}}.email', $this->email]);

        return $dataProvider;
    }
}