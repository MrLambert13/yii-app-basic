<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Product */
/* @var $form yii\widgets\ActiveForm */

/**
 * 4) Изменить в форме модели Product тип поля для атрибута name - сделать в виде выпадаюшего списка,
 * в котором значения 111,222,333, а подписи, т.е. то что видно в списке при выборе - "первый",
 * второй", "третий".
 * Проверить, что при сохранении модели в базу записывается одно из значений 111,222,333 и
 * при последующем показе формы в списке выбирается то значение, которое сохранили.
 */
?>

<div class="product-form">

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'name')->dropDownList([
        '111' => 'First',
        '222' => 'Second',
        '333' => 'Third',
    ]) ?>

    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
