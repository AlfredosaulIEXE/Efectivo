<?php

namespace livefactory\models;
use Yii;
use yii\filters\VerbFilter;
use yii\db\Query;
class TimeDiffModel extends \yii\db\ActiveRecord
{
	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '';
    }
    public static function dateDiff($time1, $time2, $precision = 6) {
		// If not numeric then convert texts to unix timestamps
		if (!is_int($time1)) {
		  $time1 = strtotime($time1);
		}
		if (!is_int($time2)) {
		  $time2 = strtotime($time2);
		}
	 
		// If time1 is bigger than time2
		// Then swap time1 and time2
		if ($time1 > $time2) {
		  $ttime = $time1;
		  $time1 = $time2;
		  $time2 = $ttime;
		}
	 
		// Set up intervals and diffs arrays
		$intervals = array('year','month','day','hour','minute','second');
		$diffs = array();
	 
		// Loop thru all intervals
		foreach ($intervals as $interval) {
		  // Create temp time from time1 and interval
		  $ttime = strtotime('+1 ' . $interval, $time1);
		  // Set initial values
		  $add = 1;
		  $looped = 0;
		  // Loop until temp time is smaller than time2
		  while ($time2 >= $ttime) {
			// Create new temp time from time1 and interval
			$add++;
			$ttime = strtotime("+" . $add . " " . $interval, $time1);
			$looped++;
		  }
		  $time1 = strtotime("+" . $looped . " " . $interval, $time1);
		  $diffs[$interval] = $looped;
		}
		
		$count = 0;
		$times = array();
		// Loop thru all diffs
		foreach ($diffs as $interval => $value) {
		  // Break if we have needed precission
		  if ($count >= $precision) {
	break;
		  }
		  // Add value and interval
		  // if value is bigger than 0
		  if ($value > 0) {
	// Add s if value is not 1
	if ($value != 1) {
	  $interval .= "s";
	}
	// Add value and interval to times array
	$times[] = $value . " " . $interval;
	$count++;
		  }
		}
	 
		// Return string with times
		return implode(", ", $times);
	  }
	public static function getTimeDiff($start,$end){
		$hours=$day=$minutes=$second=0;
		$timing=explode(',',static::dateDiff($end,$start));
		foreach($timing as $value){
			if(strpos($value,'day') !== false){
				$day=trim(str_replace('day','',$value));
			}
			if(strpos($value,'hour') !== false){
				$hours=trim(str_replace('hours','',$value));
			}
			if(strpos($value,'hour') !== false){
				$hours=trim(str_replace('hours','',$value));
			}
			if(strpos($value,'minutes') !== false){
				$minutes=trim(str_replace('minutes','',$value));
			}
			if(strpos($value,'minute') !== false){
				$minutes=trim(str_replace('minute','',$value));
			}
			if(strpos($value,'minute') !== false){
				$minutes=trim(str_replace('minutes','',$value));
			}
			if(strpos($value,'seconds') !== false){
				$second=trim(str_replace('seconds','',$value));
			}
			if(strpos($value,'second') !== false){
				$second=trim(str_replace('second','',$value));
			}
		}
		$hours = ($day*24)+$hours;
		return $hours.".".$minutes.".".$second;
	}
}
