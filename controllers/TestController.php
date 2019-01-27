<?php

namespace app\controllers;

use app\models\User;
use yii\web\Controller;

class TestController extends Controller
{
    /**
     * Displays test page.
     *
     * @return string
     */
    public function actionIndex() {

        $models = User::find()->with(User::RELATION_ACCESSED_TASKS)->all();
        _end($models);
        return $this->render('index');
    }


    public function actionInsert() {


    }

    public function actionSelect() {


        return $this->renderContent();
    }

}
