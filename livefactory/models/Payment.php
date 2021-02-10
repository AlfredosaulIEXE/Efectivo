<?php

namespace livefactory\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "tbl_note".
 *
 * @property integer $id
 * @property integer $generator_id
 * @property integer $co_generator_id
 * @property string $amount
 * @property string $note
 * @property integer $type
 * @property string $date
 * @property string $code
 * @property string $received
 * @property string $origin
 * @property string $folio
 * @property integer $status
 * @property integer $entity_id
 * @property string $entity_type
 * @property integer $file_id
 * @property integer $added_at
 * @property integer $updated_at
 * @property intenger $total_due
 */
class Payment extends \yii\db\ActiveRecord
{

    const _NEW_CONTRACT = '1';
    const _ADVANCE = '2';
    const _ADDENDUMS = '3';
    const _INCREASE = '4';
    const _INSURANCE = '5';
    const _REPAYMENT = '6';
    const _REFUSED = '7';

    const UNVALIDATED = 0;
    const VALIDATED = 1;
    const DECLINED = 2;

    /**
     * Payment types
     * @var array
     */
    protected static $_types = [
        1 => 'Cobro contrato nuevo',
        2 => 'Cobro anticipo',
        3 => 'Cobro addendums',
        4 => 'Cobro incremento',
        5 => 'Cobro de seguro',
        6 => 'Devolución',
        7 => 'Pago Rechazado'
    ];

    /**
     * Payment types for gestion
     * @var array
     */
    protected static $_gestionTypes = [
        1 => 'Gastos de Investigación',
        2 => 'Gastos Administrativos',
        3 => 'Cargos por Seguro',
        4 => 'Gastos de Ejecución',
        5 => 'Gastos Operativos'
    ];

    /**
     * @var array
     */
    protected static $_paymentStatus = [
        'Por validar',
        'Validado',
        'Declinado'
    ];

    /**
     * Get status name
     *
     * @param $status
     * @return mixed
     */
    public static function paymentStatus($status)
    {
        return self::$_paymentStatus[$status];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_payment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['amount', 'type', 'date', 'origin', 'generator_id'], 'required'],
            [['generator_id', 'co_generator_id', 'type' ,'entity_id', 'file_id', 'origin', 'folio', 'added_at', 'updated_at','status'], 'integer'],
            [['amount'], 'string'],
            [['entity_type', 'note', 'date', 'code', 'received'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'generator_id' => 'Generó',
            'co_generator_id' => 'Co-generador',
            'amount' => Yii::t('app', 'Amount'),
            'note' => Yii::t('app', 'Note'),
            'type' => Yii::t('app', 'Type'),
            'date' => Yii::t('app', 'Date'),
            'code' => Yii::t('app', 'Code'),
            'received' => Yii::t('app', 'Received'),
            'origin' => Yii::t('app', 'Origin'),
            'entity_id' => Yii::t('app', 'Entity ID'),
            'entity_type' => Yii::t('app', 'Entity Type'),
            'file_id' => Yii::t('app', 'File'),
            'added_at' => Yii::t('app', 'Added At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function beforeSave($insert) {
        $this->amount = $this->currency($this->amount);
        //$this->notes = Html::encode($this->notes);
        return parent::beforeSave($insert);
    }

    /**
     * Parses amount
     *
     * @param $amount
     * @return mixed
     */
    private function currency($amount) {
        return str_replace(array('$', ' ', ','), '', $amount);
    }

    /**
     * Get payment types
     *
     * @param bool $definition
     * @return array
     */
    public static function getTypes($definition = false)
    {
        $types = self::$_types;

        if ($definition === true)
            $types = self::$_gestionTypes;

        /*if ( Yii::$app->user->can('Office.NoLimit')) {
            $types = self::$_gestionTypes;
        } else {
            $types = self::$_types;
        }*/

        return $types;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'generator_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGenerator()
    {
        return $this->hasOne(User::className(), ['id' => 'co_generator_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLead()
    {
        return $this->hasOne(Lead::className(), ['id' => 'entity_id']);
    }
}
