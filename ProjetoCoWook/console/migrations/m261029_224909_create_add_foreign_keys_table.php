<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%add_foreign_keys}}`.
 */
class m261029_224909_create_add_foreign_keys_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    // Função executada quando corres 'php yii migrate' para ligar todas as tabelas entre si
    public function safeUp()
    {
        // 1. Liga a tabela 'perfil' à tabela 'user' nativa do Yii2 (perfil.id_utilizador -> user.id)
        $this->addForeignKey(
            'fk-perfil-id_utilizador', // 1º Nome único identificativo desta ligação
            '{{%perfil}}',             // 2º Tabela filha (de onde sai a chave estrangeira)
            'id_perfil',           // 3º Nome da coluna na tabela filha ('perfil')
            '{{%user}}',               // 4º Tabela mãe (onde está a chave primária original)
            'id',                      // 5º Nome da coluna chave primária na tabela mãe ('user')
            'CASCADE',                 // 6º ON DELETE: se o user for apagado, apaga o perfil automaticamente
            'CASCADE'                  // 7º ON UPDATE: se o ID do user mudar, atualiza no perfil automaticamente
        );

        // 2. Linha Azul do diagrama: Liga 'espacos' a 'edificios' (espacos.id_edificio -> edificios.id_edificios)
        $this->addForeignKey(
            'fk-espacos-id_edificio',  // Nome único da ligação
            '{{%espacos}}',            // Tabela filha: 'espacos'
            'id_edificio',             // Coluna na tabela 'espacos'
            '{{%edificios}}',          // Tabela mãe: 'edificios'
            'id_edificio',            // Chave primária na tabela 'edificios'
            'CASCADE',                 // Se o edifício for apagado, apaga os espaços desse edifício
            'CASCADE'                  // Se o ID mudar, atualiza automaticamente
        );

        // 3. Linha Amarela (Cima): Liga 'reservas' a 'perfil' (reservas.id_utilizador -> perfil.id_utilizador)
        $this->addForeignKey(
            'fk-reservas-id_utilizador', // Nome único da ligação
            '{{%reservas}}',             // Tabela filha: 'reservas'
            'id_perfil',             // Coluna na tabela 'reservas'
            '{{%perfil}}',               // Tabela mãe: 'perfil'
            'id_perfil',             // Chave primária na tabela 'perfil'
            'CASCADE',
            'CASCADE'
        );

        // 4. Linha Rosa (Cima): Liga 'reservas' a 'espacos' (reservas.id_espaco -> espacos.id_espaco)
        $this->addForeignKey(
            'fk-reservas-id_espaco',   // Nome único da ligação
            '{{%reservas}}',           // Tabela filha: 'reservas'
            'id_espaco',               // Coluna na tabela 'reservas'
            '{{%espacos}}',            // Tabela mãe: 'espacos'
            'id_espaco',               // Chave primária na tabela 'espacos'
            'CASCADE',
            'CASCADE'
        );

        // 5. Linha Verde (Esquerda): Liga 'pagamentos' a 'reservas' (pagamentos.id_reserva -> reservas.id_reserva)
        $this->addForeignKey(
            'fk-pagamentos-id_reserva', // Nome único da ligação
            '{{%pagamentos}}',          // Tabela filha: 'pagamentos'
            'id_reserva',               // Coluna na tabela 'pagamentos'
            '{{%reservas}}',            // Tabela mãe: 'reservas'
            'id_reserva',               // Chave primária na tabela 'reservas'
            'CASCADE',
            'CASCADE'
        );

        // 6. Linha Verde (Direita): Liga 'avaliacoes' a 'reservas' (avaliacoes.id_reserva -> reservas.id_reserva)
        $this->addForeignKey(
            'fk-avaliacoes-id_reserva', // Nome único da ligação
            '{{%avaliacoes}}',          // Tabela filha: 'avaliacoes'
            'id_reserva',               // Coluna na tabela 'avaliacoes'
            '{{%reservas}}',            // Tabela mãe: 'reservas'
            'id_reserva',               // Chave primária na tabela 'reservas'
            'CASCADE',
            'CASCADE'
        );

        // 7. Linha Amarela (Baixo): Liga 'favoritos' a 'perfil' (favoritos.id_utilizador -> perfil.id_utilizador)
        $this->addForeignKey(
            'fk-favoritos-id_utilizador', // Nome único da ligação
            '{{%favoritos}}',             // Tabela filha: 'favoritos'
            'id_perfil',              // Coluna na tabela 'favoritos'
            '{{%perfil}}',                // Tabela mãe: 'perfil'
            'id_perfil',              // Chave primária na tabela 'perfil'
            'CASCADE',
            'CASCADE'
        );

        // 8. Linha Rosa (Baixo): Liga 'favoritos' a 'espacos' (favoritos.id_espaco -> espacos.id_espaco)
        $this->addForeignKey(
            'fk-favoritos-id_espaco',   // Nome único da ligação
            '{{%favoritos}}',           // Tabela filha: 'favoritos'
            'id_espaco',                // Coluna na tabela 'favoritos'
            '{{%espacos}}',             // Tabela mãe: 'espacos'
            'id_espaco',                // Chave primária na tabela 'espacos'
            'CASCADE',
            'CASCADE'
        );
    }

    // Função executada se correres 'php yii migrate/down' (remove as ligações pela ordem inversa)
    public function safeDown()
    {
        // Desliga cada chave estrangeira indicando (1º Nome da ligação, 2º Tabela filha onde ela foi criada)
        $this->dropForeignKey('fk-favoritos-id_espaco', '{{%favoritos}}');
        $this->dropForeignKey('fk-favoritos-id_utilizador', '{{%favoritos}}');
        $this->dropForeignKey('fk-avaliacoes-id_reserva', '{{%avaliacoes}}');
        $this->dropForeignKey('fk-pagamentos-id_reserva', '{{%pagamentos}}');
        $this->dropForeignKey('fk-reservas-id_espaco', '{{%reservas}}');
        $this->dropForeignKey('fk-reservas-id_utilizador', '{{%reservas}}');
        $this->dropForeignKey('fk-espacos-id_edificio', '{{%espacos}}');
        $this->dropForeignKey('fk-perfil-id_utilizador', '{{%perfil}}');
    }

    /**
     * {@inheritdoc}
     */

}
