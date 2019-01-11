<?php

namespace app\controllers;

use yii\web\Controller;


class TestController extends Controller
{
    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionIndex()
    {
//        return $this->render('about');
        return $this->renderContent('qwe');
    }
}
