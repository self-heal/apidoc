<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yangsl\apidoc\controllers;

use yangsl\apidoc\apidoc\ApiDesc;
use yangsl\apidoc\apidoc\ApiList;
use Yii;
use yii\filters\auth\HttpBasicAuth;
use yii\web\Controller;

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

    public function beforeAction($action){
        $password = $this->module->password;
        if($action->id != 'login' && !empty($password)) {
            $checkLogin = Yii::$app->session->get('checklogin');
            if(!$checkLogin) {
                return $this->redirect('/api-document/default/login');
            }
        }

        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        $theme = Yii::$app->request->get('theme', 'fold');
        $this->layout = 'main';
        $api = new ApiList();
        $api->appControllers = $this->module->appControllers;
        $api->appFolder = $this->module->appFolder;
        $api->cacheDuration = $this->module->cacheDuration;
        $cacheDuration = $api->cacheDuration;
        $allApiS = Yii::$app->cache->get('allApiS');
        if(empty($allApiS)) {
            $allApiS = $api->getApiList($this->module->modules);
            Yii::$app->cache->set('allApiS', $allApiS, $cacheDuration);
        }
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

    public function actionLogin()
    {
        Yii::$app->session->set('checklogin', null);
        if(Yii::$app->request->isPost) {
            $password = Yii::$app->request->post('password', '');
            if($this->module->password == $password) {
                Yii::$app->session->set('checklogin', 1);
                return $this->redirect('/api-document/default/index');
            }
            return $this->refresh();
        } else {
            return $this->render('login', [

            ]);
        }
    }

}