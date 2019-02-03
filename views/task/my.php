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
/* @var $creator array */


var_dump($creator);
var_dump($dataProvider->canGetProperty('Title'));
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
            ['class' => 'yii\grid\SerialColumn'],
            'title',
            'description:ntext',
            'created_at:datetime',
            'updated_at:datetime',

            [
                // Добавить в столбце действий ссылку с иконкой на /task-user/create/?taskId=ид_задачи
                'class' => 'yii\grid\ActionColumn',
                'template' => '{share} {view} {update} {delete}',
                'buttons' => [
                    'share' => function ($url, Task $model, $key) {
                        $icon = \yii\bootstrap\Html::icon('share');
                        return Html::a($icon, ['task-user/create', 'taskId' => $model->id]);
                    },

                ],
                'visibleButtons' => [
                    // Если пользователь не создатель задачи, то он не может расшаривать и удалять её

                    'delete' => function ($model, $key, $index) use ($creator) {
                        return in_array($model->id, $creator);
                    },
                    'share' => function ($model, $key, $index) use ($creator) {
                        return in_array($model->id, $creator);
                    },
                ],
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>

</div>
