<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%avaliacoes}}`.
 */
class m261029_223633_create_avaliacoes_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%avaliacoes}}', [
            'id_avaliacao' => $this->primaryKey(),

            'id_reserva'=>$this->integer()->notNull(),

            'classificacao'=>$this->decimal(2,1)->unsigned()->notNull(),

            'comentario'=>$this->text()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%avaliacoes}}');
    }
}
