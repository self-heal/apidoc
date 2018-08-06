<?php
$this->title = 'doc';
?>
<div class="ui text container" style="max-width: none !important;">
<div class="ui floating message">

    <h2 class='ui header'>接口：<?=$service?></h2><br/>
            <span class='ui teal tag label'><?=$description?></span>

<!--
 * 接口说明 & 接口参数
 -->
<div class="ui raised segment">
    <span class="ui red ribbon label">接口说明</span>
    <div class="ui message">
        <p><?=$descComment?></p>
    </div>
</div>
<h3>接口参数</h3>
<table class="ui red celled striped table" >
    <thead>
    <tr><th>参数名字</th><th>类型</th><th>是否必须</th><th>默认值</th><th>其他</th><th>说明</th></tr>
    </thead>
    <tbody>
<?php
$typeMaps = array(
    'string' => '字符串',
    'int' => '整型',
    'float' => '浮点型',
    'boolean' => '布尔型',
    'date' => '日期',
    'array' => '数组',
    'fixed' => '固定值',
    'enum' => '枚举类型',
    'object' => '对象',
    'null' => ''
);?>
<?php
foreach ($rules as $key => $rule) {
    $name = $rule['name'];
    if (!isset($rule['type'])) {
        $rule['type'] = 'string';
    }
    $type = isset($typeMaps[$rule['type']]) ? $typeMaps[$rule['type']] : $rule['type'];
    $require = isset($rule['require']) && $rule['require'] ? '<font color="red">必须</font>' : '可选';
    $default = isset($rule['default']) ? $rule['default'] : '';
    if ($default === NULL) {
        $default = 'NULL';
    } else if (is_array($default)) {
        $default = json_encode($default);
    } else if (!is_string($default)) {
        $default = var_export($default, true);
    }

    $other = array();
    if (isset($rule['other'])) {
        $other[] = $rule['other'];
    }
    if (isset($rule['min'])) {
        $other[] = '最小：' . $rule['min'];
    }
    if (isset($rule['max'])) {
        $other[] = '最大：' . $rule['max'];
    }
    if (isset($rule['range'])) {
        $other[] = '范围：' . implode('/', $rule['range']);
    }
    if (isset($rule['source'])) {
        $other[] = '数据源：' . strtoupper($rule['source']);
    }
    $other = implode('；', $other);

    $desc = isset($rule['desc']) ? trim($rule['desc']) : '';
?>
<?php
    echo "<tr><td>$name</td><td>$type</td><td>$require</td><td>$default</td><td>$other</td><td>$desc</td></tr>\n";
} ?>


    </tbody>
</table>
<h3>返回结果</h3>
<table class="ui green celled striped table" >
    <thead>
        <tr><th>返回字段</th><th>类型</th><th>说明</th></tr>
    </thead>
    <tbody>
    <?php
        function renderResponse($response, $typeMaps = [], $level = 0) {
            $space = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
            $indent = $space;
            for ($i = 0; $i < $level; $i++) {
                $indent .= $space;
            }
            foreach ($response as $k => $item) {
                $innerType = isset($item['type']) ? $item['type'] : 'null';
                $innerDesc = isset($item['desc']) ? $item['desc'] : '';
                $type = isset($typeMaps[$innerType]) ? $typeMaps[$innerType] : $item['type'];
                if($level > 0) {
                    echo <<<EOT
<tr><td>$indent$k</td><td>$type</td><td>{$innerDesc}</td></tr>
EOT;
                } else {
                    echo <<<EOT
<tr><td>$k</td><td>$type</td><td>{$innerDesc}</td></tr>
EOT;
                }
                if($innerType == 'null' && is_array($item)) {
                    $level++;
                    renderResponse($item, $typeMaps,$level);
                }
            }
        }
        renderResponse($returns, $typeMaps);
    ?>
    </tbody>
</table>
<?php
if (!empty($exceptions)) {?>
    <h3>异常情况</h3>
    <table class="ui red celled striped table" >
        <thead>
            <tr><th>错误码</th><th>错误描述信息</th>
        </thead>
        <tbody>
        <?php

            foreach ($exceptions as $exItem) {
                $exCode = $exItem[0];
                $exMsg = isset($exItem[1]) ? $exItem[1] : '';
                echo "<tr><td>$exCode</td><td>$exMsg</td></tr>";
            }

        ?>
            </tbody>
        </table>
<?php }?>
<h3>
    请求模拟 &nbsp;&nbsp;
