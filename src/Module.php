<?php

namespace yangsl\apidoc;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'yangsl\apidoc\controllers';
    public $appControllers = true;
    public $modules = [];

    public function init()
    {
        parent::init();
        // custom initialization code goes here
    }
}
