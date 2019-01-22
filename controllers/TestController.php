<?php

namespace app\controllers;

use app\models\Product;
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
        $model = new Product(
            ['id' => 13, 'name' => '  <br/>  Name', 'price' => 135, 'created_at' => 132]
        );

        $query = new Query();
        $result = $query->from('product')
            ->where(['id' => [1, 2, 4]])
            ->orderBy(['id' => SORT_DESC])
            ->all();
        _end($result);

        $model->validate();
        /*return $this->render('index', [
            'testmodel' => \Yii::$app->test->getProperty(),
            'validate' => VarDumper::dumpAsString($model->validate(), 3, true),
            'lesson' => VarDumper::dumpAsString($model->safeAttributes(), 5, true),
            'err' => VarDumper::dumpAsString($model->getErrors(), 5, true),
        ]);*/
    }
}
