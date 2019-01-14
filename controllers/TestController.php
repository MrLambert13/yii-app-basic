<?php

namespace app\controllers;

use app\models\Product;
use app\models\TestModel;
use yii\web\Controller;

/*class A extends BaseObject {
    private $_var;
    public function setVar($value) {
        $this->_var = $value;
    }

    public function getVar() {
        return $this->_var;
    }
}*/

class TestController extends Controller
{
    /**
     * Displays test page.
     *
     * @return string
     */
    public function actionIndex()
    {
        return \Yii::$app->test->run();
        /*$obj = new A();
        $obj->var = 123;
        return $obj->var ;*/

        $model = new TestModel();
        $model->name = 'Бердников Сергей Юрьевич';
        $model->birthday = '07/05/1989';
        $model->city = 'Боровичи';
        $model->about = 'Студент GeekBrains';

        $product = new Product(1,'xiaomi mi6','телефоны', 22999.99);

        return $this->render('index', [
            'model' => $model,
            'prod' => $product,
        ]);
    }
}
