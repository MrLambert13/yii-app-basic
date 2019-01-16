<?php

namespace app\controllers;

use yii\web\Controller;

class TestController extends Controller
{
    /**
     * Displays test page.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'testmodel' => \Yii::$app->test->getProperty(),
        ]);
    }
}
