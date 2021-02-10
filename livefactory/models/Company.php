<?php

namespace livefactory\models;

use Yii;

/**
 * This is the model class for table "tbl_company".
 *
 * @property integer $id
 * @property string $company_name
 * @property string $company_email
 * @property string $phone
 * @property string $mobile
 * @property string $fax
 * @property integer $added_at
 * @property integer $updated_at
 */
class Company extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_company';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['company_name', 'company_email', 'mobile'], 'required'],
            [['added_at', 'updated_at'], 'integer'],
            [['company_name', 'company_email', 'phone', 'mobile', 'fax'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'company_name' => Yii::t('app', 'Company Name'),
            'company_email' => Yii::t('app', 'Company Email'),
            'phone' => Yii::t('app', 'Phone'),
            'mobile' => Yii::t('app', 'Mobile'),
            'fax' => Yii::t('app', 'Fax'),
            'added_at' => Yii::t('app', 'Added At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
