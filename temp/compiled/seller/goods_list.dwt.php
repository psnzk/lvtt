<?php if ($this->_var['full_page']): ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><?php echo $this->fetch('library/seller_html_head.lbi'); ?></head>
<body>
<?php echo $this->fetch('library/seller_header.lbi'); ?>
<?php echo $this->fetch('library/url_here.lbi'); ?>
<div class="ecsc-layout">
    <div class="site wrapper">
        <?php echo $this->fetch('library/seller_menu_left.lbi'); ?>
        <div class="ecsc-layout-right">
            <div class="main-content" id="mainContent">                  
                <?php echo $this->fetch('library/seller_menu_tab.lbi'); ?>
                <div class="search-form">
                    <form method="get" action="javascript:searchGoodsList()" name="searchFormList">		
                        <div class="fr">
                            <div class="p">
                            
                            <!-- 是否审核 -->
                            <label><?php echo $this->_var['lang']['audited']; ?></label>
                            <select name="review_status" class="select">
                                <option value="0"><?php echo $this->_var['lang']['intro_type']; ?></option>
                                <option value="1"><?php echo $this->_var['lang']['not_audited']; ?></option>
                                <option value="2"><?php echo $this->_var['lang']['audited_not_adopt']; ?></option>
                                <option value="3"><?php echo $this->_var['lang']['audited_yes_adopt']; ?></option>
                            </select>
                            <!-- 是否审核 -->
                            
                            <!-- 关键字 -->
                            <label>输入<?php echo $this->_var['lang']['keyword']; ?></label>
                            <input type="text" class="text text_2" name="keyword" value="">
                            <!-- 关键字 end-->
                            </div>
                            <div class="p" style="margin-bottom:0;">
                            <input type="hidden" name="act" value="store_goods_online">
                            <input type="hidden" name="op" value="index">
                            <input type="hidden" name="cat_id" id="cat_id" value="0"/>
                            
                            <label class="submit-border ml10"><input type="submit" class="submit" value="<?php echo $this->_var['lang']['button_search']; ?>"></label>
                            </div>
                        </div>
                    </form>
                </div>
                <?php endif; ?>
                <form method="post" action="" name="listForm" onsubmit="return confirmSubmit(this)">
                    <input type="hidden" name="act" value="batch">
                    <input type="hidden" name="type" value>
                    <div id="listDiv">
                        <table class="ecsc-default-table">
                            <thead>
                            <tr ectype="table_header">
                                <th class="w30">&nbsp;</th>
                                <th class="w50">&nbsp;</th>
                                <th class="w300"><?php echo $this->_var['lang']['goods_steps_name']; ?></th>
                                <th class="w80">价格</th>
                                <th class="w60">库存</th>
                                <th class="w70"><a href="javascript:listTable.sort('sort_order'); "><?php echo $this->_var['lang']['sort_order']; ?></a><div class="img"><?php echo $this->_var['sort_sort_order']; ?></div></th>
                                <th class="w60">上架</th>
                                <th class="w90"><?php echo $this->_var['lang']['audit_status']; ?></th>
                                <th class="w250"><?php echo $this->_var['lang']['handler']; ?></th>
                            </tr>
                            <tr>
                                <td class="tc"><input type="checkbox" id="all" class="checkall" name="checkboxes[]" onclick='listTable.selectAll(this, "checkboxes")'></td>
                                <td colspan="20"><label for="all">全选</label>
                                    <a href="javascript:void(0);" class="ecsc-btn-mini" ectype="batchbutton" uri="#" onclick="changeAction('trash')"><i class="icon-trash"></i>回收站</a>
									<a href="javascript:void(0);" class="ecsc-btn-mini" ec_type="batchbutton" uri="#" onclick="changeAction('on_sale')"><i class="icon-cloud-upload"></i>上架</a>
                                    <a href="javascript:void(0);" class="ecsc-btn-mini" ec_type="batchbutton" uri="#" onclick="changeAction('not_on_sale')"><i class="icon-level-down"></i>下架</a>
                                </td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');$this->_foreach['goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods']['total'] > 0):
    foreach ($_from AS $this->_var['goods']):
        $this->_foreach['goods']['iteration']++;
