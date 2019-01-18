<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product".
 *
 * @property int    $id
 * @property string $name
 * @property int    $price
 * @property int    $created_at
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * 5) Назначить в экшенах actionCreate и actionUpdate ProductController-а у модели разные сценарии.
     */
    CONST SCENARIO_CREATE = 'create';
    CONST SCENARIO_UPDATE = 'update';

    /**
     * 3) Создать в Product метод scenarios() указав, что для сценария по умолчанию
     * активный атрибут только name - проверить изменения в работе формы, т.е. как
     * теперь загружаются и проверяются значения атрибутов. Добавить остальные атрибуты.
     */
    public function scenarios() {
        /**
         * 6) Настроить Product модель так, что бы значение атрибута name не изменялось при
         * изменении данных в actionUpdate даже если поле name в форме есть.
         */
        return [
            self::SCENARIO_DEFAULT => ['name', 'price', 'created_at'],
            self::SCENARIO_CREATE => ['name', 'price', 'created_at'],
            self::SCENARIO_UPDATE => ['price', 'created_at'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'price', 'created_at'], 'required'],
            [['created_at'], 'integer'],

            //Задание 2, урок 2
            /**
             * 2) Настроить правила в rules() модели Product и проверить на странице редактирования
             * (product/create) как они работают.
             * Правила:
             * name - длина до 20 символов, необходимо обрезать пробелы (trim) и вырезать тэги (strip_tags()),
             * сделать это нужно с помощью анонимной функции - смотрите описание валидатора FilterValidator.
             * price - только целые числа больше 0 и меньше 1000
             */
            ['name', 'string', 'max' => 20],
            ['name', 'filter', 'filter' => function ($val) {
                //сначала убираем тэги, т.к. если снала удалить пробелы
                //то после удаления тегов, после которых были пробелы,
                //будет не корректный результат
                return trim(strip_tags($val));
            }],
            ['price', 'integer', 'min' => 0, 'max' => 1000],


        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'price' => 'Price',
            'created_at' => 'Created At',
        ];
    }
}
