<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%pagamentos}}`.
 */
class m261029_222916_create_pagamentos_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%pagamentos}}', [
            'id_pagamento' => $this->primaryKey(),

            'id_reserva'=> $this->integer()->notNull(),

            'metodo'=>"ENUM('mbway','transferenci_bancaria','cartao')NOT NULL DEFAULT 'mbway'",

            'valor_total'=>$this->decimal(6,2)->unsigned()->notNull(),

            'estado_pagamento'=>"ENUM('pendente','efetuado')NOT NULL DEFAULT 'pendente'"
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%pagamentos}}');
    }
}
