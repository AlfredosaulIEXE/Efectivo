<?php

namespace livefactory\models;

use livefactory\models\search\CommonModel;
use yii\helpers\ArrayHelper;
use yii\db\ActiveRecord;

class SalesReport extends ActiveRecord {


    public static function getCurrentWeek()
    {
        date_default_timezone_set(\Yii::$app->params['TIME_ZONE']);
        $start = date('Y-m-d', strtotime('monday this week'));
        $end = date('Y-m-d', strtotime('saturday this week'));

        return [$start, $end];
    }

    /**
     * @param $request
     * @return array
     */
    public static function getPeriodFromRequest($request)
    {
        $start = $request['start'];
        $end = $request['end'];
        if (empty($start) || empty($end)) {

            if ($request['r'] == 'sales/lead/appointments')
            {
                
                $current_date = date("Y-m-d");
                $start = $current_date;
                $end = $current_date;
            }
            else
            list($start, $end) = SalesReport::getCurrentWeek();
        } else {

            $start = date_create_from_format('d/m/Y', $start)->format('Y-m-d');
            $end = date_create_from_format('d/m/Y', $end)->format('Y-m-d');
        }

        return [$start, $end];
    }

    /**
     * @param $start
     * @param $end
     * @return \DatePeriod
     * @throws \Exception
     */
    //period fix bug in view for all
    public static function getPeriod($start, $end) {
        $start_s = $start . ' 00:00:00';
        $end_s = $end . ' 23:59:59';
        $period = new \DatePeriod(
            new \DateTime($start_s),
            new \DateInterval('P1D'),
            new \DateTime($end_s)
        );

        return $period;
    }

    /**
     * @param $start
     * @param $end
     * @throws \Exception
     */
    public static function getStats($start, $end)
    {
        $office_id = \Yii::$app->request->getQueryParam('office_id');
        $agent_id = \Yii::$app->request->getQueryParam('agent_id');
        $period = SalesReport::getPeriod($start, $end);
        $means = SalesReport::getMeans();
        $stats = [];
        $days = [];
        $today = false;
        $prev_day = null;

        foreach ($period as $date) {
            $prev_day = isset($day) ? $day : null;
            $day = $date->format('Y-m-d');
            $today = $today == false && $prev_day != null ? $prev_day == date('Y-m-d') : $today;
            $days[$day] = $date;

            //
            $_start = $date->format('Y-m-d');
            $_end = $date->format('Y-m-d');

            //
            $stats[$day]['leads'] = CommonModel::getAllLeads($_start, $_end, $office_id, $agent_id);
            $stats[$day]['appointments'] = CommonModel::getAllAppointments($_start, $_end, $office_id, $agent_id);
            foreach ($means as $mean_id => $mean) {
                $stats[$day]['ups'][$mean_id] = CommonModel::getAllOpportunities($_start, $_end, $office_id, $agent_id, $mean_id, true);
            }

            $stats[$day]['re_appointments'] = CommonModel::getReAppointments($_start, $_end, $office_id, $agent_id);
            $stats[$day]['increments'] = CommonModel::getAllIncrements($_start, $_end, $office_id, $agent_id);

            $stats[$day]['contracts'] = CommonModel::getAllConverted($_start, $_end, $office_id, $agent_id);
            $stats[$day]['amount'] = (float) CommonModel::getAmount($_start, $_end, $office_id, $agent_id);
            $stats[$day]['sales'] = (float) CommonModel::getCommission($_start, $_end, $office_id, $agent_id);
            $stats[$day]['new_contract'] = (float) CommonModel::getSales($_start, $_end, $office_id, $agent_id, null, Payment::_NEW_CONTRACT);
            $stats[$day]['advance'] = (float) CommonModel::getSales($_start, $_end, $office_id, $agent_id, null, Payment::_ADVANCE);
            $stats[$day]['addemdums'] = (float) CommonModel::getSales($_start, $_end, $office_id, $agent_id, null, Payment::_ADDENDUMS);
            $stats[$day]['increase'] = (float) CommonModel::getSales($_start, $_end, $office_id, $agent_id, null, Payment::_INCREASE);
            $stats[$day]['income'] = $stats[$day]['new_contract'] + $stats[$day]['advance'] + $stats[$day]['addemdums'] + $stats[$day]['increase'];
            $stats[$day]['addendums2'] = (float) CommonModel::getAddemdums($_start, $_end, $office_id, $agent_id);
            $stats[$day]['addendums3'] = $today ? 0 : (float) CommonModel::getTotalAddemdums($office_id, $agent_id);
        }

        //var_dump($stats);

        return [
            $days, $stats
        ];
    }

    /**
     * @return array
     */
    protected static function getMeans()
    {
        // Means
        $leadSource = array();
        foreach (ArrayHelper::map(LeadSource::find()->where("active=1")->orderBy('sort_order')->asArray()->all(), 'id', 'label') as $key => $ld) {
            $leadSource[$key] = $ld;
        }

        return $leadSource;
    }
}