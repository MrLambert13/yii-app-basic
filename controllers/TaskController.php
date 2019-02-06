<?php

namespace app\controllers;

use app\models\TaskUser;
use Yii;
use app\models\Task;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TaskController implements the CRUD actions for Task model.
 */
class TaskController extends Controller
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
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Task models.
     * @return mixed
     */
    public function actionMy() {
        /**
         * б) В TaskController cделать экшен my, при создании датапровайдера добавив к $query созданный в пункте "а"
         * метод byCreator($userId) и подставив вместо $userId Id текущего юзера.
         */
        $currentUserId = Yii::$app->user->id;

        $query = Task::find()->byCreator($currentUserId);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('my', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Shared Task models.
     * @return mixed
     */
    public function actionShared() {
        /**
         * а) Создаем экшен shared - список расшаренных задач, экшен отличается от my тем, что к query
         * создаваемому для датапровайдера присоединен с помощью inner join релейшен taskUsers.
         */
        $currentUserId = Yii::$app->user->id;

        $query = Task::find()
            ->byCreator($currentUserId)
            ->innerJoinWith(TaskUser::RELATION_TASK_USERS);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        return $this->render('shared', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Accessed Task models.
     * @return mixed
     */
    public function actionAccessed() {
        /**
         * Создаем экшен accessed - список доступных чужих задач.
         */
        $currentUserId = Yii::$app->user->id;

        $query = Task::find()
            ->innerJoinWith(Task::RELATION_TASK_USERS)
            ->where(['user_id' => $currentUserId]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        return $this->render('accessed', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Task model.
     *
     * @param integer $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws ForbiddenHttpException
     */
    public function actionView($id) {
        $task = $this::findModel($id);
        $currentUserId = Yii::$app->user->id;
        if (!$task || $task->creator_id != $currentUserId) {
            throw new ForbiddenHttpException();
        }

        $users = $task->getSharedUsers();
        $dataProvider = new ActiveDataProvider([
            'query' => $users,
        ]);

        return $this->render('view', [
            'model' => $task,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Task model.
     * If creation is successful, the browser will be redirected to the 'my' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Task();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //Добавляем флэш-сообщения после создания
            Yii::$app->session->setFlash('success', 'Задача успешно создана и сохранена');
            //Меняем редиректы после создания, изменения и удаления на my.
            return $this->redirect(['task/my']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Task model.
     * If update is successful, the browser will be redirected to the 'my' page.
     *
     * @param integer $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws ForbiddenHttpException
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        // Добавляем при изменения и удалении проверку что текущий пользователь создатель задачи, если нет - выбрасываем исключение.
        if (!$model || $model->creator_id != Yii::$app->user->id) {
            throw new ForbiddenHttpException();
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //в) Добавляем флэш-сообщения после изменения
            Yii::$app->session->setFlash('success', 'Задача "' . $model->title . '" была обновлена.');
            //Меняем редиректы после создания, изменения и удаления на my.
            return $this->redirect(['task/my']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Task model.
     * If deletion is successful, the browser will be redirected to the 'my' page.
     *
     * @param integer $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id) {
        $model = $this->findModel($id);
        // Добавляем при изменения и удалении проверку что текущий пользователь создатель задачи, если нет - выбрасываем исключение.
        if (!$model || $model->creator_id != Yii::$app->user->id) {
            throw new ForbiddenHttpException();
        }
        $model->delete();
        //в) Добавляем флэш-сообщения после удаления
        Yii::$app->session->setFlash('warning', 'Задача "' . $model->title . '" была удалена.');
        //Меняем редиректы после создания, изменения и удаления на my.
        return $this->redirect(['task/my']);
    }

    /**
     * Finds the Task model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return Task the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Task::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
