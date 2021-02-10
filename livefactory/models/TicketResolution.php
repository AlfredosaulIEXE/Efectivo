<?php

namespace livefactory\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "tbl_ticket_resolution".
 *
 * @property integer $id
 * @property string $resolution_number
 * @property string $subject
 * @property string $resolution
 * @property integer $resolved_by_user_id
 * @property integer $added_at
 * @property integer $updated_at
 */
class TicketResolution extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_ticket_resolution';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['resolved_by_user_id'], 'integer'],            
            [['added_at', 'updated_at','subject', 'resolution_number'], 'safe'],
            [['subject'], 'string', 'max' => 255],
            [['resolution'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
			'resolution_number' => Yii::t('app', 'Resolution Number'),
            'subject' => Yii::t('app', 'Subject'),
            'resolution' => Yii::t('app', 'Resolution'),
            'resolved_by_user_id' => Yii::t('app', 'Resolved By User ID'),
            'added_at' => Yii::t('app', 'Added At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

	public function getUser()
    {
    	return $this->hasOne(User::className(), ['id' => 'resolved_by_user_id']);
    }

	public function beforeSave($insert) {
		$this->subject = Html::encode($this->subject);
		//$this->resolution = Html::encode($this->resolution);
		return parent::beforeSave($insert);
	}
}
