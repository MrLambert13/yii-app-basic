<?php
/**
 * @var $model \app\models\TestModel
 * @var $prod \app\models\Product
 * @var $testmodel \app\components\TestService
 */
?>

<?php if (isset($model)): ?>
  <h3>Информация:</h3>
  <p>Имя: <u><?= $model->name ?></u></p>
  <p>Дата рождения: <u><?= $model->birthday ?> г.</u></p>
  <p>Город: <u><?= $model->city ?></u></p>
  <p>О себе: <u><?= $model->about ?></u></p>
  <hr>
<?php else: ?>
  <h2>Информация отсутствует</h2>
  <hr>
<?php endif; ?>

<?php if (isset($prod)): ?>
  <?= \yii\widgets\DetailView::widget(['model' => $prod]) ?>
  <hr>
<?php endif; ?>

Данные из метода getProperty() компонента test: <br>
<b><?= $testmodel ?></b>
