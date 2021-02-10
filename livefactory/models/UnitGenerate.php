<?php


namespace livefactory\models;

use Yii;
use yii\helpers\Html;

/**
 * Class UnitGenerate
 * @property integer $id
 * @property string $name
 * @property  string $description
 */

class UnitGenerate extends  \Yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_unit_generate';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['id', 'active'], 'integer'],
            [['name', 'description'], 'string', 'max' => 255 ]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'Id'),
            'name' =>Yii::t('app','Name'),
            'active' => Yii::t('app', 'active'),
            'description' =>Yii::t('app', 'description'),
        ];
    }


}