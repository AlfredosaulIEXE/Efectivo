<?php

namespace livefactory\models;
use livefactory\models\History;
use Yii;
use yii\filters\VerbFilter;
use yii\db\Query;
class HistoryModel extends \yii\db\ActiveRecord
{

    const ACTION_LOGIN = 1;
    const ACTION_EXPORT = 2;
    const ACTION_DELETE = 3;
    const ACTION_RESTORE = 4;
    const ACTION_VIEW_LEAD = 5;

    /**
     * @return array
     */
    public static function actions()
    {
        return [
            HistoryModel::ACTION_LOGIN => 'Ingreso',
            HistoryModel::ACTION_EXPORT => 'Exportación',
            HistoryModel::ACTION_DELETE => 'Eliminación',
            HistoryModel::ACTION_RESTORE => 'Restauración',
            HistoryModel::ACTION_VIEW_LEAD => 'Consulta de Lead'
        ];
    }

    /**
     * @param $aid
     * @return mixed
     */
    public static function getAction($aid)
    {
        $actions = self::actions();

        return $actions[$aid];
    }

	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '';
    }
    public static  function historyInsert($entity_type,$entity_id,$notes, $user_id=0, $time = null){
		date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
		$addHistory = new History;
		$addHistory->entity_id=$entity_id;
		$addHistory->entity_type=$entity_type;
		$addHistory->notes=$notes;
		if($user_id == 0)
			$addHistory->user_id=Yii::$app->user->identity->id;
		else
			$addHistory->user_id=$user_id;
		$addHistory->added_at=strtotime(date('Y-m-d H:i:s'));
		$addHistory->session_id=session_id();
		$addHistory->prod_time = $time; // TODO: Add to sql
		$addHistory->save();
		if ($time != null)
        {
            UserActivity::updateProductivity($time);
        }

		if($addHistory->errors){
		var_dump($addHistory->errors);
		exit();
		}
	}
}
