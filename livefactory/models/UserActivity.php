<?php

namespace livefactory\models;

use Yii;
use yii\db\Query;
use yii\helpers\Html;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "tbl_user_activity".
 *
 * @property integer $id
 * @property string $date_activity
 * @property integer $user_id
 * @property integer $time_online
 * @property integer $productivity_time
 * @property integer $first_activity
 * @property integer $last_activity
 */
class UserActivity extends \yii\db\ActiveRecord
{
    // Times
    const SIX_MINUTES = 360;
    const FIVE_MINUTES = 300;
    const THREE_MINUTES = 180;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_user_activity';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'date_activity', 'first_activity'], 'required'],
            [['user_id', 'time_online','productivity_time', 'first_activity', 'last_activity'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User id'),
            'date_activity' => Yii::t('app', 'Date activity'),
            'time_online' => Yii::t('app', 'Time online'),
            'productivity_time' => Yii::t('app', 'Productivity time'),
            'first_activity' => Yii::t('app', 'Updated At'),
            'last_activity' => Yii::t('app', 'last_activity'),

        ];
    }

    /**
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function getCurrentActivity()
    {
        $dateActivity = date('Y-m-d');
        $userId = Yii::$app->user->id;

        return UserActivity::find()->where('user_id = ' . $userId . " and date_activity = '" . $dateActivity . "'")->one();
    }

    /**
     *
     */
    public static function insertIfNotExists()
    {
        $result = UserActivity::getCurrentActivity();
        if (empty($result))
        {
            $adduserActivity = new UserActivity;
            $adduserActivity->user_id = Yii::$app->user->id;
            $adduserActivity->date_activity = date('Y-m-d');
            $adduserActivity->first_activity = time();
            $adduserActivity->save();
            if ($adduserActivity->errors)
            {
                var_dump($adduserActivity->errors);
                exit();
            }
        }
        else
        {
            $updateduserActivity = UserActivity::getCurrentActivity();
            $updateduserActivity->first_activity = time();
            $updateduserActivity->save();
        }
    }

    /**
     * @param $time integer
     */
    public static function updateProductivity($time)
    {
        /**
         * @var $activity UserActivity
         */
        $activity = UserActivity::getCurrentActivity();
        if (! empty($activity)) {
            $activity->productivity_time = $activity->productivity_time + $time;
            $activity->save();
        }
    }

    /**
     *
     */
    public static function logoutUserActivity()
    {
        $activity = UserActivity::getCurrentActivity();
        if (! empty($activity))
        {
            $activity->last_activity = time();
            $activity->time_online = $activity->time_online +  (time() - $activity->first_activity) ;
            $activity->save();
        }
    }

    public static function interactionUserActivity()
    {
        $activity = UserActivity::getCurrentActivity();
        if (! empty($activity))
        {

            $activity->last_activity = time();
            $activity->time_online = $activity->time_online +  (time() - $activity->first_activity) ;
            $activity->first_activity = time();
            $activity->save();
        }
    }

    public function search($params)
    {
        $date_start = '';
        $office = '';
        $agent = '';
        if ($params['start'] != '')
        {
            $start = date('Y-m-d' ,strtotime($params['start'] . ' 0:00'));
            $date_start = "  tbl_user_activity.date_activity >= '" . $start . "'";
        }
        else{
            $start = date('Y-m-d');
            $date_start = "  tbl_user_activity.date_activity >= '" . $start . "'";
        }
        if ($params['end'] != '')
        {
            $end = date('Y-m-d',strtotime($params['end'] . '23:59'));
            $date_start .= "  and tbl_user_activity.date_activity <= '" . $end . "'";
        }
        else{
            $end =  date('Y-m-d');
            $date_start .= "  and tbl_user_activity.date_activity <= '" . $end . "'";
        }
        if ($params['office_id'] != '')
        {
            $office = ' tbl_user.office_id = ' . $params['office_id'];
        }
        if ($params['agent_id'] != '')
        {
            $agent = ' tbl_user.id = ' . $params['agent_id'];
        }
        $useractivity = (new Query())
            ->select(['concat(tbl_user.first_name , " ", tbl_user.last_name, " ", tbl_user.middle_name, "(", tbl_user.username, ")") as name', 'tbl_user.id as user_id', 'tbl_user_activity.date_activity', 'tbl_user_activity.time_online', 'tbl_user_activity.productivity_time', 'tbl_office.code'])
            ->from('tbl_user_activity')
            ->leftJoin('tbl_user', 'tbl_user.id = tbl_user_activity.user_id')
            ->leftJoin('tbl_office','tbl_office.id = tbl_user.office_id')
            ->where($date_start)
            ->andWhere($office)
            ->andWhere($agent)->orderBy('tbl_user_activity.date_activity DESC');

        $dataProvider = new ActiveDataProvider([
            'query' => $useractivity,
        ]);

        return $dataProvider;
    }
    public function searchUserActivity($params)
    {
        $id = $params['user_id'];
        $date_activity_start = strtotime($params['date_activity'] . ' 0:00');
        $date_activity_end = strtotime($params['date_activity'] . ' 23:59');
        $useractivity = (new Query())
            ->select(['lead.id as lead_id','lead.lead_name as lead_name','office.code as code','history.notes', 'history.prod_time', 'concat(user.first_name , " ", user.last_name, " ", user.middle_name) as name','history.entity_id','history.added_at'])
            ->from('tbl_history as history')
            ->leftJoin('tbl_user as user','history.user_id = user.id')
            ->leftJoin('tbl_lead as lead','lead.id = history.entity_id')
            ->leftJoin('tbl_office as office', 'office.id = lead.office_id')
            ->where('history.user_id = ' . $id)
            ->andWhere('history.prod_time > 0')
            ->andWhere('history.added_at >=' . $date_activity_start)
            ->andWhere('history.added_at <= ' . $date_activity_end)
            ->orderBy('history.added_at DESC')

        ;
        $dataProvider = new ActiveDataProvider([
            'query' => $useractivity
        ]);

        return $dataProvider;
    }
}















































































