?>
                                <tr>
                                    <th class="tc"><input type="checkbox"  class="checkitem tc" value="<?php echo $this->_var['goods']['goods_id']; ?>" name="checkboxes[]"></th>
                                    <th colspan="20">编号：<?php echo $this->_var['goods']['goods_id']; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;货号：<?php echo $this->_var['goods']['goods_sn']; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;发布时间：<?php echo $this->_var['goods']['formated_add_tim']; ?></th>
                                </tr>
                                <tr>
                                    <td class="trigger"><i class="tip icon-plus-sign" <?php if ($this->_var['add_handler']): ?>style="margin-top:35px"<?php endif; ?> ectype="GoodsList" data-comminid="100094" title="<?php echo $this->_var['lang']['new_store_desc_title']; ?>"></i></td>
                                    <td><div class="pic-thumb"><a href="../goods.php?id=<?php echo $this->_var['goods']['goods_id']; ?>" target="_blank"><img src="../<?php echo $this->_var['goods']['goods_thumb']; ?>"></a></div></td>
                                    <td class="tl">
                                        <dl class="goods-name">
                                            <dt>
                                            <p>
                                            <strong onclick="listTable.edit(this, 'edit_goods_name', <?php echo $this->_var['goods']['goods_id']; ?>)" class="hidden"><?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?></strong>
                                            <?php if ($this->_var['goods']['brand_name']): ?><font style="color:#27A9E3">[ <?php echo $this->_var['goods']['brand_name']; ?> ]</font><?php endif; ?>
         
                                            <?php if ($this->_var['goods']['is_shipping']): ?>
                                            <span  class="span_color span_green">免邮</span>
                                            <?php endif; ?>
                                            
                                            <?php if ($this->_var['goods']['stages']): ?>
                                            <span  class="span_color span_blue">分期</span>
                                            <?php endif; ?>
                                            <?php if (! $this->_var['goods']['is_alone_sale']): ?>
                                            <span  class="span_color span_light_red">配件</span>
                                            <?php endif; ?>
                                            
                                            <?php if ($this->_var['goods']['is_promote']): ?>
                                                <?php if ($this->_var['nowTime'] >= $this->_var['goods']['promote_end_date']): ?>
                                            <span  class="span_color span_red">特卖结束</span>
                                                <?php else: ?>
                                            <span  class="span_color span_red">特卖</span>    
                                                <?php endif; ?>
                                            <?php endif; ?>
                                            
                                            <?php if ($this->_var['goods']['is_xiangou']): ?>
                                                <?php if ($this->_var['nowTime'] >= $this->_var['goods']['xiangou_end_date']): ?>
                                            <span  class="span_color span_light_purple">限购结束</span>
                                                <?php else: ?>
                                            <span  class="span_color span_light_purple">限购</span>    
                                                <?php endif; ?>
                                            <?php endif; ?>
                                            </p>
                                            </dt>
                                        </dl>
                                    </td>
                                    <td><span onclick="listTable.edit(this, 'edit_goods_price', <?php echo $this->_var['goods']['goods_id']; ?>)"><?php echo $this->_var['goods']['shop_price']; ?></span></td>
                                    <td><span onclick="listTable.edit(this, 'edit_goods_number', <?php echo $this->_var['goods']['goods_id']; ?>)"><?php echo $this->_var['goods']['goods_number']; ?></span></td>
                                    <td><span onclick="listTable.edit(this, 'edit_sort_order', <?php echo $this->_var['goods']['goods_id']; ?>)"><?php echo $this->_var['goods']['sort_order']; ?></span></td>
                                    <td><img src="images/<?php if ($this->_var['goods']['is_on_sale']): ?>yes<?php else: ?>no<?php endif; ?>.gif" onclick="listTable.toggle(this, 'toggle_on_sale', <?php echo $this->_var['goods']['goods_id']; ?>)" title="<?php echo $this->_var['lang']['click']; ?>" class="pointer"/></td>
                                    <td class="audit_status">
                                        <?php if ($this->_var['goods']['review_status'] == 1): ?>
                                        <font class="org2"><?php echo $this->_var['lang']['not_audited']; ?></font>
                                        <?php elseif ($this->_var['goods']['review_status'] == 2): ?>
                                        <font class="red"><?php echo $this->_var['lang']['audited_not_adopt']; ?></font>
                                        <i class="tip yellow" title="<?php echo $this->_var['goods']['review_content']; ?>"><?php echo $this->_var['lang']['prompt']; ?></i>
                                        <?php elseif ($this->_var['goods']['review_status'] == 3 || $this->_var['goods']['review_status'] == 4): ?>
                                        <font class="blue"><?php echo $this->_var['lang']['audited_yes_adopt']; ?></font>
                                        <?php elseif ($this->_var['goods']['review_status'] == 5): ?>
                                        <font class="navy2"><?php echo $this->_var['lang']['wuxu_adopt']; ?></font>
                                        <?php endif; ?>
                                    </td>
                                    <td class="ecsc-table-handle tr">
                                        <span><a href="../goods.php?id=<?php echo $this->_var['goods']['goods_id']; ?>" target="_blank" class="btn-orange"><i class="icon-search"></i><p>查看</p></a></span>
                                        <span><a href="goods.php?act=edit&goods_id=<?php echo $this->_var['goods']['goods_id']; ?>" class="btn-blue"><i class="icon-edit"></i><p>编辑</p></a></span>
                                        <span><a href="goods.php?act=copy&goods_id=<?php echo $this->_var['goods']['goods_id']; ?>" class="btn-green"><i class="icon-copy"></i><p>复制</p></a></span>
                                        <?php if ($this->_var['specifications'] [ $this->_var['goods']['goods_type'] ] != ''): ?>
                                            <?php if ($this->_var['goods']['model_attr'] == 1): ?>
                                            <span><a href="goods_warehouse_attr.php?act=warehouse_list&goods_id=<?php echo $this->_var['goods']['goods_id']; ?>" title="<?php echo $this->_var['lang']['item_list']; ?>" class="btn-black"><i class="icon-list-ul"></i><p>货品</p></a></span>
                                            <?php elseif ($this->_var['goods']['model_attr'] == 2): ?>
                                            <span><a href="goods_area_attr.php?act=warehouse_list&goods_id=<?php echo $this->_var['goods']['goods_id']; ?>" title="<?php echo $this->_var['lang']['item_list']; ?>" class="btn-black"><i class="icon-list-ul"></i><p>货品</p></a></span>
                                            <?php else: ?>
                                            <span><a href="goods.php?act=product_list&goods_id=<?php echo $this->_var['goods']['goods_id']; ?>" title="<?php echo $this->_var['lang']['item_list']; ?>" class="btn-black"><i class="icon-list-ul"></i><p>货品</p></a></span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <span><a href="javascript:void(0);" onclick="listTable.remove(<?php echo $this->_var['goods']['goods_id']; ?>, '您确实要把该商品放入回收站吗？')" class="btn-red"><i class="icon-trash"></i><p>删除</p></a></span>
                                        <?php if ($this->_var['add_handler']): ?>
                                            <?php $_from = $this->_var['add_handler']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'handler');$this->_foreach['namehandler'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['namehandler']['total'] > 0):
    foreach ($_from AS $this->_var['handler']):
        $this->_foreach['namehandler']['iteration']++;
