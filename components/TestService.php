<?php

namespace app\components;


use yii\base\Component;

class TestService extends Component
{
    public $property = 'Default property';

    public function getProperty() {
        return $this->property;
    }
}
