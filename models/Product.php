<?php

namespace app\models;

use yii\base\Model;

/**
 * Class Product
 * @package app\models
 */
class Product extends Model
{
    public $id;
    public $name;
    public $category;
    public $price;

    public function __construct(int $id, string $name, string $category, float $price) {
        $this->id = $id;
        $this->name = ucfirst($name);
        $this->category = ucfirst($category);
        $this->price = $price;
    }
}