?>
                                                <span class="mt5"><a href="<?php echo $this->_var['handler']['url']; ?>&goods_id=<?php echo $this->_var['goods']['goods_id']; ?>" title="<?php echo $this->_var['handler']['title']; ?>" class="<?php if ($this->_foreach['namehandler']['iteration'] == 1): ?>btn-orange<?php elseif ($this->_foreach['namehandler']['iteration'] == 2): ?>btn-blue<?php elseif ($this->_foreach['namehandler']['iteration'] == 3): ?>btn-green<?php endif; ?>"><i class="<?php echo $this->_var['handler']['icon']; ?>"></i><p><?php echo $this->_var['handler']['title']; ?></p></a></span>
                                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr style="display: none;">
                                    <td colspan="20">
                                        <div class="ecsc-goods-sku ps-container">
                                        	<dl>
                                                <dt><?php echo $this->_var['lang']['lab_goods_sn']; ?></dt>
                                                <dd><div class="checkbox_items"><span onclick="listTable.edit(this, 'edit_goods_sn', <?php echo $this->_var['goods']['goods_id']; ?>)"><?php echo empty($this->_var['goods']['goods_sn']) ? $this->_var['lang']['n_a'] : $this->_var['goods']['goods_sn']; ?></span></div></dd>
                                            </dl>
                                        	<dl>
                                                <dt><?php echo $this->_var['lang']['lab_bar_code']; ?></dt>
                                                <dd><div class="checkbox_items"><span onclick="listTable.edit(this, 'edit_goods_bar_code', <?php echo $this->_var['goods']['goods_id']; ?>)"><?php echo empty($this->_var['goods']['bar_code']) ? $this->_var['lang']['n_a'] : $this->_var['goods']['bar_code']; ?></span></div></dd>
                                            </dl>
                                            <?php if ($this->_var['goods']['user_id']): ?>
                                            <dl>
                                                <dt><?php echo $this->_var['lang']['store_rec']; ?>：</dt>
                                                <dd>
                                                    <div class="checkbox_items">
                                                        <label><input type="checkbox" class="checkbox" name="store_best" value="1" onchange="get_ajax_act(this, '<?php echo $this->_var['goods']['goods_id']; ?>', 'toggle_store_best', 'goods');" <?php if ($this->_var['goods']['store_best']): ?>checked="checked"<?php endif; ?> /><span><?php echo $this->_var['lang']['best']; ?></span></label>
                                                        <label><input type="checkbox" class="checkbox" name="store_new" value="1" onchange="get_ajax_act(this, '<?php echo $this->_var['goods']['goods_id']; ?>', 'toggle_store_new', 'goods');" <?php if ($this->_var['goods']['store_new']): ?>checked="checked"<?php endif; ?> /><span><?php echo $this->_var['lang']['new']; ?></span></label>
                                                        <label><input type="checkbox" class="checkbox" name="store_hot" value="1" onchange="get_ajax_act(this, '<?php echo $this->_var['goods']['goods_id']; ?>', 'toggle_store_hot', 'goods');" <?php if ($this->_var['goods']['store_hot']): ?>checked="checked"<?php endif; ?> /><span><?php echo $this->_var['lang']['hot']; ?></span></label>
                                                    </div>
                                                </dd>
                                            </dl>
                                            <?php endif; ?>
                                            <dl>
                                                <dt><?php echo $this->_var['lang']['lab_goods_service']; ?></dt>
                                                <dd>
                                                    <div class="checkbox_items">
                                                        <label><input type="checkbox" class="checkbox" name="is_reality" value="1" onchange="get_ajax_act(this, '<?php echo $this->_var['goods']['goods_id']; ?>', 'toggle_is_reality', 'goods');" <?php if ($this->_var['goods']['goods_extend']['is_reality']): ?> checked="checked"<?php endif; ?> /><span><?php echo $this->_var['lang']['is_reality']; ?></span></label>
                                                        <label><input type="checkbox" class="checkbox" name="is_return" value="1" onchange="get_ajax_act(this, '<?php echo $this->_var['goods']['goods_id']; ?>', 'toggle_is_return', 'goods');" <?php if ($this->_var['goods']['goods_extend']['is_return']): ?> checked="checked"<?php endif; ?> /><span><?php echo $this->_var['lang']['is_return']; ?></span></label>
                                                        <label><input type="checkbox" class="checkbox" name="is_fast" value="1" onchange="get_ajax_act(this, '<?php echo $this->_var['goods']['goods_id']; ?>', 'toggle_is_fast', 'goods');" <?php if ($this->_var['goods']['goods_extend']['is_fast']): ?> checked="checked"<?php endif; ?> /><span><?php echo $this->_var['lang']['is_fast']; ?></span></label>
                                                    </div>
                                                </dd>
                                            </dl>
                                            <dl>
                                                <dt><?php echo $this->_var['lang']['lab_is_free_shipping']; ?></dt>
                                                <dd><div class="checkbox_items"><label><input type="checkbox" class="checkbox" name="is_shipping" value="1" onchange="get_ajax_act(this, '<?php echo $this->_var['goods']['goods_id']; ?>', 'toggle_is_shipping', 'goods');" <?php if ($this->_var['goods']['is_shipping']): ?>checked="checked"<?php endif; ?> /><span><?php echo $this->_var['lang']['free_shipping']; ?></span></label></div></dd>
                                            </dl>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; else: ?>
                            <tr><td colspan="20" class="no-records"><?php echo $this->_var['lang']['no_records']; ?></td></tr>
                            <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>	
                            <tfoot>
                                <tr>
                                    <th class="tc"><input type="checkbox" id="all2" name="checkboxes[]" class="checkall" onclick='listTable.selectAll(this, "checkboxes")'></th>
                                    <th colspan="20" class="tl"><label for="all2">全选</label>
                                        <a href="javascript:void(0);" class="ecsc-btn-mini" ectype="batchbutton" uri="#" onclick="changeAction('trash')"><i class="icon-trash"></i>回收站</a>
										<a href="javascript:void(0);" class="ecsc-btn-mini" ec_type="batchbutton" uri="#" onclick="changeAction('on_sale')"><i class="icon-cloud-upload"></i>上架</a>
                                        <a href="javascript:void(0);" class="ecsc-btn-mini" ec_type="batchbutton" uri="#" onclick="changeAction('not_on_sale')"><i class="icon-level-down"></i>下架</a>
                                    </th>
                                </tr>
                            </tfoot>
                            </tbody>
                        </table>
                        <?php echo $this->fetch('page.dwt'); ?>
                    </div>
                </form>
                <?php if ($this->_var['full_page']): ?>
            </div>
        </div>
    </div>
