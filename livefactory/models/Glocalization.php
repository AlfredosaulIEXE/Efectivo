<?php

namespace livefactory\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "tbl_glocalization".
 *
 * @property integer $id
 * @property string $language
 * @property string $locale
 * @property integer $added_at
 * @property integer $updated_at
 */
class Glocalization extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_glocalization';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['language', 'locale'], 'required'],
            [['added_at', 'updated_at'], 'integer'],
            [['language', 'locale'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'language' => Yii::t('app', 'Language'),
            'locale' => Yii::t('app', 'Locale'),
            'added_at' => Yii::t('app', 'Added At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

	public function beforeSave($insert) {
		$this->language = Html::encode($this->language);
		$this->locale = Html::encode($this->locale);
		return parent::beforeSave($insert);
	}
}
