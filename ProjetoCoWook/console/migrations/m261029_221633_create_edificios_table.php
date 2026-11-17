<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%edificios}}`.
 */
class m261029_221633_create_edificios_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%edificios}}', [
            'id_edificio' => $this->primaryKey(),

            'nome'=>$this->string(50)->notNull(),

            'morada'=>$this->string(150)->notNull(),

            'cidade'=>$this->string(50)->notNull(),

            'ativo'=>$this->tinyInteger()->notNull()->defaultValue(1)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%edificios}}');
    }
}
