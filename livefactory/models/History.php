<?php

namespace livefactory\models;

use Yii;

/**
 * This is the model class for table "tbl_history".
 *
 * @property integer $id
 * @property string $notes
 * @property string $user_id
 * @property integer $entity_id
 * @property string $entity_type
 * @property string $session_id
 * @property integer $added_at
 * @property integer $updated_at
 */
class History extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['notes', 'user_id', 'entity_id', 'entity_type'], 'required'],
            [['entity_id', 'added_at', 'updated_at', 'user_id'], 'integer'],
            [['session_id','entity_type'], 'string', 'max' => 255]
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
			'session_id' => Yii::t('app', 'Session Id'),
            'added_at' => Yii::t('app', 'Added At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
	public function getUser()

    {

    	return $this->hasOne(User::className(), ['id' => 'user_id']);

    }
}
