<?php

namespace livefactory\modules\pmt\controllers;

use livefactory\controllers\Controller;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}
