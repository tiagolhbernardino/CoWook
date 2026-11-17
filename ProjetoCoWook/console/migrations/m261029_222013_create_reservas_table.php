<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%reservas}}`.
 */
class m261029_222013_create_reservas_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%reservas}}', [
            'id_reserva' => $this->primaryKey(),

            'id_perfil'=>$this->integer()->notNull(),

            'id_espaco'=>$this->integer()->notNull(),

            'data_reserva'=>$this->date()->notNull(),

            'hora_inicio'=>$this->time()->notNull(),

            'hora_fim'=>$this->time()->notNull(),

            'estado_reserva'=>"ENUM('disponivel','reservado') NOT NULL DEFAULT 'disponivel'",

            'qr_code'=>$this->string(250)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%reservas}}');
    }
}
