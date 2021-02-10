<?php

namespace livefactory\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "tbl_email_template".
 *
 * @property integer $id
 * @property string $template_name
 * @property string $template_subject
 * @property resource $template_body
 * @property integer $added_at
 * @property integer $updated_at
 */
class EmailTemplate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_email_template';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['template_name', 'template_subject', 'template_body'], 'required'],
            [['template_body'], 'string'],
            [['added_at', 'updated_at'], 'integer'],
            [['template_name', 'template_subject'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'template_name' => Yii::t('app', 'Template Name'),
            'template_subject' => Yii::t('app', 'Template Subject'),
            'template_body' => Yii::t('app', 'Template Body'),
            'added_at' => Yii::t('app', 'Added At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

	public function beforeSave($insert) {
		//$this->template_name = Html::encode($this->template_name);
		//$this->template_subject = Html::encode($this->template_subject);
		//$this->template_body = Html::encode($this->template_body);
		return parent::beforeSave($insert);
	}
}
