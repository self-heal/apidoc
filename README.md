# apidoc
yii2 apidoc

根据注释或者定义的对象文件生成接口文档。

yii2 basic版本 测试通过

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).


```
composer require yangsl/apidoc

```
配置
```
if (YII_ENV_DEV) {
    $config['modules']['api-document'] = [
        'class' => 'yangsl\apidoc\Module',
        'defaultRoute' => 'default', //默认控制器
        'appControllers' => true, //是否检测app\controllers命名空间下的控制器
        'modules' => [  //需要生成文档的模块命名空间
            'app\modules\test\Module',
        ],
    ];
}
```

访问
```
http://domain/api-document

```
action注释
```
    /**
     * 这是一个测试的Api
     * @desc 列举所有的注释格式
     *
     * @param string $user_type |用户类型|true||其他说明|
     * @param int $sex |性别|true|0|0:不限 1:男 2:女|
     *
     * @return int status 操作码，0表示成功
     * @return string msg 提示信息
     * @return array list 用户列表
     * @return int list[].id 用户ID
     * @return string list[].name 用户名字
     *
     * @exception 400 参数传递错误
     * @exception 500 服务器内部错误
     */
```
或

```
    /**
     * 这是一个测试的Api
     * @desc 列举所有的注释格式
     *
     * @apidoc \app\structs\DemoRequest
     *
     * @exception 400 参数传递错误
     * @exception 500 服务器内部错误
     */
     public function actionTestApi()
     {
         $postData = Yii::$app->request->post();
         $requestData = new DemoRequest($postData);
         $response = DemoService::testMethod($requestData);
         return $this->renderMessageResponse($response);
     }

```
service层处理逻辑, 适用@apidoc形式传值

```
    public static function testMethod(DemoRequest $requestData) : MessageResponse
    {
        $response = new MessageResponse();

        $id = $requestData->id; //代码提示

        //todo
        $response->message = '错误提示';
        return $response;
    }

```