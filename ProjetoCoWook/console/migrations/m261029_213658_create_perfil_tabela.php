<?php

use yii\db\Migration;

class m261029_213658_create_perfil_tabela extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%perfil}}',[

            'id_perfil'=>$this->primaryKey(),

            'nome'=>$this->string(150)->notNull(),

            'nif'=>$this->integer(9),

            'role'=>"ENUM('sys_admin','rececionista','equipa_de_limpeza','cliente') NOT NULL DEFAULT 'cliente'",

            'morada'=>$this->string(250)->notNull()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m261029_213658_create_perfil_tabela cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m261029_213658_create_perfil_tabela cannot be reverted.\n";

        return false;
    }
    */
}
