<?php

namespace livefactory\models;

use Yii;

/**
 * This is the model class for table "tbl_prd_lic".
 *
 * @property integer $id
 * @property string $prd_lic_name
 * @property string $prd_lic_desc
 * @property integer $prd_lic_status
 * @property string $prd_lic_date
 * @property integer $added_at
 * @property integer $updated_at
 */
class PrdLic extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_prd_lic';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['prd_lic_name', 'prd_lic_desc', 'prd_lic_status', 'prd_lic_date'], 'required'],
            [['prd_lic_status'], 'integer'],
            [['prd_lic_date'], 'safe'],
            [['prd_lic_name'], 'string', 'max' => 255],
            [['prd_lic_desc'], 'string', 'max' => 1024]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'prd_lic_name' => Yii::t('app', 'Prd Lic Name'),
            'prd_lic_desc' => Yii::t('app', 'Prd Lic Desc'),
            'prd_lic_status' => Yii::t('app', 'Prd Lic Status'),
            'prd_lic_date' => Yii::t('app', 'Prd Lic Date'),
            'added_at' => Yii::t('app', 'Added At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
