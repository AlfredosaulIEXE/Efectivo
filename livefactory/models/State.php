<?php

namespace livefactory\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "tbl_state".
 *
 * @property integer $id
 * @property string $state
 * @property string $state_code
 * @property integer $active
 * @property integer $country_id
 * @property integer $added_at
 * @property integer $updated_at
 */
class State extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_state';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['state',  'active', 'country_id'], 'required'],
            [['active', 'country_id', 'added_at', 'updated_at'], 'integer'],
            [['state', 'state_code'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'state' => Yii::t('app', 'State'),
            'state_code' => Yii::t('app', 'State Code'),
            'active' => Yii::t('app', 'Active'),
            'country_id' => Yii::t('app', 'Country'),
            'added_at' => Yii::t('app', 'Added At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
	public function getCountry()
    {
    	return $this->hasOne(Country::className(), ['id' => 'country_id']);
    }

	public function beforeSave($insert) {
		$this->state = Html::encode($this->state);
		$this->state_code = Html::encode($this->state_code);
		return parent::beforeSave($insert);
	}
}
