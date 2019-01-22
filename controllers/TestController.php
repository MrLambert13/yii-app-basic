<?php

namespace app\controllers;

use app\models\Product;
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

        return $this->render('index');
    }

    public function actionInsert() {
        /**
         * 3) В экшене insert TestController-а через Yii::$app->db->createCommand()->insert()
         * вставить несколько записей в таблицу user, в поле password_hash можно вставить
         * произвольные значения, поле id заполняется автоматически.
         */
        $rows = \Yii::$app->db->createCommand()->batchInsert(
            'user',
            ['username', 'password_hash', 'auth_key', 'creator_id', 'updater_id', 'created_at', 'updated_at'],
            [
                ['user_7', 'hash_7', 'auth_7', 77, 777, time(), time()],
                ['user_8', 'hash_8', 'auth_8', 88, 888, time(), time()],
                ['user_9', 'hash_9', 'auth_9', 99, 999, time(), time()],
            ]
        )->execute();
        return $this->renderContent(($rows > 0) ? 'Data was added' : 'Error');

    }

    public function actionSelect() {
        /**
         * Используя \yii\db\Query в экшене select TestController выбрать из user:
         * а) Запись с id=1
         */
        $query_a = new Query();
        $result_a = $query_a
            ->from('user')
            ->where(['id' => 1])
            ->one();

        /**
         * б) Все записи с id>1 отсортированные по имени (orderBy())
         */
        $query_b = new Query();
        $result_b = $query_b
            ->from('user')
            ->where(['>', 'id', 1])
            ->orderBy('username', SORT_ASC)
            ->all();

        /**
         * в) количество записей.
         */
        $query_c = new Query();
        $result_c = $query_c
            ->from('user')
            ->count();

        //Вывод результата
        $result = VarDumper::dumpAsString($result_a, 5, true)
            . '<hr>' . VarDumper::dumpAsString($result_b, 5, true)
            . '<hr>' . 'Количество записей всего:'
            . VarDumper::dumpAsString($result_c, 5, true);

        return $this->renderContent($result);
    }

}
