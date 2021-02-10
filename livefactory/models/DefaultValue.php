<?php

namespace livefactory\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "tbl_default_value".
 *
 * @property integer $id
 * @property string $entity_type
 * @property integer $entity_id
 */
class DefaultValue extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_default_value';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['entity_type', 'entity_id'], 'required'],
            [['entity_id'], 'integer'],
            [['entity_type'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'entity_type' => Yii::t('app', 'Entity Type'),
            'entity_id' => Yii::t('app', 'Entity ID'),
        ];
    }

	public function beforeSave($insert) {
		$this->entity_type = Html::encode($this->entity_type);
		return parent::beforeSave($insert);
	}
	
}
