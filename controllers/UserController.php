<?php

namespace app\controllers;

use app\models\Task;
use Yii;
use app\models\User;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex() {
        $dataProvider = new ActiveDataProvider([
            'query' => User::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     *
     * @param integer $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new User();
        $model->creator_id = 1;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * В UserController создать экшен test, в нем используя ActiveRecord, т.е. методы классов User и Task,
     * выполнить последовательно, наблюдая выполняемые запросы в debug-панели:
     *  а) Создать запись в таблице user.
     *  б) Создать три связаные (с записью в user) запиcи в task, используя метод link().
     *  в) Прочитать из базы все записи из User применив жадную подгрузку связанных данных из Task, с запросами без JOIN.
     *  г) Прочитать из базы все записи из User применив жадную подгрузку связанных данных из Task, с запросом содержащим JOIN.
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionTest() {
        //Создать запись в таблице user.
        $newUser = new User();
        $newUser->username = 'Bob';
        $newUser->password_hash = 'kjhjkfhg';
        $newUser->created_at = time();
        $newUser->creator_id = User::findOne(['username' => 'Admin'])->getAttribute('id');
        $newUser->save();
        _log($newUser);

        //Создать три связаные (с записью в user) запиcи в task, используя метод link().
        $description = 'Some description';
        $user = User::findOne(['username' => 'Bob']);

        $task_1 = new Task();
        $task_1->title = 'Bob\'s task #1';
        $task_1->description = $description;
        $task_1->link(Task::RELATION_CREATOR, $user);
        _log($task_1);

        $task_2 = new Task();
        $task_2->title = 'Bob\'s task #2';
        $task_2->description = $description;
        $task_2->link(Task::RELATION_CREATOR, $user);
        _log($task_2);

        $task_3 = new Task();
        $task_3->title = 'Bob\'s task #3';
        $task_3->description = $description;
        $task_3->link(Task::RELATION_CREATOR, $user);
        _log($task_3);

        //Прочитать из базы все записи из User применив жадную подгрузку связанных данных из Task, с запросами без JOIN.
        $models = User::find()->with(User::RELATION_ACCESSED_TASKS)->all();
        _log($models);

        //Прочитать из базы все записи из User применив жадную подгрузку связанных данных из Task, с запросом содержащим JOIN.
        $models = User::find()->joinWith(User::RELATION_ACCESSED_TASKS)->all();
        _log($models);

        //6) Добавить с помощью созданного релейшена связь между записями в User и Task (метод link(), обе модели д.б. сохранены).
        $admin = User::findOne(['username' => 'Admin']);
        $tasks = Task::findAll([6, 7, 8, 9]);
        foreach ($tasks as $task) {
            $admin->link(User::RELATION_ACCESSED_TASKS, $task);
        }

        return $this->renderContent('complete');
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
