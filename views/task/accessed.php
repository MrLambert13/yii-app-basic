<?php
/**
 * Создать вьюху my скопировав или изменив вьюху index - в таблице д.б. только столбцы title, description,
 * created_at,updated_at,действия. Убедиться, что теперь в списке задач выводятся только созданные текущим
 * пользователем.
 */

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
        //Во вьюхе accessed выводим название, текст, имя автора и время создания.
        'dataProvider' => $dataProvider,
        'columns' => [
            'title',
            'description:ntext',
            'creator.username',
            'created_at:datetime',
        ],
    ]); ?>
    <?php Pjax::end(); ?>

</div>
