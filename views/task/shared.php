<?php
/**
 * Создать вьюху my скопировав или изменив вьюху index - в таблице д.б. только столбцы title, description,
 * created_at,updated_at,действия. Убедиться, что теперь в списке задач выводятся только созданные текущим
 * пользователем.
 */

use app\models\Task;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tasks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-index">
  <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>

  <p>
      <?= Html::a('Create Task', ['create'], ['class' => 'btn btn-success']) ?>
  </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            //Во вьюхе shared выводим название и текст задач, кнопку удаления доступа для всех юзеров, работающую с подверждением и отправкой по POST
            'title',
            'description:ntext',
            [
                'label' => 'users',
                'content' => function (Task $model) {
                    $users = $model->getSharedUsers()->select('username')->column();
                    return join(', ', $users);
                }
            ],

            [
                // Добавить в столбце действий ссылку с иконкой на /task-user/create/?taskId=ид_задачи
                'class' => 'yii\grid\ActionColumn',
                'template' => '{deleteAll} {view} {update} {delete}',
                'buttons' => [
                    'deleteAll' => function ($url, Task $model, $key) {
                        $icon = \yii\bootstrap\Html::icon('remove');
                        return Html::a($icon, [
                            'task-user/delete-all',
                            'taskId' => $model->id,],
                            [
                                'data' => [
                                    'confirm' => 'Вы действительно хотите убрать доступ к задаче?',
                                    'method' => 'post',
                                ],
                            ]
                        );
                    },

                ],
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>

</div>
