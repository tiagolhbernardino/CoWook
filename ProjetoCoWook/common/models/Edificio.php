<?php

// Define o namespace na pasta 'common' para que o modelo possa ser usado no Backend e no Frontend
namespace common\models;

// Importa a classe global Yii
use Yii;
// Importa a classe ActiveQuery para tipar as relações entre tabelas
use yii\db\ActiveQuery;
// Importa a classe base ActiveRecord do Yii2 para comunicar com a tabela no MySQL
use yii\db\ActiveRecord;

/**
 * Modelo ActiveRecord para a tabela "edificios".
 *
 * Propriedades correspondentes às colunas da tabela no MySQL:
 * @property int $id_edificio Chave primária auto-incrementada do edifício
 * @property string $nome Nome do edifício (ex: CoWook Leiria Centro)
 * @property string $morada Rua, número e código postal do edifício
 * @property string $cidade Cidade onde se localiza o edifício
 * @property int $ativo Estado do edifício (1 = Ativo, 0 = Inativo / Soft Deleted)
 *
 * Relações ligadas por Chave Estrangeira:
 * @property Espaco[] $espacos Lista de todos os espaços/salas que pertencem a este edifício
 */
class Edificio extends ActiveRecord
{
    // Constante que representa um edifício eliminado logicamente (Soft Delete)
    public const STATUS_INATIVO = 0;

    // Constante que representa um edifício ativo e disponível para reservas
    public const STATUS_ATIVO = 1;

    /**
     * Indica ao Yii2 o nome exato da tabela na base de dados MySQL.
     */
    public static function tableName(): string
    {
        // Usa {{%edificios}} para aplicar automaticamente qualquer prefixo configurado na BD
        return '{{%edificios}}';
    }

    /**
     * Define as regras de validação dos dados antes de gravar na base de dados.
     */
    public function rules(): array
    {
        return [
            // Remove espaços em branco extra no início e fim destes três campos de texto
            [['nome', 'morada', 'cidade'], 'trim'],

            // Garante que se o campo 'ativo' vier vazio, assume por defeito o valor 1 (Ativo)
            [['ativo'], 'default', 'value' => self::STATUS_ATIVO],

            // Torna o preenchimento do nome, morada e cidade obrigatório no formulário
            [['nome', 'morada', 'cidade'], 'required', 'message' => 'O campo {attribute} é de preenchimento obrigatório.'],

            // Garante que o campo 'ativo' é sempre um número inteiro e só aceita 0 ou 1
            [['ativo'], 'integer'],
            ['ativo', 'in', 'range' => [self::STATUS_INATIVO, self::STATUS_ATIVO]],

            // Limita o nome e a cidade a 50 caracteres (igual ao VARCHAR(50) da migration)
            [['nome', 'cidade'], 'string', 'max' => 50, 'tooLong' => 'O campo {attribute} não pode ter mais de 50 caracteres.'],

            // Limita a morada a 150 caracteres (igual ao VARCHAR(150) da migration)
            [['morada'], 'string', 'max' => 150, 'tooLong' => 'A morada não pode ter mais de 150 caracteres.'],
        ];
    }

    /**
     * Define as etiquetas (labels) em português que aparecem nos formulários e nas tabelas (GridView).
     */
    public function attributeLabels(): array
    {
        return [
            'id_edificio' => 'ID Edifício',      // Título para a chave primária
            'nome' => 'Nome do Edifício',        // Título para o campo nome
            'morada' => 'Morada Completa',       // Título para o campo morada
            'cidade' => 'Cidade / Localidade',   // Título para o campo cidade
            'ativo' => 'Estado do Edifício',     // Título para o estado (Ativo / Desativado)
        ];
    }

    /**
     * Relação 1:N (Um para Muitos) com a tabela 'espacos'.
     * Permite aceder a todas as salas deste edifício fazendo: $edificio->espacos
     *
     * @return ActiveQuery
     */
    public function getEspacos(): ActiveQuery
    {
        // Liga a coluna 'id_edificio' da tabela 'espacos' à coluna 'id_edificio' desta tabela 'edificios'
        return $this->hasMany(Espaco::class, ['id_edificio' => 'id_edificio']);
    }
}