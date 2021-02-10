<?php

namespace livefactory\models;
use livefactory\models\Payment;
use livefactory\models\FileModel;
use Yii;
use yii\filters\VerbFilter;
use yii\db\Query;
use livefactory\models\Lead;

use livefactory\models\LeadStatus;

class PaymentModel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '';
    }
    public static function paymentInsert($entity_id,$entity_type) {

        $statuslead = [1=> 'Nuevo',2=>'Cita',3=>'UPS',4=>'Venta',5=>'Reciclado',6=>'Muerto'];
        $addPayment = new Payment();
        $amount_insert = str_replace(array('$', ' ', ','), '', $_REQUEST['amount']);
        //$addPayment->amount=(string)$_REQUEST['amount'];
        $addPayment->amount = $amount_insert;
        $addPayment->note = $_REQUEST['note'];
        $addPayment->type = $_REQUEST['type'];
        $addPayment->date = date("Y-m-d", strtotime($_REQUEST['date']));
        $addPayment->code = $_REQUEST['code'];
        $addPayment->received = $_REQUEST['received'];
        $addPayment->origin = $_REQUEST['origin'];
        $addPayment->entity_id = $entity_id;
        $addPayment->entity_type = $entity_type;
        $addPayment->file_id = 0;
        $addPayment->added_at = strtotime(date('Y-m-d H:i:s'));
        $addPayment->total_due;
        $lead = Lead::findOne($entity_id);
        $addPayment->folio = $lead->payment_folio + 1;
        $lead->payment_folio = $addPayment->folio;
        if ($addPayment->type == 6 or $addPayment->type == 7)
            $addPayment->amount = '-' . $amount_insert;

        if ($addPayment->origin == 1)
        {
            $addPayment->status = 1;
        }
        else
            $addPayment->status = 0;
        // Generators
        $addPayment->generator_id = (int) $_REQUEST['generator_id'];
        $addPayment->co_generator_id = (int) $_REQUEST['co_generator_id'];

        // Payments
        $payment = Payment::find()->where(['entity_id' => $entity_id])->all();
        $total = 0;
        foreach ($payment as $pay){
            if ($pay->type != 5)
            $total += $pay->amount;
        }
        // No generator?
        if (empty($addPayment->generator_id)) {
            $addPayment->generator_id = $lead->lead_owner_id;
        }

        // Co generator
        if ( ! empty($addPayment->generator_id) && ($addPayment->generator_id == $addPayment->co_generator_id)) {
            $addPayment->co_generator_id = null;
        }
        $amount = str_replace(array('$', ' ', ','), '', $_REQUEST['amount']);
        $total += $amount;
        if ($total >= 3000 && $addPayment->type == 2) {
            $addPayment->type = 1;
        }


        if ($addPayment->type == Payment::_NEW_CONTRACT) {
            $newstatus=4;
            if($lead->lead_status_id != $newstatus)
            HistoryModel::historyInsert($entity_type,$entity_id,'Se cambio el status de lead de <strong>'. $statuslead[$lead->lead_status_id].'</strong> a <strong>'.$statuslead[$newstatus].'</strong> en automático');

            $lead->lead_status_id = $newstatus;
            $lead->payed = 1;
            // Generate
            if (empty($lead->c_contract)) {
                $lead->c_contract = Lead::generateContract($lead->product_id);
                $lead->converted_at = time();
            }

            // Update all dates
            $connection = Yii::$app->db;
            $sql = "UPDATE tbl_appointment SET status = 1 WHERE entity_id = " . $lead->id;
            $command = $connection->createCommand($sql);
            $command->execute();
        }
        $addPayment->total_due = $lead->loan_commission - $total;
        $addPayment->save();
        $aid = $addPayment->id;

        // Save lead only if payment is saved
        if ( ! empty($aid)) {
            $lead->save();
        }

        $fid=FileModel::fileInsert($aid, 'lead.payment');
        $addPayment->file_id = $fid;
        //$addPayment->amount=(string)$addPayment->amount;
        $addPayment->save();
        return $aid;
    }
    public static function paymentUpdate($id) {

        //payment origin
        $payment_origin = [
            1 => 'Efectivo',
            2 => 'Transferencia',
            3 => 'Depósito Bancario',
            4 => 'Tarjeta de Crédito/Débito'
        ];
        //payment status
        $payment_status = [
            0 => 'Por validar',
            1 => 'Validado',
            2 => 'Declinado'
        ];
        //payment edit
        $editPayment = Payment::findOne($id);
        //Lead
        $lead = Lead::findOne($editPayment->entity_id);
        $payment_types = Payment::getTypes();
        //text payments
        $payment_text = '';
        // Payments
        $payment = Payment::find()->where(['entity_id' => $editPayment->entity_id])->all();
        //amount new to payment
        $amountedit = str_replace(array('$', ' ', ','), '', $_REQUEST['amount']);
        //total payments in lead
        $total = 0;
        foreach ($payment as $pay){
            if ($pay->folio <= $editPayment->folio)
                if ($pay->type != 5)
            $total += $pay->amount;
        }
        //text for all changes in payment
        if ($editPayment->amount != $amountedit)
        {
            $payment_text .= ' Cantidad: <strong>$'.number_format($editPayment->amount, 2).'</strong> a <strong>$'.number_format($amountedit  , 2).'</strong> , ';
        }
        if ($editPayment->type != $_REQUEST['type'])
        {
            $payment_text .= ' Tipo de pago: <strong>'. $payment_types[$editPayment->type] . '</strong> a <strong> '. $payment_types[$_REQUEST['type']] . '</strong> , ';
        }
        if ( date('d/m/Y' ,strtotime($editPayment->date)) != date('d/m/Y' , strtotime($_REQUEST['date'])) )
        {
            $payment_text .= ' Fecha: <strong>' . date('d/m/Y' ,strtotime($editPayment->date)) . '</strong> a <strong> ' . date('d/m/Y' , strtotime($_REQUEST['date'])) . '</strong> , ' ;
        }
        if ($editPayment->note != $_REQUEST['note'])
        {
            $payment_text .= ' Nota: <strong>' . $editPayment->note . '</strong> a <strong>' . $_REQUEST['note'] . '</strong> , ' ;
        }
        if ($editPayment->code != $_REQUEST['code'])
        {
            $payment_text .= ' Código: <strong>' . $editPayment->code . '</strong> a <strong> ' . $_REQUEST['code'] . '</strong> , ';
        }
        if ($editPayment->generator_id != $_REQUEST['generator_id'])
        {
            $generator = User::findOne($editPayment->generator_id);
            $newgenerator = User::findOne($_REQUEST['generator_id']);
            $payment_text .= ' Generador: <strong>' . $generator->first_name . ' ' . substr($generator->middle_name, 0 , 1) . ' ' . substr($generator->last_name , 0 , 1). '</strong> a <strong>' . $newgenerator->first_name . ' ' . substr($newgenerator->middle_name, 0 , 1) . ' ' . substr($newgenerator->last_name , 0 , 1).' </strong> , ';
        }
        if ($editPayment->origin != $_REQUEST['origin'])
        {
            $payment_text .= ' Origen del pago: <strong>' . $payment_origin[$editPayment->origin] .' </strong> a <strong>' . $payment_origin[$_REQUEST['origin']] . '</strong> , ';
        }
        if ($editPayment->co_generator_id != $_REQUEST['co_generator_id'])
        {
            $co_generator = User::findOne($editPayment->co_generator_id);
            $co_generator_new = User::findOne($_REQUEST['co_generator_id']);
            $payment_text .= ' Co-generador: <strong>' . $generator->first_name . ' ' . substr($generator->middle_name, 0 , 1) . ' ' . substr($generator->last_name , 0 , 1).'</strong>';
        }

        // file changes
        if(!empty($_FILES['attach']['name'])) {
            $u_file = File::find()->where(['entity_id' => $id])->one();
            if ($u_file) {
                $ext = explode('/', $u_file->file_type);
                $u_path = './'.$u_file->file_path.'/'.$u_file->id.'.'.$ext[1];
                unlink($u_path);
                $payment_text .= ' Archivo: <strong>' . $u_file->file_name . '</strong> a <strong>' . $_FILES['attach']['name'] . '</strong> , ';
                File::findOne($u_file->id)->delete();
            }
            $fid=FileModel::fileInsert($id, 'lead.payment');
            $editPayment->file_id = $fid;
        }
        $newStatus = isset($_REQUEST['payment_status']) ? $_REQUEST['payment_status'] : $editPayment->status;
        //
        $editPayment->amount = $amountedit;
        $editPayment->total_due = $lead->loan_commission - $total;
        $editPayment->note = $_REQUEST['note'];
        $editPayment->type = $_REQUEST['type'];
        $editPayment->date = date("Y-m-d", strtotime($_REQUEST['date']));
        $editPayment->code = $_REQUEST['code'];
        $editPayment->received = $_REQUEST['received'];
        $editPayment->origin = $_REQUEST['origin'];
        $editPayment->updated_at = strtotime(date('Y-m-d H:i:s'));
        // Generators
        $editPayment->generator_id = (int) $_REQUEST['generator_id'];
        $editPayment->co_generator_id = (int) $_REQUEST['co_generator_id'];
        $editPayment->status = $newStatus;
        // No generator?
        if (empty($editPayment->generator_id)) {
            $editPayment->generator_id = $lead->lead_owner_id;
        }

        // Co generator
        if ( ! empty($editPayment->generator_id) && ($editPayment->generator_id == $editPayment->co_generator_id)) {
            $editPayment->co_generator_id = null;
        }
        if ($editPayment->status == null) {
            $editPayment->status = $editPayment->origin == 1 ? Payment::VALIDATED : Payment::UNVALIDATED;
        }
        // Status
        if (Yii::$app->user->can('Payment.Validate')) {
            if ($editPayment->origin == 1)
            {
                $editPayment->status = Payment::VALIDATED;
            }
            else
                if ( ! isset($_REQUEST['payment_status']))
                $editPayment->status = 0;

            if ($editPayment->status != $newStatus)
            {
                $payment_text .= ' Estado: <strong>' . $payment_status[$editPayment->status] . '</strong> a <strong>' . $payment_status[$newStatus] . '</strong>';

            }
        }
        HistoryModel::historyInsert('lead',$lead->id ,'Pago actualizado ' . $payment_text, 0 , UserActivity::FIVE_MINUTES);
         $editPayment->update();
        $aid=$editPayment->id;
        return $aid;
    }
}
