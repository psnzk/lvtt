<?php if ($this->_var['full_page']): ?>
<!doctype html>
<html>
<head><?php echo $this->fetch('library/admin_html_head.lbi'); ?></head>

<body class="iframe_body">
	<div class="warpper">
    	<div class="title">手机-<?php echo $this->_var['ur_here']; ?></div>
        <div class="content">
        	<div class="explanation" id="explanation">
            	<div class="ex_tit"><i class="sc_icon"></i><h4>操作提示</h4><span id="explanationZoom" title="收起提示"></span></div>
                <ul>
                	<li>您需要到系统设置->计划任务中开启该功能后才能使用。</li>
                    <li>标识“<em>*</em>”的选项为必填项，其余为选填项。</li>
                </ul>
            </div>
            <div class="flexilist">
            	<!--商品分类列表-->
                <div class="common-head">
                    <div class="fl">
                    	<a href="<?php echo $this->_var['action_link']['href']; ?>"><div class="fbutton"><div class="add" title="<?php echo $this->_var['action_link']['text']; ?>"><span><i class="icon icon-plus"></i><?php echo $this->_var['action_link']['text']; ?></span></div></div></a>
                    </div>
                    <div class="refresh<?php if (! $this->_var['action_link']): ?> ml0<?php endif; ?>">
                    	<div class="refresh_tit" title="刷新数据"><i class="icon icon-refresh"></i></div>
                    	<div class="refresh_span">刷新 - 共<?php echo $this->_var['record_count']; ?>条记录</div>
                    </div>
                </div>
                <div class="common-content">
                	<div class="list-div" id="listDiv">
                        <?php endif; ?>
                    	<table cellpadding="0" cellspacing="0" border="0">
                            <thead>
                                <tr>
                                    <th width="25%"><div class="tDiv"><?php echo $this->_var['lang']['item_name']; ?></div></th>
                                    <th width="15%"><div class="tDiv"><?php echo $this->_var['lang']['item_ifshow']; ?></div></th>
                                    <th width="15%"><div class="tDiv"><?php echo $this->_var['lang']['item_opennew']; ?></div></th>
                                    <th width="15%"><div class="tDiv"><?php echo $this->_var['lang']['item_vieworder']; ?></div></th>
                                    <th width="15%"><div class="tDiv"><?php echo $this->_var['lang']['item_type']; ?></div></th>
                                    <th width="15%" class="handle"><?php echo $this->_var['lang']['handler']; ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $_from = $this->_var['navdb']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'val');if (count($_from)):
    foreach ($_from AS $this->_var['val']):
?>
                            	<tr>
                                    <td><div class="tDiv"><!-- <?php if ($this->_var['val']['id']): ?> --><?php echo $this->_var['val']['name']; ?><!-- <?php else: ?> -->&nbsp;<!-- <?php endif; ?> --></div></td>
                                    <td>
                                    	<div class="tDiv">
                                            <div class="switch <?php if ($this->_var['val']['ifshow']): ?>active<?php endif; ?>" title="<?php if ($this->_var['val']['ifshow']): ?>是<?php else: ?>否<?php endif; ?>" onclick="listTable.switchBt(this, 'toggle_ifshow', <?php echo $this->_var['val']['id']; ?>)">
                                            	<div class="circle"></div>
                                            </div>
                                            <input type="hidden" value="0" name="">
                                        </div>
                                    </td>
                                    <td>
                                    	<div class="tDiv">
                                            <div class="switch <?php if ($this->_var['val']['opennew']): ?>active<?php endif; ?>" title="<?php if ($this->_var['val']['opennew']): ?>是<?php else: ?>否<?php endif; ?>" onclick="listTable.switchBt(this, 'toggle_opennew', <?php echo $this->_var['val']['id']; ?>)">
                                            	<div class="circle"></div>
                                            </div>
                                            <input type="hidden" value="0" name="">
                                        </div>
                                    </td>
                                    <td><div class="tDiv"><input name="sort_order" class="text w40" value="<?php echo $this->_var['val']['vieworder']; ?>" onkeyup="listTable.editInput(this, 'edit_sort_order',<?php echo $this->_var['val']['id']; ?> )" type="text"></div></td>
                                    <td><div class="tDiv"><?php echo $this->_var['lang'][$this->_var['val']['type']]; ?></div></td>
                                    <td class="handle">
                                        <div class="tDiv a2">
                                            <a href="touch_navigator.php?act=edit&id=<?php echo $this->_var['val']['id']; ?>" title="<?php echo $this->_var['lang']['edit']; ?>" class="btn_edit"><i class="icon icon-edit"></i><?php echo $this->_var['lang']['edit']; ?></a>
                                            <a href="javascript:confirm_redirect('<?php echo $this->_var['lang']['ckdel']; ?>', 'touch_navigator.php?act=del&id=<?php echo $this->_var['val']['id']; ?>')" title="<?php echo $this->_var['lang']['remove']; ?>" class="btn_trash"><i class="icon icon-trash"></i>删除</a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; else: ?>
                                <tr><td class="no-records" colspan="12"><?php echo $this->_var['lang']['no_records']; ?></td></tr>
                                <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            </tbody>
                            <tfoot>
                            	<tr>
                                    <td colspan="12">
                                    	<div class="list-page">
                                            <?php echo $this->fetch('library/page.lbi'); ?>
                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                        <?php if ($this->_var['full_page']): ?>
                    </div>
                </div>
            </div>
	</div>
	</div>
 <?php echo $this->fetch('library/pagefooter.lbi'); ?>
<script type="text/javascript">

listTable.recordCount = '<?php echo $this->_var['record_count']; ?>';
listTable.pageCount = '<?php echo $this->_var['page_count']; ?>';

<?php $_from = $this->_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
listTable.filter.<?php echo $this->_var['key']; ?> = '<?php echo $this->_var['item']; ?>';
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    
$(".ps-container").perfectScrollbar();
</script>     
</body>
</html>
<?php endif; ?>
