<?php

namespace app\controllers;

use app\models\Task;
use app\models\User;
use Yii;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

class TestController extends Controller
{
    /**
     * Displays test page.
     * @return string
     * @throws ForbiddenHttpException
     */
    public function actionIndex() {
        $id = 1;
        $task = Task::findOne($id);
        $currentUserId = Yii::$app->user->id;

        $users = $task->getTaskUsers();

        _end($users);
    }


    public function actionInsert() {


    }

    public function actionSelect() {


        return $this->renderContent();
    }

}
