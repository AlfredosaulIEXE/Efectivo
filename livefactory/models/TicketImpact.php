<?php

namespace livefactory\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "tbl_ticket_impact".
 *
 * @property integer $id
 * @property string $impact
 * @property string $label
 * @property integer $active
 * @property integer $sort_order
 * @property integer $added_at
 * @property integer $updated_at
 */
class TicketImpact extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_ticket_impact';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['impact', 'label', 'active', 'sort_order'], 'required'],
            [['active', 'sort_order', 'added_at', 'updated_at'], 'integer'],
            [['impact', 'label'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'impact' => Yii::t('app', 'Impact'),
            'label' => Yii::t('app', 'Label'),
            'active' => Yii::t('app', 'Active'),
            'sort_order' => Yii::t('app', 'Sort Order'),
            'added_at' => Yii::t('app', 'Added At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

	public function beforeSave($insert) {
		$this->impact = Html::encode($this->impact);
		$this->label = Html::encode($this->label);
		return parent::beforeSave($insert);
	}
}
