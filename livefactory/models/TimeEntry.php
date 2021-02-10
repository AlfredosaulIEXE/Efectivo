<?php

namespace livefactory\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "lot_time_entry".
 *
 * @property integer $id
 * @property integer $start_time
 * @property integer $end_time
 * @property integer $entity_id
 * @property string $entity_type
 * @property integer $user_id
 * @property string $entry_type
 * @property integer $added_at
 * @property integer $modified_by_user_id
 * @property integer $modified_at
 * @property string $notes
 */
class TimeEntry extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_time_entry';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['start_time', 'entity_id', 'entity_type', 'user_id', 'entry_type', 'notes'], 'required'],
            [['start_time', 'end_time'], 'safe'],
            [['entity_id', 'user_id', 'added_at', 'modified_by_user_id', 'modified_at'], 'integer'],
            [['entity_type', 'entry_type'], 'string', 'max' => 255],
            [['notes'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'start_time' => Yii::t('app', 'Start Time'),
            'end_time' => Yii::t('app', 'End Time'),
            'entity_id' => Yii::t('app', 'Entity ID'),
            'entity_type' => Yii::t('app', 'Entity Type'),
            'user_id' => Yii::t('app', 'User ID'),
            'entry_type' => Yii::t('app', 'Entry Type'),
            'added_at' => Yii::t('app', 'Added At'),
            'modified_by_user_id' => Yii::t('app', 'Modified By User ID'),
            'modified_at' => Yii::t('app', 'Modified At'),
            'notes' => Yii::t('app', 'Notes'),
        ];
    }
	public function getUser()
    {
    	return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

	public function beforeSave($insert) {
		//$this->notes = Html::encode($this->notes);
		return parent::beforeSave($insert);
	}
}