</h3>
<table class="ui green celled striped table" >
    <thead>
        <tr><th>参数</th><th>是否必填</th><th>值</th></tr>
    </thead>
    <tbody id="params">
        <tr>
            <td>service</td>
            <td><font color="red">必须</font></td>
            <td class="ui input"><input name="service" value="<?=$service?>" style="width:100%;" class="C_input" /></td>
        </tr>
    <?php
        foreach ($rules as $key => $rule){
            $name = $rule['name'];
            $require = isset($rule['require']) && $rule['require'] ? '<font color="red">必须</font>' : '可选';
            $default = isset($rule['default']) ? $rule['default'] : '';
            $desc = isset($rule['desc']) ? htmlspecialchars(trim($rule['desc'])) : '';
            $inputType = (isset($rule['type']) && $rule['type'] == 'file') ? 'file' : 'text';
            ?>
                <tr>
                    <td><?=$name?></td>
                    <td><?=$require?></td>
                    <td class="ui input"><input name="<?=$name?>" value="<?=$default?>" placeholder="<?=$desc?>" style="width:100%;" class="C_input" type="<?=$inputType?>"/></td>
                </tr>
    <?php } ?>
    </tbody>
</table>
<div style="display: flex;align-items:center;">
    <?php $url = \Yii::$app->urlManager->createAbsoluteUrl("{$service}");?>
    <select name="request_type" class="ui dropdown">
            <option value="POST">POST</option>
            <option value="GET">GET</option>
    </select>
    <div class="ui input">
        <input name="request_url" value="<?=$url?>"  style="width: 350px;"/>
        <?php if($service != '/site/login') {?>
        <input name="token" value=""  placeholder="access token" style="width: 350px;margin-left: 10px;"/>
        <?php }?>
        <input type="submit" name="submit" value="发送" id="submit" style="font-size:14px;line-height: 20px;margin-left: 10px "/>
    </div>
</div>
<div class="ui blue message" id="json_output">
</div>
    <?php
        $_csrf = \Yii::$app->request->getCsrfToken();
        $csrfKey = Yii::$app->request->csrfParam;
    ?>
    </div>
</div>
    <style>
    select.ui.dropdown{
        outline: 0;
        text-align: left;
        height: 42px;
        margin-right: 10px;
        background: #FFF;
        border: 1px solid rgba(34,36,38,.15);
        color: rgba(0,0,0,.87);
        border-radius: .28571429rem;
        -webkit-transition: box-shadow .1s ease,border-color .1s ease;
        transition: box-shadow .1s ease,border-color .1s ease;
        box-shadow: none;
    }
    #params .input{width: 100%;}
    </style>
<?php
$this->beginBlock('js') ?>
    function getData() {
        var data={};
        $("td input").each(function(index,e) {
            if ($.trim(e.value)){
                data[e.name] = e.value;
            }
        });
        if ($("select").val() == 'POST') {
            data['<?=$csrfKey?>'] = "<?=$_csrf?>";
        }
        return data;
    }

    $(function(){
    $("#json_output").hide();
    $("#submit").on("click",function(){
        $.ajax({
            url:$("input[name=request_url]").val(),
            type:$("select").val(),
            data:getData(),
            headers: {
                "Access-Control-Allow-Origin": "*",
                "Access-Control-Allow-Headers": "Authorization",
                "Authorization": "Bearer " + $("input[name=token]").val(),
            },
            success:function(res,status,xhr){
                console.log(xhr);
                var statu = xhr.status + ' ' + xhr.statusText;
                var header = xhr.getAllResponseHeaders();
                var json_text = JSON.stringify(res, null, 4);    // 缩进4个空格
                $("#json_output").html('<pre>' + statu + '<br/>' + header + '<br/>' + json_text + '</pre>');
                $("#json_output").show();
            },
            error:function(error){
                console.log(error)
            }
        })
    })
})
<?php $this->endBlock() ?>
<?php $this->registerJs($this->blocks['js'], \yii\web\View::POS_END); ?>