</div>
<!--高级搜索 start-->
<?php echo $this->fetch('library/goods_search.lbi'); ?>
<!--高级搜索 end-->
    
<?php echo $this->fetch('library/seller_footer.lbi'); ?>
<script type="text/javascript">
listTable.recordCount = <?php echo $this->_var['record_count']; ?>;
listTable.pageCount = <?php echo $this->_var['page_count']; ?>;

<?php $_from = $this->_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
listTable.filter.<?php echo $this->_var['key']; ?> = '<?php echo $this->_var['item']; ?>';
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>

function searchGoodsList()
{
	listTable.filter['review_status'] = Utils.trim(document.forms['searchFormList'].elements['review_status'].value);
	listTable.filter['keyword'] = Utils.trim(document.forms['searchFormList'].elements['keyword'].value);
	listTable.filter['page'] = 1;

	listTable.loadList();
}

function confirmSubmit(frm, ext)
{
  if (frm.elements['type'].value == 'trash')
  {
	  return confirm(batch_trash_confirm);
  }
  else if (frm.elements['type'].value == 'not_on_sale')
  {
	  return confirm(batch_no_on_sale);
  }
  else if (frm.elements['type'].value == 'move_to')
  {
	  ext = (ext == undefined) ? true : ext;
	  return ext && document.getElementById('target_cat').value != 0;
  }
  else if (frm.elements['type'].value == '')
  {
	  return false;
  }
  else
  {
	  return true;
  }
}

function changeAction(type)
{
  var frm = document.forms['listForm'];
  frm.elements['type'].value = type;
  if(confirmSubmit(frm, false))
  {
	frm.submit();
  }
}
  
//单选勾选
function get_ajax_act(t, goods_id, act, FileName){
	
	if(t.checked == false){
		t.value = 0;
	}
	
	Ajax.call(FileName + '.php?act=' + act, 'id=' + goods_id + '&val=' + t.value, act_response, 'POST', 'JSON');
}

function act_response(result){
}  
  
$(function(){
    // 获取商品列表
    $(document).on("click",'i[ectype="GoodsList"]',function(){
            if($(this).hasClass("icon-plus-sign")){
                $(this).removeClass('icon-plus-sign').addClass('icon-minus-sign');
                var parenttr = $(this).parents('tr');
                parenttr.next().show()
            }else{
                $(this).removeClass('icon-minus-sign').addClass('icon-plus-sign');
                $(this).parents('tr').next().hide();
            }
        }
    );
	$('.tip').poshytip({
		className: 'tip-yellowsimple',
		showTimeout:300,
		alignTo: 'target',
		alignX: 'center',
		alignY: 'top',
		offsetY: 5
	});
});
</script>
</body>
</html>
<?php endif; ?>