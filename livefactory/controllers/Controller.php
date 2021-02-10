<?php

namespace livefactory\controllers;

use Yii;

/**
 * Controller for LiveCRM system.
 */
class Controller extends \yii\web\Controller
{
    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
       /** Added by LiveObjects Technologies Pvt Ltd for language/timezone change at runtime **/
		Yii::$app->language = Yii::$app->params['LOCALE'];
		Yii::$app->timezone = Yii::$app->params['TIME_ZONE'];
		return true;
    }
}
