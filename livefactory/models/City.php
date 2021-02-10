<?php

namespace livefactory\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "tbl_city".
 *
 * @property integer $id
 * @property string $city
 * @property integer $active
 * @property integer $state_id
 * @property integer $country_id
 * @property integer $added_at
 * @property integer $updated_at
 */
class City extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_city';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['city', 'active', 'state_id', 'country_id'], 'required'],
            [['active', 'state_id', 'country_id', 'added_at', 'updated_at'], 'integer'],
            [['city'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'city' => Yii::t('app', 'City'),
            'active' => Yii::t('app', 'Active'),
            'state_id' => Yii::t('app', 'State'),
            'country_id' => Yii::t('app', 'Country'),
            'added_at' => Yii::t('app', 'Added At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
	 public function getCountry()

    {

    	return $this->hasOne(Country::className(), ['id' => 'country_id']);

    }

    

    public function getState()

    {

    	return $this->hasOne(State::className(), ['id' => 'state_id']);

    }

	public function beforeSave($insert) {
		$this->city=Html::encode($this->city);
		return parent::beforeSave($insert);
	}
}
