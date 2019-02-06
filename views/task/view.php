<?php

use app\models\Task;
use app\models\TaskUser;
use app\models\User;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Task */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Tasks', 'url' => ['my']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="task-view">

  <h1><?= Html::encode($this->title) ?></h1>

  <p>
      <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

      <?= Html::a('Share', ['task-user/create', 'taskId' => $model->id], ['class' => 'btn btn-primary']) ?>

      <?= Html::a('Delete', ['delete', 'id' => $model->id], [
          'class' => 'btn btn-danger',
          'data' => [
              'confirm' => 'Are you sure you want to delete this item?',
              'method' => 'post',
          ],
      ]) ?>
  </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'description:ntext',
            'creator_id',
            'updater_id',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'username',
            [
                // Добавить в столбце действий ссылку с иконкой на /task-user/create/?taskId=ид_задачи
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete}',
                'buttons' => [
                    'delete' => function ($url, User $user, $key) use ($model) {
                        $icon = \yii\bootstrap\Html::icon('remove');
                        $taskUserId = TaskUser::find()->where(['user_id' => $user->id])->andWhere(['task_id' => $model->id]);
                        return Html::a($icon, [
                            'task-user/delete',
                            'id' => $taskUserId,],
                            [
                                'data' => [
                                    'confirm' => 'Вы действительно хотите убрать доступ к задаче пользователю ' . $user->username . ' ?',
                                    'method' => 'post',
                                ],
                            ]
                        );
                    },

                ],
            ],
        ],
    ]) ?>

</div>
