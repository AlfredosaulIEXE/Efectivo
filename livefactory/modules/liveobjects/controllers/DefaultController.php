<?php

namespace livefactory\modules\liveobjects\controllers;

use livefactory\controllers\Controller;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}
