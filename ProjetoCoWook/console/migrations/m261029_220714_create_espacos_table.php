<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%espacos}}`.
 */
class m261029_220714_create_espacos_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%espacos}}', [
            'id_espaco' => $this->primaryKey(),

            'id_edificio'=>$this->integer()->notNull(),

            'tipo_de_espaco'=>"ENUM('gabinete','sala_reunioes','secretaria','sala_de_apresentacao') NOT NULL DEFAULT 'gabinete'",

            'capacidade'=>$this->integer()->notNull(),

            'preco_hora'=>$this->decimal(6,2)->notNull(),

            'estado_limpeza'=>"ENUM('disponivel','em_manutencao') NOT NULL DEFAULT 'disponivel'"


        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%espacos}}');
    }
}
