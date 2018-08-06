<?php
/**
 * Created by PhpStorm.
 * User: yangsl
 * Date: 2018/8/6
 * Time: 下午2:38
 */
?>
<style>
    .box{width: 300px;margin: 200px auto 0 auto;}
</style>
    <div class="box">
        <form action="./login" method="post">
        <input type="hidden" name="<?=Yii::$app->request->csrfParam?>" value="<?=\Yii::$app->request->getCsrfToken()?>">
        <div class="ui fluid action input">
            <input type="text" placeholder="输入口令" name="password">
            <button class="ui button" id="submit">确认</button>
        </div>
        </form>
    </div>
