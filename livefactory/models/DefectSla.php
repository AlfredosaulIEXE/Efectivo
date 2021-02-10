<?php

namespace livefactory\models;
use livefactory\models\DefectPriority;
use livefactory\models\DefectType;

use Yii;

/**
 * This is the model class for table "tbl_defect_sla".
 *
 * @property integer $id
 * @property integer $defect_priority_id
 * @property integer $defect_type_id
 * @property integer $start_sla
 * @property integer $end_sla
 */
class DefectSla extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_defect_sla';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['defect_priority_id', 'defect_type_id', 'start_sla', 'end_sla'], 'required'],
            [['defect_priority_id', 'defect_type_id', 'start_sla', 'end_sla'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'defect_priority_id' => Yii::t('app', 'Defect Priority'),
			'defect_type_id' => Yii::t('app', 'Defect Type'),
            'start_sla' => Yii::t('app', 'Start Sla'),
            'end_sla' => Yii::t('app', 'End Sla'),
        ];
    }

	public function getDefectPriority()
    {
    	return $this->hasOne(DefectPriority::className(), ['id' => 'defect_priority_id']);
    }

	public function getDefectType()
    {
    	return $this->hasOne(DefectType::className(), ['id' => 'defect_type_id']);
    }
}
