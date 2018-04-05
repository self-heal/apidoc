<?php
/**
 * Created by PhpStorm.
 * User: yangsl
 * Date: 2018/2/10
 * Time: 下午8:04
 */

namespace app\structs;


use yangsl\apidoc\apidoc\ApidocInterface;
use yii\base\Exception;
use Yii;

abstract class BaseRequest implements ApidocInterface
{
    public $params = [];

    public function __construct(?array $params = [])
    {
        $this->params = $params ?? [];

        if(YII_ENV_DEV && $params !== null) {
            $rules = $this->getRules();
            foreach ($rules as $key => $rule) {
                if(!isset($rule['require']) || !$rule['require']) {
                    continue;
                }
                if(!isset($params[$key])) {
                    throw new Exception('请求参数' . $key . '必填');
                }
            }
        }

    }


    /**
     * Returns the value of a request param.
     */
    public function __get($name)
    {
        if(isset($this->params[$name])) {
            return $this->params[$name];
        }
        $rules = $this->getRules();
        $rule = $rules[$name] ?? null;
        if($rule === null) {
            throw new Exception('rules中没有设置请求参数' . $name);
        }
        if($rule && isset($rule['default'])) {
            return $rule['default'];
        } else if($rule && isset($rule['type'])) {
            if($rule['type'] == 'array') {
                return [];
            }
        }
        return '';
    }

    /**
     * Sets value of an object property.
     */
    public function __set($name, $value)
    {
        $rules = $this->getRules();
        if(isset($rules[$name])) {
            $this->params[$name] = $value;
        } else {
            throw new Exception('rules中没有设置请求参数' . $name);
        }
    }

    /**
     * Checks if a property is set, i.e. defined and not null
     */
    public function __isset($name)
    {
        $params = $this->params;
        return isset($params[$name]);
    }

    /**
     * Sets an object property to null.
     */
    public function __unset($name)
    {
        $rules = $this->getRules();
        $rule = $rules[$name] ?? null;
        if($rule && isset($rule['default'])) {
            $this->params[$name] = $rule['default'];
        } else if($rule && isset($rule['type'])) {
            if($rule['type'] == 'array') {
                $this->params[$name] = [];
            }
        }
        $this->params[$name] = '';
    }

}