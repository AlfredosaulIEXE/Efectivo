<?php

namespace livefactory\modules\estimate\controllers;

use livefactory\controllers\Controller;

/**
 * Default controller for the `estimate` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
