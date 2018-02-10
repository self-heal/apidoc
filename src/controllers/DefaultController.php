<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yangsl\test\controllers;

use app\components\Helper;
use yangsl\test\apidoc\ApiDesc;
use yangsl\test\apidoc\ApiList;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class DefaultController extends Controller
{
    public $layout = 'main';
    /**
     * @var \yii\gii\Module
     */
    public $module;
    /**
     * @var \yii\gii\Generator
     */
    public $generator;


    public function actionIndex()
    {
        $theme = Yii::$app->request->get('theme', 'fold');
        $this->layout = 'main';
        $api = new ApiList();
        $api->appControllers = $this->module->appControllers;
        $api->suffix = $this->module->suffix;
        $api->prefix = $this->module->prefix;
        $api->modules = $this->module->modules;
        $allApiS = $api->getApiList($api->modules);
        return $this->render('index',[
            'allApiS' => $allApiS,
            'theme' => $theme
        ]);
    }

    public function actionView()
    {

        $service = \Yii::$app->request->get('service');
        $api = new ApiDesc();
        $api->appControllers = $this->module->appControllers;
        $api->suffix = $this->module->suffix;
        $api->prefix = $this->module->prefix;
        $info = $api->getApiInfo($service);
        list($description, $descComment, $requestData, $responseData) = $info;
        return $this->render('view', [
            'description' => $description,
            'descComment' => $descComment,
            'rules' => $requestData,
            'returns' => $responseData,
            'service' => $service
        ]);
    }

}
