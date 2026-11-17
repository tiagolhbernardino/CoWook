<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%favoritos}}`.
 */
class m261029_224303_create_favoritos_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%favoritos}}', [
            'id_perfil' => $this->integer(),

            'id_espaco'=>$this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%favoritos}}');
    }
}
