<?php

namespace livefactory\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "{{%tbl_lead_status}}".
 *
 * @property integer $id
 * @property string $status
 * @property string $label
 * @property integer $active
 * @property integer $sort_order
 * @property integer $added_at
 * @property integer $updated_at
 */
class LeadStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

	const _NEW = '1';
	const _INPROCESS = '2'; 
	const _OPPORTUNITY = '3'; 
	const _CONVERTED = '4'; 
	const _RECYCLED = '5';
	const _DEAD = '6';
	const _FROZE = '7';
	const _NOCALL = '8';
	const _TRACKING = '9';
	const _WELCOME = '10';

	const _WELCOME_CALL = '1';
	const _FIRST_DATE = '2';
	const _SIGNATURE = '3';
	const _INSURANCE = '4';
	const _SUPPORT_INCOME = '5';
	const _FINANCIAL_EVALUATION = '6';
	const _BANK_EVALUATION = '7';
	const _DECLINED_BY_ONE_INSTITUTION = '8';
	const _DECLINED_BY_MORE_INSTITUTION = '9';
	const _REQUEST_GUARANTEE = '10';
	const _CLIENT_CANCELLATION = '11';
	const _REFUND = '12';
	const _APPROVED_CREDIT = '13';
	const _EXERCISE_CREDIT = '14';
	const _PROFECO = '15';
	const _PROFECO_AGREEMENT = '16';
	const _FISCALIA = '17';

	// Master status
	const _MASTER_SALES = 1;
	const _MASTER_INSURANCE = 2;
	const _MASTER_SERVICE = 3;

    /**
     * Customer service statuses
     *
     * @var array
     */
	public static $_customer_service_status = [
	    0 => 'Nuevo',
	    1 => 'Llamada de Bienvenida',
        2 => 'Primer Cita',
        3 => 'Firmo Solicitudes',
        4 => 'En Seguros',
        5 => 'Soportará Ingresos',
        6 => 'En Evaluación de Financiera',
        7 => 'En Evaluación de Banco',
        8 => 'Declinada por una Institución',
        9 => 'Declinada por más de una Institución',
        10 => 'Se solicitó Aval y/o Garantía',
        11 => 'Cliente Solicita Cancelación',
        12 => 'Devolución',
        13 => 'Crédito Aprobado',
        14 => 'Crédito Ejercido',
        15 => 'En Profeco',
        16 => 'Convenio Profeco',
        17 => 'Fiscalía'
    ];

    /**
     * @param $status_id
     * @return mixed|string
     */
	public static function getServiceStatusName($status_id) {
	    $status_id = (int) $status_id;

	    return isset(LeadStatus::$_customer_service_status[$status_id]) ? LeadStatus::$_customer_service_status[$status_id] : 'Unknown';
    }

    /**
     * @param $model
     * @return string
     * @throws \Exception
     */
    public static function getStatusLabel($model)
    {
        /*if ( $model->valid_sales == 1 && $model->valid_manager == 1 && $model->valid_admin == 1 ) {
            return '<span class="label label-primary">ATENCIÓN A CLIENTES</span>';
        } else {
            return '<span class="label label-info">VENTAS</span>';
        }*/
        if ($model->lead_master_status_id == LeadStatus::_MASTER_SALES) {
            $label = '<span class="label label-info">VENTAS</span>';

            if ( $model->lead_status_id == LeadStatus::_CONVERTED) {

                $current = new \DateTime();
                $converted = new \DateTime(date('Y-m-d', $model->converted_at));
                $diff = $current->diff($converted);

                //return $converted->format('d-m-Y');

                $days = (int) $diff->format('%a');
                $limit = 15;
                $remaining = $limit - $days;
                $remaining = $remaining > 0 ? $remaining : 0;

                if ($remaining > 8)
                    $classes = 'label-primary';
                elseif ($remaining > 3) {
                    $classes = 'label-warning blink';
                } else {
                    $classes = 'label-danger blink';
                }

                $label .= '<span class="label transparent"> - </span><span class="label '.$classes.'" data-toggle="tooltip" title="Días restantes para vender seguro."> Restan ' . $remaining .  ' días</span>';
            }

            return $label;
        } else if ($model->lead_master_status_id == LeadStatus::_MASTER_INSURANCE) {
            return '<span class="label label-warning">SEGUROS</span>';
        } else {
            return '<span class="label label-primary">ATENCIÓN A CLIENTES</span>';
        }
    }

    /**
     * @param $model
     * @return bool
     */
    public static function isMigrated($model)
    {
        return $model->lead_master_status_id == LeadStatus::_MASTER_SERVICE;
    }

    public static function customerManage($model)
    {
        return $model->lead_status_id == LeadStatus::_CONVERTED && Yii::$app->user->can('Review.Index');
    }

    /**
     * @param $model
     * @return bool
     */
    public static function canMigrate($model) {

        if ($model->lead_status_id == LeadStatus::_CONVERTED) {

            if ( ! LeadStatus::isMigrated($model) && Yii::$app->user->can('Lead.Migrate'))
                return true;

            /*if (Yii::$app->user->can('Role.Sales') && ! $model->valid_sales)
                return true;

            if (Yii::$app->user->can('Role.Admin') && ! $model->valid_admin)
                return true;

            if (Yii::$app->user->can('Role.Manager') && !$model->valid_manager)
                return true;
            */
        }

        return false;
    }
    public static function caMigrateInsurance($model){
        if ((Yii::$app->user->can('Insurance.Director')) || (Yii::$app->user->can('Audit.Member')) || (Yii::$app->user->can('Admin'))){
            return true;
        }
        else
            return false;
    }

    public  static  function caMigrateInsuranceForm($model){
        if ($model->insurance_agent != null)
            return true;
        else
            return false;
    }

    /**
     * @param $model
     * @param bool $update
     * @return bool
     */
    public static function canChange($model, $update = false) {
        // TODO: Check this
        return true;
	    $is_validated = $model->valid_sales == 1 && $model->valid_admin = 1 && $model->valid_manager == 1;

	    if ($update) {
	        if ((Yii::$app->user->can('Lead.Update') && ! $is_validated) || Yii::$app->user->can('Role.Manager')) {
	            return true;
            }
        } else {
            if (Yii::$app->user->can('Role.Manager') && $model->lead_status_id == LeadStatus::_CONVERTED && $is_validated ) {
                return true;
            }
        }

        return false;
    }

    public static function tableName()
    {
        return '{{%tbl_lead_status}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'label', 'active', 'sort_order'], 'required'],
            [['active', 'sort_order', 'added_at', 'updated_at'], 'integer'],
            [['status', 'label'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'status' => Yii::t('app', 'Status'),
            'label' => Yii::t('app', 'Label'),
            'active' => Yii::t('app', 'Active'),
            'sort_order' => Yii::t('app', 'Sort Order'),
            'added_at' => Yii::t('app', 'Added At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

	public function beforeSave($insert) {
		$this->status = Html::encode($this->status);
		$this->label = Html::encode($this->label);
		return parent::beforeSave($insert);
	}
}
