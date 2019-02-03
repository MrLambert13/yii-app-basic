<?php

namespace app\controllers;

use app\models\TaskUser;
use Yii;
use app\models\Task;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
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
    //id текущего пользовтеля
    $currentUserId = Yii::$app->user->id;

    //Выведет только задачи где пользователь создатель (по заданию)
    $query = Task::find()->byCreator($currentUserId);


    //задачи где пользователь создатель, и расшаренные ему задачи
    $myQuery = TaskUser::find()->select('task_id')->where(['user_id' => $currentUserId]);
    $myMainQuery = Task::find()->where(['creator_id' => $currentUserId])->orWhere(['id' => $myQuery]);

    $dataProvider = new ActiveDataProvider([
      'query' => $myMainQuery,
    ]);

    return $this->render('my', [
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
   */
  public function actionView($id) {
    return $this->render('view', [
      'model' => $this->findModel($id),
    ]);
  }

  /**
   * Creates a new Task model.
   * If creation is successful, the browser will be redirected to the 'view' page.
   * @return mixed
   */
  public function actionCreate() {
    $model = new Task();

    if ($model->load(Yii::$app->request->post()) && $model->save()) {
      return $this->redirect(['view', 'id' => $model->id]);
    }

    return $this->render('create', [
      'model' => $model,
    ]);
  }

  /**
   * Updates an existing Task model.
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
   * Deletes an existing Task model.
   * If deletion is successful, the browser will be redirected to the 'index' page.
   *
   * @param integer $id
   *
   * @return mixed
   * @throws NotFoundHttpException if the model cannot be found
   */
  public function actionDelete($id) {
    $this->findModel($id)->delete();

    return $this->redirect(['my']);
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
