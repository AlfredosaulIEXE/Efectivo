<?php

namespace livefactory\models;

use Yii;

/**
 * This is the model class for table "tbl_prd_mdl_lic".
 *
 * @property integer $id
 * @property integer $prd_lic_id
 * @property integer $prd_mdl_id
 */
class PrdMdlLic extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_prd_mdl_lic';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['prd_lic_id', 'prd_mdl_id'], 'required'],
            [['prd_lic_id', 'prd_mdl_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'prd_lic_id' => Yii::t('app', 'Prd Lic'),
            'prd_mdl_id' => Yii::t('app', 'Prd Mdl'),
        ];
    }
	public function getModule()

    {

    	return $this->hasOne(PrdMdl::className(), ['id' => 'prd_mdl_id']);

    }
}
