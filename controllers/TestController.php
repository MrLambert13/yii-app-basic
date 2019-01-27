<?php

namespace app\controllers;

use app\models\Task;
use app\models\User;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\VarDumper;
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
