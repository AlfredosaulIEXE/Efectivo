<?php

namespace livefactory\models;

use livefactory\models\LeadStatus;
use livefactory\models\Appointment;
use livefactory\models\Lead;
use Yii;
use yii\filters\VerbFilter;
use yii\db\Query;
class AppointmentModel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '';
    }
    public static function appointmentInsert($entity_id,$entity_type) {

        $appointment = Appointment::find()->where('entity_id = ' . $entity_id . ' and date = "' . date("Y-m-d", strtotime($_REQUEST['date'])) . '"')->one();
        // Appointment not rated
        $total = Appointment::find()->where('entity_id = '.$entity_id.' and status=0')->count();
        if ($total > 0) {
            return false;
        }
        //var type_appointment user appointment global
        $user_appointment_type = 0;
        //useracount
        $userid = Yii::$app->user->id;
        // user rol
        $query = (new Query())->select('tbl_user.id, auth_assignment.item_name')->from('tbl_user')->leftJoin('auth_assignment', 'tbl_user.id = auth_assignment.user_id')->where('tbl_user.id = ' . $userid)->one();
        //Appointment insurance
        if (($query['item_name'] == 'Insurance.Director') || ($query['item_name'] == 'Insurance.Customer') || ($query['item_name'] == 'Insurance'))
        {
            $user_appointment_type = 1;
        }
        //Appointment Customer
        if (($query['item_name'] == 'Customer.Service2') || ($query['item_name'] == 'Customer.Service')  || ($query['item_name'] == 'Customer.Director'))
        {
            $user_appointment_type = 2;
        }
        if ($appointment != null)
        {
            return false;
        }
        // New appointment
        $statuslead = [1=> 'Nuevo',2=>'Cita',3=>'UPS',4=>'Venta',6=>'Muerto',8=>'No Contesta'];
        $addAppointment = new Appointment();
        $addAppointment->description=$_REQUEST['description'];
        $addAppointment->date=date("Y-m-d", strtotime($_REQUEST['date']));
        $addAppointment->time=date("g:i a", strtotime($_REQUEST['time']));
        $addAppointment->status = 0;
        $addAppointment->entity_id = $entity_id;
        $addAppointment->entity_type = $entity_type;
        $addAppointment->user_id = $userid;
        $addAppointment->added_at = time();
        $addAppointment->updated_at = time();
        $addAppointment->appointment_type = $user_appointment_type;
        $addAppointment->type = $_REQUEST['type_appointment'];
        $lead = Lead::findOne($entity_id);
        if ($_REQUEST['amount'] > $lead->loan_commission)
        {
            $addAppointment->amount= $lead->loan_commission;
        }
        else
        {
            $addAppointment->amount = $_REQUEST['amount'];
        }
        //added activity user
        $addAppointment->save();
        if ($lead->lead_status_id == 1) {
            $newstatus=2;
            HistoryModel::historyInsert($entity_type,$entity_id,'Se cambio el status de lead de <strong>'. $statuslead[$lead->lead_status_id].'</strong> a <strong>'.$statuslead[$newstatus].'</strong> en automático');
            $lead->lead_status_id = $newstatus;
            // date to lead in appointment in automatic
            $lead->appointment_date = date('Y-m-d');
            $lead->save();
        }
        $aid=$addAppointment->id;

        return $aid;
    }
    public static function appointmentUpdate($id) {
        $editAppointment = Appointment::findOne($id);
        //array list status
        $statuslead = [1=> 'Nuevo',2=>'Cita',3=>'UPS',4=>'Venta',6=>'Muerto',8=>'No Contesta', 9 => 'Seguimiento', 10 => 'T1', 11 => 'T1NI'];
        $lead = Lead::findOne($editAppointment->entity_id);
        if($lead->lead_status_id==1 || $lead->lead_status_id==2 && $_REQUEST['status'] ==1 )
        {

            if ($editAppointment->type == '0' || $_REQUEST['type_update'] == '0')
            {
                $newstatus=3;
                HistoryModel::historyInsert($editAppointment->entity_type,$editAppointment->entity_id,'Se cambio el status de lead de <strong>'. $statuslead[$lead->lead_status_id].'</strong> a <strong>'.$statuslead[$newstatus].'</strong> en automático');

                $lead->lead_status_id=$newstatus;
                $lead->update();
            }

        }
        /*if ($_REQUEST['type_update'] == 0)
        {
            $editAppointment->type=1;
            $editAppointment->update();
            $_POST['type_appointment']=1;
        }
        else
        {
            $editAppointment->type=0;
            $editAppointment->update();
            $_POST['type_appointment']=0;
        }*/
        if ($_REQUEST['amount'] > $lead->loan_commission)
        {
            $editAppointment->amount = $lead->loan_commission;
        }
        else
        {
            $editAppointment->amount = $_REQUEST['amount'];
        }
        $editAppointment->description=$_REQUEST['description'];
        $editAppointment->date=date("Y-m-d", strtotime($_REQUEST['date']));
        $editAppointment->time=date("g:i a", strtotime($_REQUEST['time']));
        $editAppointment->status = $_REQUEST['status'];
        $editAppointment->updated_at=time();
        $editAppointment->update();
        $aid=$editAppointment->id;
        return $aid;
    }

    /**
     * @return int
     */
    public static function appointmentsUps()
    {
        $appointments = Appointment::find()->where('status = 1 and type = 0')->groupBy('entity_id')->orderBy('entity_id ASC');
        $count = 0;
        //connection to db
        $connection = \Yii::$app->db;
        foreach ($appointments->all() as $appointment)
        {
            $lead = Lead::findOne($appointment->entity_id);
            if ($lead != null)
            {
                if ($appointment->updated_at != null)
                {
                    $ups_date = date('Y-m-d',$appointment->updated_at);
                }
                else{
                    $ups_date = date('Y-m-d',$appointment->added_at);
                }
                $connection->createCommand("UPDATE tbl_lead SET ups_type = " . $appointment->type ." , ups_date = '" . $ups_date . "' WHERE id = " . $lead->id)->execute();
                        $count++;

            }



        }
        return $count;
    }

    /**
     * @throws \yii\db\Exception
     */
    public static function appointmentsUpdated()
    {
        $current_date = date("Y-m-d");
        $start = $current_date . " 00:00";
        $end = $current_date . " 23:59";
//        var_dump($current_date , strtotime($start), $end);
//        $connection = \Yii::$app->db;
//        $sql= "update tbl_appointment set status='-1', updated_at =" . time() . " where status='0' and date<'".$current_date."'";
//        $connection->createCommand($sql)->execute();
        $appointments = Appointment::find()->where('date = "' . $current_date . '" and status = 0')->all();
        foreach ($appointments as $appointment)
        {
//            var_dump($appointment->entity_id, $appointment->date, $appointment->time, $appointment->status);
            $history = History::find()->where('entity_id = ' . $appointment->entity_id . ' and added_at >= ' . strtotime($start) . ' and added_at <= ' . strtotime($end) )->all();
            if (empty($history))
            {

//                var_dump('not history today');
//                var_dump('not concreted 2');
                $appointment->status = 2;
                $appointment->save();
            }
            else
            {
                $appointment->status = -1;
                $appointment->save();
//                var_dump($history);
//                var_dump('vencida -1');
            }

        }
    }

}
