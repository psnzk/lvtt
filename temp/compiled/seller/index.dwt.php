<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><?php echo $this->fetch('library/seller_html_head.lbi'); ?></head>

<body>
<?php echo $this->fetch('library/seller_header.lbi'); ?>
<?php echo $this->fetch('library/url_here.lbi'); ?>
<div class="ecsc-layout">
    <div class="site wrapper">
		<?php echo $this->fetch('library/seller_menu_user.lbi'); ?>
        <div class="ecsc-layout-right">
            <div class="main-content" id="mainContent">
                <div class="top-container">
                    <div class="basic-info">
                        <dl class="ecsc-seller-info">
                            <dt class="seller-name">
                                <h3><?php echo $this->_var['seller_info']['shop_name']; ?></h3>
                                <h5>( 用户名：<?php echo $this->_var['seller_info']['user_name']; ?> )</h5>
                            </dt>
                            <dd class="store-logo">
                                <p><img src="<?php echo $this->_var['seller_info']['logo_thumb']; ?>" /></p>
                            </dd>
                            <dd class="seller-permission">管理权限：<strong>管理员</strong></dd>
                            <dd class="seller-last-login">最后登录：<strong><?php echo $this->_var['seller_info']['last_login']; ?></strong></dd>
                            <dd class="store-name">店铺名称：<a href="../merchants_store.php?merchant_id=<?php echo $this->_var['ru_id']; ?>" target="_blank"><?php echo $this->_var['seller_info']['shopName']; ?></a></dd>
                            <dd class="store-tishi list-items">
                                <ul>
                                    <li>待处理订单<em>(<?php echo $this->_var['order']['shipped_deal']; ?>)</em></li>
                                    <li>当前优惠活动<em>(<?php echo $this->_var['favourable_count']; ?>)</em></li>
                                    <li>即将到期优惠活动<em>(<?php echo $this->_var['favourable_dateout_count']; ?>)</em></li>
                                    <li>退换货订单<em>(<?php echo $this->_var['new_repay']; ?>)</em></li>
                                    <li>待商品回复咨询<em>(<?php echo $this->_var['reply_count']; ?>)</em></li>
                                </ul>
                            </dd>
                        </dl>
                        <div class="detail-rate">
                            <h5> <strong>店铺评分：</strong> 与行业相比 </h5>
                            <ul>
                                <li> 
                                	描述相符<span class="credit"><?php echo $this->_var['merch_cmt']['cmt']['commentRank']['zconments']['score']; ?>分</span> 
                                    <?php if ($this->_var['merch_cmt']['cmt']['commentRank']['zconments']['is_status'] == 1): ?>
                                    <span class="high"><i></i>高于</span> 
                                    <?php elseif ($this->_var['merch_cmt']['cmt']['commentRank']['zconments']['is_status'] == 2): ?>
                                    <span class="equal"><i></i>持平</span> 
                                    <?php else: ?>
                                    <span class="low"><i></i>低于</span> 
                                    <?php endif; ?>
                                </li>
                                <li> 
                                	服务态度<span class="credit"><?php echo $this->_var['merch_cmt']['cmt']['commentServer']['zconments']['score']; ?>分</span>
                                    <?php if ($this->_var['merch_cmt']['cmt']['commentServer']['zconments']['is_status'] == 1): ?>
                                    <span class="high"><i></i>高于</span> 
                                    <?php elseif ($this->_var['merch_cmt']['cmt']['commentServer']['zconments']['is_status'] == 2): ?>
                                    <span class="equal"><i></i>持平</span> 
                                    <?php else: ?>
                                    <span class="low"><i></i>低于</span> 
                                    <?php endif; ?>
                                </li>
                                <li> 
                                	发货速度<span class="credit"><?php echo $this->_var['merch_cmt']['cmt']['commentDelivery']['zconments']['score']; ?>分</span>
                                    <?php if ($this->_var['merch_cmt']['cmt']['commentDelivery']['zconments']['is_status'] == 1): ?>
                                    <span class="high"><i></i>高于</span> 
                                    <?php elseif ($this->_var['merch_cmt']['cmt']['commentDelivery']['zconments']['is_status'] == 2): ?>
                                    <span class="equal"><i></i>持平</span> 
                                    <?php else: ?>
                                    <span class="low"><i></i>低于</span> 
                                    <?php endif; ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="seller-cont">
                    <div class="container_left">
                        <div class="container type-a">
                            <div class="hd">
                                <h3>店铺及商品提示</h3>
                                <h5>您需要关注的店铺信息以及待处理事项</h5>
                            </div>
                            <div class="content">
                                <dl class="focus">
                                    <dt>店铺商品发布情况：</dt>
                                    <dd><strong id="nc_goodscount"><?php echo $this->_var['seller_goods_info']['total']; ?></strong> 条</dd>
                                    <!--<dt>图片空间使用：</dt>
                                    <dd><strong id="nc_imagecount">4</strong>&nbsp;/&nbsp;1000</dd>-->
                                </dl>
                                <ul>
                                    <li>出售中的商品<a href="goods.php?act=list&is_on_sale=1" target="_blank">(<?php echo $this->_var['seller_goods_info']['is_sell']; ?>)</a></li>
                                    <li>商品回收站<a href="goods.php?act=trash" target="_blank">(<?php echo $this->_var['seller_goods_info']['is_delete']; ?>)</a></li>
                                    <li>已下架的商品<a href="goods.php?act=list&is_on_sale=0" target="_blank">(<?php echo $this->_var['seller_goods_info']['is_on_sale']; ?>)</a></li>
                                    <li>总销售量(笔)<a href="javascript:void(0)">(<?php echo $this->_var['total_shipping_info']['order_total']; ?>)</a></li>
                                </ul>
                                <ul>
                                    <li>新品商品数<a href="goods.php?act=list&intro_type=is_new" target="_blank">(<?php echo $this->_var['hot_count']; ?>)</a></li>
                                    <li>精品商品数<a href="goods.php?act=list&intro_type=is_best" target="_blank">(<?php echo $this->_var['new_count']; ?>)</a></li>
                                    <li>热销商品数<a href="goods.php?act=list&intro_type=is_hot" target="_blank">(<?php echo $this->_var['best_count']; ?>)</a></li>
                                    <li>促销商品数<a href="goods.php?act=list&intro_type=is_promote" target="_blank">(<?php echo $this->_var['promotion_count']; ?>)</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="container type-a">
                            <div class="hd">
                                <h3>交易提示</h3>
                                <h5>您需要立即处理的交易订单</h5>
                            </div>
                            <div class="content">
                                <dl class="focus">
                                    <dt>近期售出：</dt>
                                    <dd><a href="order.php?act=list" class="num">交易中的订单 <strong id="nc_progressing"><?php echo $this->_var['order']['shipped_deal']; ?></strong> 单</a></dd>
                                </dl>
                                <ul>
                                	<li>待确定<a href="order.php?act=list&composite_status=<?php echo $this->_var['status']['unconfirmed']; ?>" target="_blank">(<?php echo $this->_var['order']['unconfirmed']; ?>)</a></li>
                                    <li>待付款<a href="order.php?act=list&composite_status=<?php echo $this->_var['status']['await_pay']; ?>" target="_blank">(<?php echo $this->_var['order']['await_pay']; ?>)</a></li>
                                    <li>待发货<a href="order.php?act=list&composite_status=<?php echo $this->_var['status']['await_ship']; ?>" target="_blank">(<?php echo $this->_var['order']['await_ship']; ?>)</a></li>
                                    <li>已完成<a href="order.php?act=list&composite_status=<?php echo $this->_var['status']['finished']; ?>" target="_blank">(<?php echo $this->_var['order']['finished']; ?>)</a></li>
                                    <li>缺货登记<a href="goods_booking.php?act=list_all" target="_blank">(<?php echo $this->_var['booking_goods']; ?>)</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="container type-a h400">
                            <div class="hd">
                                <h3>单品销售排名</h3>
                                <h5>掌握30日内最热销的商品及时补充货源</h5>
                            </div>
                            <div class="content">
                                <table class="ecsc-default-table rank">
                                <thead>
                                    <tr>
                                        <th width="10%">排名</th>
                                        <th width="80%">商品信息</th>
                                        <th width="10%">销量(件)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php $_from = $this->_var['goods_info']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'vo');$this->_foreach['goods_info'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods_info']['total'] > 0):
    foreach ($_from AS $this->_var['vo']):
        $this->_foreach['goods_info']['iteration']++;
?>
                                <tr class="bd-line2">
                                    <td class="tc"><?php echo $this->_foreach['goods_info']['iteration']; ?></td>
                                    <td class="tl"><a target="_blank" href="../goods.php?id=<?php echo $this->_var['vo']['goods_id']; ?>" class="goods_name"> <?php echo $this->_var['vo']['goods_name']; ?></a></td>
                                    <td class="tc"><?php echo $this->_var['vo']['goods_shipping_total']; ?></td>
                                </tr>
                                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="container type-a h300">
                            <div class="hd">
                                <h3>销售情况统计</h3>
                                <h5>按周期统计商家店铺的订单量和订单金额</h5>
                            </div>
                            <div class="content">
                                <table class="ecsc-default-table count">
                                <thead>
                                    <tr>
                                        <th class="w80">项目</th>
                                        <th>订单量(笔)</th>
                                        <th class="w100">订单金额(元)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="bd-line2">
                                        <td>昨日销量</td>
                                        <td><?php echo $this->_var['yseterday_shipping_info']['order_total']; ?></td>
                                        <td><?php echo $this->_var['yseterday_shipping_info']['money_total']; ?></td>
                                    </tr>
                                    <tr class="bd-line2">
                                        <td>月销量</td>
                                        <td><?php echo $this->_var['month_shipping_info']['order_total']; ?></td>
                                        <td><?php echo $this->_var['month_shipping_info']['money_total']; ?></td>
                                    </tr>
                                </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="container_right">
                        <div class="container type-b">
                            <div class="hd">
                                <h3>商家帮助</h3>
                                <h5></h5>
                            </div>
                            <div class="content">
                                <ul>
                                    <?php $_from = $this->_var['articles']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'vo');if (count($_from)):
    foreach ($_from AS $this->_var['vo']):
?>
                                    <li><a target="_blank" href="../article.php?id=<?php echo $this->_var['vo']['article_id']; ?>" title="<?php echo $this->_var['vo']['title']; ?>"><?php echo $this->_var['vo']['title']; ?></a></li>
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                </ul>
                                <dl>
                                    <dt>联系方式</dt>
                                    <dd>电话：<?php echo $this->_var['seller_info']['kf_tel']; ?></dd>
                                    <dd>邮箱：<?php echo $this->_var['seller_info']['seller_email']; ?></dd>
                                    <dd>地址：<?php echo $this->_var['seller_info']['shop_address']; ?></dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $this->fetch('library/seller_footer.lbi'); ?>
<?php echo $this->smarty_insert_scripts(array('files'=>'jquery.purebox.js')); ?>
</body>
</html>
