<?php

namespace yangsl\apidoc;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'yangsl\apidoc\controllers';
    public $appFolder = 'app';
    public $appControllers = true;
    public $cacheDuration = 1800;
    public $modules = [];


    public function init()
    {
        parent::init();
        // custom initialization code goes here
    }
}
