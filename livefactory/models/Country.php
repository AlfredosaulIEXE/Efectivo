<?php

namespace livefactory\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "tbl_country".
 *
 * @property integer $id
 * @property string $country
 * @property string $country_code
 * @property integer $active
 * @property integer $region_id
 * @property integer $added_at
 * @property integer $updated_at
 */
class Country extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_country';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['country', 'country_code', 'active', 'region_id'], 'required'],
            [['active', 'region_id', 'added_at', 'updated_at'], 'integer'],
            [['country', 'country_code'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'country' => Yii::t('app', 'Country'),
            'country_code' => Yii::t('app', 'Country Code'),
            'active' => Yii::t('app', 'Active'),
            'region_id' => Yii::t('app', 'Region ID'),
            'added_at' => Yii::t('app', 'Added At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
	public function getRegion()
    {
    	return $this->hasOne(Region::className(), ['id' => 'region_id']);
    }

	public function beforeSave($insert) {
		$this->country = Html::encode($this->country);
		$this->country_code = Html::encode($this->country_code);
		return parent::beforeSave($insert);
	}
}
