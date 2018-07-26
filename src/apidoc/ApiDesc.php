<?php
namespace yangsl\apidoc\apidoc;



class ApiDesc {
    /**
     * @var bool 是否检测基础控制器
     */
    public $appControllers = true;

    public function getApiInfo($service) {
        $requestData = array();
        $responseData = array();
        $description = $descComment = '暂无说明';
        $exceptions = array();
        $service = trim($service, '/');
        $exploade_service = explode('/', $service);
        if(count($exploade_service) == 1) {
            $exploade_service[] = 'index';
        }
        //默认app
        $ctlClassName = \Yii::$app->controllerNamespace . '\\%sController';
        $ctlClassName = sprintf($ctlClassName, ucfirst($exploade_service[0]));
        $methodName = 'action' .ucfirst($exploade_service[1]);
        //modules
        if (!class_exists($ctlClassName)) {
            $c = count($exploade_service);
            if($c == 2) {
                $exploade_service[] = 'index';
            }
            $moduleName = $exploade_service[0];
            $modules = \Yii::$app->modules;
            if(isset($modules[$moduleName])) {
                $module = $modules[$moduleName]['class'];
                $t = new \ReflectionClass($module);
                $moduleNamespace = $t->getNamespaceName();
                $ctlClassName = $moduleNamespace . '\\controllers\\%sController';
                $ctlClassName = sprintf($ctlClassName, ucfirst($exploade_service[1]));
                $methodName = 'action' .ucfirst($exploade_service[2]);
            } else {
                //app 二级
                $ctlClassName = \Yii::$app->controllerNamespace .'\\' . $exploade_service[0] . '\\%sController';
                $ctlClassName = sprintf($ctlClassName, ucfirst($exploade_service[1]));
                $methodName = 'action' .ucfirst($exploade_service[2]);
            }
        }

        // 整合需要的类注释，包括父类注释
        $rClass = new \ReflectionClass($ctlClassName);
        $classDocComment = $rClass->getDocComment();
        $needClassDocComment = '';
        foreach (explode("\n", $classDocComment) as $comment) {
            if (stripos($comment, '@exception') !== false || stripos($comment, '@return') !== false) {
                $needClassDocComment .=  "\n" . $comment;
            }
        }
        $methodName = $this->convertHyphens($methodName);

        if($rClass->hasMethod($methodName)) {
            // 方法注释
            $rMethod = new \ReflectionMethod($ctlClassName, $methodName);
        } else {
            $methodName = $exploade_service[1];
            $ctlObj = new $ctlClassName('', '');
            $actions = $ctlObj->actions();
            $actionCls = $actions[$methodName]['class'];
            $rMethod = new \ReflectionClass($actionCls);
        }
        $docCommentArr = explode("\n", $needClassDocComment . "\n" . $rMethod->getDocComment());

        $setApidocObject = false;

        foreach ($docCommentArr as $comment) {
            $comment = trim($comment);
            //标题描述
            if (empty($description) && strpos($comment, '@') === false && strpos($comment, '/') === false) {
                $description = substr($comment, strpos($comment, '*') + 1);
                continue;
            }
            //@param注释
            $pos = stripos($comment, '@param');
            if ($pos !== false) {
                $paramArr = explode(' ', trim(substr(trim($comment), $pos + 7)), 3);
                if(!$setApidocObject) {
                    $rule = [];
                    $rule['name'] = isset($paramArr[1])?ltrim($paramArr[1], '$'):'';
                    $rule['type'] = isset($paramArr[0]) ? $paramArr[0] : 'string';
                    $ruleString = isset($paramArr[2]) ? trim($paramArr[2], '|') : '';
                    $ruleArr = explode('|', $ruleString);

                    $rule['name'] = isset($ruleArr[0])?$ruleArr[0]:'无';
                    $rule['require'] = isset($ruleArr[1]) && $ruleArr[1]=='true' ? true : false;
                    $rule['default'] = isset($ruleArr[2]) ? $ruleArr[2] : '';
                    $rule['desc'] = isset($ruleArr[3]) ? $ruleArr[3] : '无';
                    $rule['other'] = isset($ruleArr[4]) ? $ruleArr[4] : '';
                    $requestData[] = $rule;
                }
                continue;
            }
            //@param注释
            $pos = stripos($comment, '@apidoc');
            if ($pos !== false) {
                $paramArr = explode(' ', trim(substr(trim($comment), $pos + 7)), 3);
                $object = $paramArr[0];
                /**
                 * @var $apidoc ApidocInterface
                 */
                $apidoc = new $object(null);
                $requestData = $apidoc->getRules();
                $responseData = $apidoc->getResponse();
                $setApidocObject = true;
                continue;
            }
            //@desc注释
            $pos = stripos($comment, '@desc');
            if ($pos !== false) {
                $descComment = substr($comment, $pos + 5);
                continue;
            }
            //@exception注释
            $pos = stripos($comment, '@exception');
            if ($pos !== false) {
                $exArr = explode(' ', trim(substr($comment, $pos + 10)));
                $exceptions[$exArr[0]] = $exArr;
                continue;
            }
            //@return注释
            $pos = stripos($comment, '@return');
            if ($pos !== false) {
                $returnCommentArr = explode(' ', substr($comment, $pos + 8));
                //将数组中的空值过滤掉，同时将需要展示的值返回
                $returnCommentArr = array_values(array_filter($returnCommentArr));
                if (count($returnCommentArr) < 2) {
                    continue;
                }
                if($setApidocObject) {
                    break;
                }
                $row = [];
                $row['name'] = $returnCommentArr[1];
                $row['type'] = $returnCommentArr[0];
                if (!isset($returnCommentArr[2])) {
                    $row['desc'] = '';	//可选的字段说明
                } else {
                    //兼容处理有空格的注释
                    $row['desc'] = implode(' ', array_slice($returnCommentArr, 2));
                }
                //以返回字段为key，保证覆盖
                $responseData[$row['name']] = $row;
            }

        }

        return [$description, $descComment, $requestData, $responseData];
    }

    /*
     * - 转驼峰
     */
    private function convertHyphens($str)
    {
        $str = preg_replace_callback('/([-_]+([a-z]{1}))/i',function($matches){
            return strtoupper($matches[2]);
        },$str);
        return $str;
    }

}

