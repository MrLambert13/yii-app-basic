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
        \Yii::$app->db->createCommand()->insert('user', [
            'username' => 'user_1',
            'password_hash' => 'hash_1',
            'auth_key' => 'auth_1',
            'creator_id' => 11,
            'updater_id' => 111,
            'created_at' => time(),
            'updated_at' => time(),
        ])->execute();
        \Yii::$app->db->createCommand()->insert('user', [
            'username' => 'user_2',
            'password_hash' => 'hash_2',
            'auth_key' => 'auth_2',
            'creator_id' => 2,
            'updater_id' => 222,
            'created_at' => time(),
            'updated_at' => time(),
        ])->execute();

        /**
         * 5) В экшене insert TestController-а через Yii::$app->db->createCommand()->batchInsert()
         * вставить одним вызовом сразу 3 записи в таблицу task, в поле creator_id подставив
         * реальное значение id из user (просто числом).
         */
        $rows = \Yii::$app->db->createCommand()->batchInsert(
            'task',
            ['title', 'description', 'creator_id', 'updater_id', 'created_at', 'updated_at'],
            [
                ['title_1', 'description_1', 11, 111, time(), time()],
                ['title_2', 'description_22', 2, 111, time(), time()],
                ['title_3', 'description_333', 22, 111, time(), time()],
            ]
        )->execute();
        return $this->renderContent(($rows > 0) ? 'Tasks was added' : 'Error add tasks');

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

        //Сборка результата
        $result = VarDumper::dumpAsString($result_a, 5, true)
            . '<hr>' . VarDumper::dumpAsString($result_b, 5, true)
            . '<hr>' . 'Количество записей всего:'
            . VarDumper::dumpAsString($result_c, 5, true);

        /**
         * 6) Используя \yii\db\Query в экшене select TestController-а вывести содержимое task
         * с присоединенными по полю creator_id записями из таблицы user (innerJoin())
         */
        $query_d = new Query();
        $result_d = $query_d
            ->from('task')
            ->innerJoin('user', 'user.creator_id = task.creator_id')
            ->all();

        //Сборка результата
        $result .= '<hr>' . 'Содержимое task (inner join, creator_id =>[11, 22, 2]):'
            . VarDumper::dumpAsString($result_d, 5, true);

        return $this->renderContent($result);
    }

}
