# apidoc
yii2 apidoc

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).


```
composer require yangsl/apidoc

```
config add to modules
```
    'api-document' => [
        'class' => 'yangsl\apidoc\Module',
        'defaultRoute' => 'default', //默认控制器
        'appControllers' => true, //是否检测app\controllers命名空间下的控制器
        'suffix' => '', //api后缀
        'prefix' => '', //api前缀
        'modules' => [  //需要生成文档的模块命名空间
            'app\modules\test\Module',
        ],
    ],
```
