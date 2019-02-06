<?php

namespace app\controllers;

use app\models\Task;
use app\models\User;
use Throwable;
use Yii;
use app\models\TaskUser;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TaskUserController implements the CRUD actions for TaskUser model.
 */
class TaskUserController extends Controller
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
                    'delete-all' => ['POST'],

                ],
            ],
        ];
    }

    /**
     * Creates a new TaskUser model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @var $taskId integer
     * @return mixed
     * @throws ForbiddenHttpException
     */
    public function actionCreate($taskId) {
        //Сделать проверку автора заметки при создании доступа.
        $task = Task::findOne($taskId);
        if (!$task || $task->creator_id != Yii::$app->user->id) {
            throw new ForbiddenHttpException();
        }

        $model = new TaskUser();
        $model->task_id = $taskId;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //Добавляем флэш-сообщения после создания и удаления доступа.
            Yii::$app->session->setFlash('success', 'Доступ к задаче добавлен');
            //Меняем редиректы после создания и удаления на /task/shared.
            return $this->redirect(['task/shared']);
        }

        //Запрос пользователей кто не является создателем задачи, и не расшаренным.
        $currentUser = Yii::$app->user->id;
        $sharedUsers = TaskUser::find()->where(['task_id' => $taskId])->select('user_id')->column();
        $users = User::find()
            ->where(['not in', 'id', $sharedUsers])
            ->andWhere(['<>', 'id', $currentUser])
            ->select('username')
            ->indexBy('id')
            ->column();

        return $this->render('create', [
            'model' => $model,
            'users' => $users,
        ]);
    }

    /**
     * Unshared task for all users.
     * @var $taskId integer
     * @return mixed
     * @throws ForbiddenHttpException
     */
    public function actionDeleteAll($taskId) {
        //Сделать проверку прав доступа.
        $task = Task::findOne($taskId);
        if (!$task || $task->creator_id != Yii::$app->user->id) {
            throw new ForbiddenHttpException();
        }

        $task->unlinkAll(Task::RELATION_TASK_USERS, true);

        Yii::$app->session->setFlash('warning', 'Успешно удалили доступ к задаче');

        return $this->redirect(['task/shared']);
    }

    /**
     * Deletes an existing TaskUser model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        Yii::$app->session->setFlash('warning', 'Успешно удалили доступ к задаче');

        return $this->redirect(['task/shared']);
    }

    /**
     * Finds the TaskUser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return TaskUser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TaskUser::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
