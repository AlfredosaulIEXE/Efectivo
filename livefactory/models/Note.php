<?php

namespace livefactory\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "tbl_note".
 *
 * @property integer $id
 * @property string $notes
 * @property integer $user_id
 * @property integer $entity_id
 * @property string $entity_type
 * @property integer $added_at
 * @property integer $updated_at
 */
class Note extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_note';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['notes', 'user_id', 'entity_id', 'entity_type'], 'required'],
            [['notes', 'note_type'], 'string'],
            [['user_id', 'entity_id', 'added_at', 'updated_at'], 'integer'],
            [['entity_type'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'notes' => Yii::t('app', 'Notes'),
            'user_id' => Yii::t('app', 'User ID'),
            'entity_id' => Yii::t('app', 'Entity ID'),
            'entity_type' => Yii::t('app', 'Entity Type'),
			'note_type' => Yii::t('app', 'Note Type'),
            'added_at' => Yii::t('app', 'Added At'),
            'updated_at' => Yii::t('app', 'Updated At'),
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
