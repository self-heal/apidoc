<?php
namespace app\structs;

use yangsl\test\apidoc\ApidocInterface;
use yii\base\Component;

/**
 * @property integer $id
 * @property integer $customer_id
 * @property integer $created_at
 * @property string $total
 */
class DemoRequest extends Component implements ApidocInterface {

    private $params = [
        'default' => array(
            'title' => array('name' => 'title', 'require' => true, 'min' => 1, 'max' => '20', 'desc' => '标题'),
            'content' => array('name' => 'content', 'require' => true, 'min' => 1, 'desc' => '内容'),
            'state' => array('name' => 'state', 'type' => 'int', 'default' => 0, 'desc' => '状态'),
        ),
        /*'update' => array(
            'id' => array('name' => 'id', 'require' => true, 'min' => 1, 'desc' => 'ID'),
            'title' => array('name' => 'title', 'require' => true, 'min' => 1, 'max' => '20', 'desc' => '标题'),
            'content' => array('name' => 'content', 'require' => true, 'min' => 1, 'desc' => '内容'),
            'state' => array('name' => 'state', 'type' => 'int', 'default' => 0, 'desc' => '状态'),
        ),
        'get' => array(
            'id' => array('name' => 'id', 'require' => true, 'min' => 1, 'desc' => 'ID'),
        ),
        'delete' => array(
            'id' => array('name' => 'id', 'require' => true, 'min' => 1, 'desc' => 'ID'),
        ),
        'getList' => array(
            'page' => array('name' => 'page', 'type' => 'int', 'min' => 1, 'default' => 1, 'desc' => '第几页'),
            'perpage' => array('name' => 'perpage', 'type' => 'int', 'min' => 1, 'max' => 20, 'default' => 10, 'desc' => '分页数量'),
            'state' => array('name' => 'state', 'type' => 'int', 'default' => 0, 'desc' => '状态'),
        ),
        'png' => array(
            'data' => array('name' => 'data', 'require' => true, 'desc' => '待生成二维码的内容'),
            'level' => array('name' => 'level', 'type' => 'enum', 'range' => array('L','M','Q','H'), 'default' => 'L', 'desc' => '错误纠正级别，L为最小，H为最佳'),
            'size' => array('name' => 'size', 'type' => 'int', 'min' => 1, 'max' => 10, 'default' => 4, 'desc' => '二维码尺寸大小'),
            'isShowPic' => array('name' => 'output', 'type' => 'boolean', 'default' => true, 'desc' => '是否直接显示二维码，否的话通过base64返回二维码数据'),
        ),
        'file' => array(
            'name' => 'file',        // 客户端上传的文件字段
            'type' => 'file',
            'require' => true,
            'max' => 2097152,        // 最大允许上传2M = 2 * 1024 * 1024,
            'range' => array('image/jpeg', 'image/png'),  // 允许的文件格式
            'ext' => 'jpeg,jpg,png', // 允许的文件扩展名
            'desc' => '待上传的图片文件',
        ),*/
    ];

    public function getParams()
    {
        return [
            'title' => array('name' => 'title', 'require' => true, 'min' => 1, 'max' => '20', 'desc' => '标题'),
            'content' => array('name' => 'content', 'require' => true, 'min' => 1, 'desc' => '内容'),
            'state' => array('name' => 'state', 'type' => 'int', 'default' => 0, 'desc' => '状态'),
        ];
    }

    public function getResponse()
    {
        return [
            'title' => array('type' => 'string', 'desc' => '标题'),
            'content' => array('type' => 'string', 'desc' => '内容'),
            'state' => array('type' => 'int', 'desc' => '状态'),
            'list' => [
                'type' => 'array',
                'desc' => '测试列表',
                'items' => [
                    'id' => ['type' => 'int', 'desc' => 'ID'],
                    'name' => ['type' => 'string', 'desc' => '名称']
                ]
            ]
        ];

    }
}
