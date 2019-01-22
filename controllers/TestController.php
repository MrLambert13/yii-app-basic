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

    public function actionInsert() {
        \Yii::$app->db->createCommand()->batchInsert(
            'user',
            ['username', 'password_hash', 'auth_key', 'creator_id', 'updater_id', 'created_at', 'updated_at'],
            [
                ['user_1', 'hash_1', 'auth_1', 11, 111, time(), time()],
                ['user_2', 'hash_2', 'auth_2', 22, 222, time(), time()],
                ['user_3', 'hash_3', 'auth_3', 33, 333, time(), time()],
            ]
        );
    }

    public function actionSelect() {

    }
}
