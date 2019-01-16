<?php

namespace app\controllers;

use app\models\Product;
use app\models\TestModel;
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

        $model = new TestModel();
        $model->name = 'Бердников Сергей Юрьевич';
        $model->birthday = '07/05/1989';
        $model->city = 'Боровичи';
        $model->about = 'Студент GeekBrains';

        $product = new Product();

        return $this->render('index', [
            'model' => $model,
            'prod' => $product,
            'testmodel' => \Yii::$app->test->getProperty(),
        ]);
    }
}
