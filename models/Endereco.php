<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "endereco".
 *
 * @property int $id
 * @property int $id_cliente
 * @property string $logradouro
 * @property string $numero
 * @property string $complemento
 * @property string $bairro
 * @property string $cep
 * @property int $cidade_id
 * @property int $estado_id
 * @property string $observacao
 * @property string $ativo Flag que valida se o endereco esta ativo
 *
 * @property Cliente $cliente
 * @property Cidade $cidade
 * @property Estado $estado
 */
class Endereco extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'endereco';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_cliente', 'logradouro',], 'required'],
            [['id_cliente', 'cidade_id', 'estado_id'], 'integer'],
            [['ativo'], 'string'],
            [['logradouro', 'bairro'], 'string', 'max' => 100],
            [['numero'], 'string', 'max' => 10],
            [['complemento'], 'string', 'max' => 50],
            [['cep'], 'string', 'max' => 8],
            [['observacao'], 'string', 'max' => 250],
            [['id_cliente'], 'exist', 'skipOnError' => true, 'targetClass' => Cliente::className(), 'targetAttribute' => ['id_cliente' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_cliente' => 'Id Cliente',
            'logradouro' => 'Logradouro',
            'numero' => 'Numero',
            'complemento' => 'Complemento',
            'bairro' => 'Bairro',
            'cep' => 'Cep',
            'cidade_id' => 'Cidade',
            'estado_id' => 'Estado',
            'observacao' => 'Observacao',
            'ativo' => 'Ativo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCliente()
    {
        return $this->hasOne(Cliente::className(), ['id' => 'id_cliente']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCidade()
    {
        return $this->hasOne(Cidade::className(), ['id' => 'cidade_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEstado()
    {
        return $this->hasOne(Estado::className(), ['id' => 'estado_id']);
    }
}
