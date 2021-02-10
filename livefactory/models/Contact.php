<?php

namespace livefactory\models;
use yii\helpers\Html;

use Yii;

/**
 * This is the model class for table "tbl_contact".
 *
 * @property integer $id
 * @property string $first_name
 * @property string $middle_name
 * @property string $last_name
 * @property string $email
 * @property string $phone
 * @property string $phone_ext
 * @property string $mobile
 * @property string $fax
 * @property integer $added_at
 * @property integer $updated_at
 */
class Contact extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_contact';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['first_name'], 'required'],
            [['added_at','is_primary', 'updated_at','entity_id'], 'integer'],
            [['first_name', 'last_name', 'middle_name', 'email', 'entity_type', 'phone', 'phone_ext', 'mobile', 'fax'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'first_name' => Yii::t('app', 'First Name'),
            'last_name' => Yii::t('app', 'Last Name'),
            'middle_name' => Yii::t('app', 'Middle Name'),
            'email' => Yii::t('app', 'Email'),
            'phone' => Yii::t('app', 'Phone'),
            'phone_ext' => Yii::t('app', 'Phone Ext'),
            'mobile' => Yii::t('app', 'Mobile'),
            'fax' => Yii::t('app', 'Fax'),
            'added_at' => Yii::t('app', 'Added At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

	public function beforeSave($insert) {
		$this->first_name = mb_strtoupper(trim($this->first_name));
		$this->last_name = mb_strtoupper(trim($this->last_name));
		$this->middle_name = mb_strtoupper(trim($this->middle_name));
		$this->email = Html::encode($this->email);
		$this->phone = Html::encode($this->phone);
		$this->phone_ext = Html::encode($this->phone_ext);
		$this->mobile = Html::encode($this->mobile);
		$this->fax = Html::encode($this->fax);
		return parent::beforeSave($insert);
	}
}
