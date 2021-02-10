<?php

namespace livefactory\models;

use Yii;

/**
 * This is the model class for table "tbl_prd_mdl".
 *
 * @property integer $id
 * @property string $mdl_name
 * @property string $mdl_desc
 * @property string $mdl_status
 * @property integer $added_at
 * @property integer $updated_at
 */
class PrdMdl extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_prd_mdl';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mdl_name', 'mdl_desc','mdl_status'], 'required'],
			 [['mdl_status'], 'integer'],
            [['mdl_name'], 'string', 'max' => 255],
            [['mdl_desc'], 'string', 'max' => 1024]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'mdl_name' => Yii::t('app', 'Mdl Name'),
            'mdl_desc' => Yii::t('app', 'Mdl Desc'),
			'mdl_status' => Yii::t('app', 'Mdl Status'),
            'added_at' => Yii::t('app', 'Added At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
