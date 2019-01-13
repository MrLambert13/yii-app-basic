<?php
/**
 * @var $model app\models\TestModel
 * @var $prod \app\models\Product
 */
?>
<?php if (isset($model)): ?>
  <h3>Информация:</h3>
  <p>Имя: <u><?= $model->name ?></u></p>
  <p>Дата рождения: <u><?= $model->birthday ?> г.</u></p>
  <p>Город: <u><?= $model->city ?></u></p>
  <p>О себе: <u><?= $model->about ?></u></p>
<?php else: ?>
  <h2>Информация отсутствует</h2>
<?php endif; ?>
<hr>
<?php if (isset($model)): ?>
  <?= \yii\widgets\DetailView::widget(['model' => $prod]) ?>
<?php endif; ?>
