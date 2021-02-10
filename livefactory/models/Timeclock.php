<?php

namespace livefactory\models;

/**
 * Class Timeclock
 * @package livefactory\models
 *
 * @property int $role_id
 * @property int $office_id
 * @property string $start_time
 * @property string $end_time
 * @property int $week_day
 * @property int $denied
 * @property int $created_at
 * @property int $updated_at
 */
class Timeclock extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_timeclock';
    }
}