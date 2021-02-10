<?php

namespace livefactory\models;

use Yii;

/**
 * This is the model class for table "tbl_resolution_reference".
 *
 * @property integer $id
 * @property integer $resolution_id
 * @property integer $ticket_id
 */
class ResolutionReference extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_resolution_reference';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['resolution_id', 'ticket_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'resolution_id' => Yii::t('app', 'Resolution ID'),
            'ticket_id' => Yii::t('app', 'Ticket ID'),
        ];
    }

	public function getCountLinkedWithResolution($id)
	{
		return ResolutionReference::find()->where('resolution_id='.$id)->count();
	}

	public function deleteTicketResolution($res_id, $ticket_id)
	{
		ResolutionReference::find()->where('resolution_id='.$res_id.' and ticket_id='.$ticket_id)->one()->delete();
	}
}
