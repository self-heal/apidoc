<?php

namespace yangsl\test;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'yangsl\test\controllers';
    public $appControllers = true;
    public $suffix = '';
    public $prefix = '';
    public $modules = [];

    public function init()
    {
        parent::init();
        // custom initialization code goes here
    }
}
