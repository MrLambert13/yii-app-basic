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

        $user = new User();
        $user->username = 'Test name';
        $user->password_hash = 'qweqwe';
        $user->save();

        _end($user);
        return $this->render('index');
    }


    public function actionInsert() {


    }

    public function actionSelect() {


        return $this->renderContent();
    }

}
