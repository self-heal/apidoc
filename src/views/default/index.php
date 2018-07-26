<?php
$asset = yangsl\apidoc\ApidocAsset::register($this);
$table_color_arr = explode(" ", "red orange yellow olive teal blue violet purple pink grey black");
?>
<div class="ui text container" style="max-width: none !important; width: 1200px" id="menu_top">
    <div class="ui floating message">
        <div class="ui grid container" style="max-width: none !important;">
            <?php if ($theme == 'fold') { ?>
                <div class="four wide column">
                    <div class="ui vertical accordion menu">
                        <?php
                        // 总接口数量
                        $methodTotal = 0;
                        foreach ($allApiS as $namespace => $subAllApiS) {
                            foreach ($subAllApiS as $item) {
                                $methodTotal += count($item['methods']);
                            }
                        }
                        ?>
                        <div class="item"><h4>接口服务列表&nbsp;(<?php echo $methodTotal; ?>)</h4></div>
                        <?php
                        $num = 0;
                        foreach ($allApiS as $namespace => $subAllApiS) {
                            echo '<div class="item">';
                            $subMethodTotal = 0;
                            foreach ($subAllApiS as $item) {
                                $subMethodTotal += count($item['methods']);
                            }
                            echo sprintf('<h4 class="title active" style="font-size:16px;margin:0px;"><i class="dropdown icon"></i>%s (%d)</h4>', $namespace, $subMethodTotal);
                            echo sprintf('<div class="content %s" style="margin-left:-16px;margin-right:-16px;margin-bottom:-13px;">', $num == 0 ? 'active' : '');
                            // 每个命名空间下的接口类
                            foreach ($subAllApiS as $key => $item) {
                                echo sprintf('<a class="item %s" data-tab="%s">%s</a>', $num == 0 ? 'active' : '', str_replace('/', '_', $namespace) . $key, $item['desc']);
                                $num++;
                            }
                            echo '</div></div><!-- END OF NAMESPACE -->';
                        } // 每个命名空间下的接口

                        ?>
                        <div class="item">
                            <div class="content" style="margin:-13px -16px;">
                                <a class="item" href="#menu_top">返回顶部↑↑↑</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?> <!-- 折叠时的菜单 -->

            <!-- 折叠时与展开时的布局差异 -->
            <?php if ($theme == 'fold') { ?>
            <div class="twelve wide stretched column">
                <?php } else { ?>
                <div class="wide stretched column">
                    <?php
                    // 展开时，将全部的接口服务，转到第一组
                    $mergeAllApiS = array('all' => array('methods' => array()));
                    foreach ($allApiS as $namespace => $subAllApiS) {
                        foreach ($subAllApiS as $key => $item) {
                            if (!isset($item['methods']) || !is_array($item['methods'])) {
                                continue;
                            }
                            foreach ($item['methods'] as $mKey => $mItem) {
                                $mergeAllApiS['all']['methods'][$mKey] = $mItem;
                            }
                        }
                    }
                    $allApiS = array('ALL' => $mergeAllApiS);
                    }
                    ?>
                    <?php
                    $num2 = 0;
                    foreach ($allApiS as $namespace => $subAllApiS) {
                        foreach ($subAllApiS as $key => $item) { ?>
                            <div class="ui  tab <?php if ($num2 == 0) { ?>active<?php } ?>" data-tab="<?php echo str_replace('/', '_', $namespace) . $key; ?>">
                                <table class="ui red celled striped table <?php echo $table_color_arr[$num2 % count($table_color_arr)]; ?> celled striped table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>接口服务</th>
                                        <th>接口名称</th>
                                        <th>更多说明</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $num = 1;
                                    foreach ($item['methods'] as $mKey => $mItem) { ?>
                                        <tr>
                                            <td><?=$num++?></td>
                                            <td>
                                                <a target="_blank" href="<?= \yii\helpers\Url::to(['/api-document/default/view', 'service' => $mItem['service']]) ?>"><?=$mItem['service']?></a>
                                            </td>
                                            <td><?=$mItem['title']?></td>
                                            <td><?=$mItem['desc']?></td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>

                                <!-- 主题切换，仅当在线时才支持 -->
                                <?php
                                    if ($theme == 'fold') {
                                        echo '<div style="float: right"><a href="' . \yii\helpers\Url::to(['/api-document', 'theme' => 'expand']) . '">切换回展开版</a></div>';
                                    } else {
                                        echo '<div style="float: right"><a href="' . \yii\helpers\Url::to(['/api-document', 'theme' => 'fold']) . '">切换回折叠版</a></div>';
                                    }
                                ?>

                            </div>
                            <?php
                            $num2++;
                        } // 单个命名空间的循环
                    } // 遍历全部命名空间
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->beginBlock('js') ?>
    $('.accordion.menu a.item').tab({'deactivate':'all'});
    $('.ui.sticky').sticky();
    $(".accordion.menu a.item").click(function() {
        $('body,html').animate({
            scrollTop: 0
        }, 500);
        return false;
    });
    $('.ui.accordion').accordion({'exclusive':false});
<?php $this->endBlock() ?>
<?php $this->registerJs($this->blocks['js'], \yii\web\View::POS_END); ?>