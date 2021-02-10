<?php

namespace livefactory\models;
use yii\helpers\Html;

use Yii;

/**
 * This is the model class for table "tbl_address".
 *
 * @property integer $id
 * @property string $address_1
 * @property string $address_2
 * @property string $num_ext
 * @property string $num_int
 * @property string $block
 * @property integer $country_id
 * @property integer $state_id
 * @property integer $city_id
 * @property string $delegation
 * @property string $zipcode
 * @property integer $added_at
 * @property integer $updated_at
 */
class Address extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['country_id'], 'required'],
            [['country_id','is_primary', 'state_id', 'city_id','entity_id', 'added_at', 'updated_at'], 'integer'],
            [['address_1', 'address_2','entity_type', 'zipcode'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'address_1' => Yii::t('app', 'Address 1'),
            'address_2' => Yii::t('app', 'Address 2'),
            'country_id' => Yii::t('app', 'Country'),
            'state_id' => Yii::t('app', 'State'),
            'city_id' => Yii::t('app', 'City'),
            'zipcode' => Yii::t('app', 'Zipcode'),
            'added_at' => Yii::t('app', 'Added At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
	public function beforeSave($insert) {
		$this->address_1 = mb_strtoupper($this->address_1);
		$this->address_2 = mb_strtoupper($this->address_2);
        $this->num_ext = Html::encode(mb_strtoupper($this->num_ext));
        $this->num_int = Html::encode(mb_strtoupper($this->num_int));
        $this->block = mb_strtoupper($this->block);
        $this->delegation = mb_strtoupper($this->delegation);

		if($this->city_id == NULL) 
			$this->city_id = 0;
		return parent::beforeSave($insert);
	}
	public function getCountry()

    {

    	return $this->hasOne(Country::className(), ['id' => 'country_id']);

    }
	


	public function getState()

    {

    	return $this->hasOne(State::className(), ['id' => 'state_id']);

    }



	public function getCity()

    {

    	return $this->hasOne(City::className(), ['id' => 'city_id']);

    }
}
