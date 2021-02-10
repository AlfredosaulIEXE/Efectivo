<?php

namespace livefactory\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "tbl_office".
 *
 * @property integer $id
 * @property string  $code
 * @property string  $description
 * @property string  $rfc
 * @property string  $business_name
 * @property string  $weekly_goal
 * @property string #folio
 * @property integer $added_at
 * @property integer $updated_at
 */
class Office extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_office';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'rfc', 'business_name'], 'required'],
            [['folio', 'reports','added_at', 'updated_at'], 'integer'],
            [['code', 'description', 'rfc', 'business_name', 'website', 'weekly_goal', 'notary', 'notary_number', 'notary_id', 'notary_state'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'code' => Yii::t('app', 'Code'),
            'description' => Yii::t('app', 'Description'),
            'rfc' => Yii::t('app', 'RFC'),
            'business_name' => Yii::t('app', 'Business Name'),
            'weekly_goal' => Yii::t('app', 'Weekly Goal'),
            'website' => Yii::t('app', 'Website'),
            'folio' => Yii::t('app', 'Folio'),
            'added_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    private function currency($amount) {
        return str_replace(array('$', ' ', ','), '', $amount);
    }

	public function beforeSave($insert) {
		$this->description = Html::encode($this->description);
        $this->weekly_goal = $this->currency($this->weekly_goal);
		$this->rfc = strtoupper($this->rfc);
		return parent::beforeSave($insert);
	}

	public function getAddress()
    {
        return Address::findOne(['entity_id' => $this->id, 'entity_type' => 'office']);
    }

    public function getContact()
    {
        return Contact::findOne(['entity_id' => $this->id, 'entity_type' => 'office']);
    }
}
