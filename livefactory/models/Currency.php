<?php

namespace livefactory\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "tbl_currency".
 *
 * @property integer $id
 * @property string $currency
 * @property string $alphabetic_code
 * @property string $numeric_code
 * @property string $minor_unit
 */
class Currency extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_currency';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['currency'], 'required'],
            [['currency'], 'string', 'max' => 65],
            [['alphabetic_code'], 'string', 'max' => 15],
            [['numeric_code'], 'string', 'max' => 12],
            [['minor_unit'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'currency' => Yii::t('app', 'Currency'),
            'alphabetic_code' => Yii::t('app', 'Alphabetic Code'),
            'numeric_code' => Yii::t('app', 'Numeric Code'),
            'minor_unit' => Yii::t('app', 'Minor Unit'),
        ];
    }

	public function beforeSave($insert) {
		$this->currency = Html::encode($this->currency);
		$this->alphabetic_code = Html::encode($this->alphabetic_code);
		$this->numeric_code = Html::encode($this->numeric_code);
		$this->minor_unit = Html::encode($this->minor_unit);
		return parent::beforeSave($insert);
	}


}
