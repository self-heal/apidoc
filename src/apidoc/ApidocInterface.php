<?php
/**
 * Created by PhpStorm.
 * User: yangsl
 * Date: 2018/2/7
 * Time: 下午11:15
 */

namespace yangsl\apidoc\apidoc;

Interface ApidocInterface
{
    public function getRules();

    public function getResponse();
}