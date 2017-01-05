<?php if ($this->_var['full_page']): ?>
<!doctype html>
<html>
<head><?php echo $this->fetch('library/admin_html_head.lbi'); ?></head>

<body class="iframe_body">
	<div class="warpper">
		<div class="title"><a href="merchants_steps.php?act=list" class="s-back"><?php echo $this->_var['lang']['back']; ?></a>商家 - <?php echo $this->_var['ur_here']; ?></div>
        <div class="content">
        	<div class="explanation" id="explanation">
            	<div class="ex_tit"><i class="sc_icon"></i><h4>操作提示</h4><span id="explanationZoom" title="收起提示"></span></div>
                <ul>
                	<li>流程内容信息管理。</li>
                    <li>可编辑流程内容和添加流程内容。</li>
                </ul>
            </div>
            <div class="flexilist">
            	<div class="common-head">
                	<div class="fl">
                        <a href="<?php echo $this->_var['action_link']['href']; ?>"><div class="fbutton"><div class="add" title="<?php echo $this->_var['action_link']['text']; ?>"><span><i class="icon icon-plus"></i><?php echo $this->_var['action_link']['text']; ?></span></div></div></a>
                    </div>
                   	<div class="refresh">
                    	<div class="refresh_tit" title="刷新数据"><i class="icon icon-refresh"></i></div>
                    	<div class="refresh_span">刷新 - 共<?php echo $this->_var['record_count']; ?>条记录</div>
                    </div>
                    <div class="search">
                    	<div class="input">
                        	<input type="text" name="keywords" class="text nofocus" placeholder="内容标题" autocomplete="off" /><button class="btn" name="secrch_btn"></button>
                        </div>
                    </div>
                </div>
                <div class="common-content">
                	<div class="list-div" id="listDiv">
<?php endif; ?>
                    	<table cellpadding="0" cellspacing="0" border="0">
                        	<thead>
                            	<tr>
                                	<th width="3%" class="sign"><div class="tDiv"><input type="checkbox" name="all_list" class="checkbox" id="all_list" /><label for="all_list" class="checkbox_stars"></label></div></th>
                                    <th width="5%"><div class="tDiv"><?php echo $this->_var['lang']['record_id']; ?></div></th>
                                    <th width="20%"><div class="tDiv"><?php echo $this->_var['lang']['fields_titles']; ?></div></th>
                                    <th width="15%"><div class="tDiv"><?php echo $this->_var['lang']['fields_steps']; ?></div></th>
                                    <th width="32%"><div class="tDiv"><?php echo $this->_var['lang']['fields_special_instructions']; ?></div></th>
                                    <th width="10%"><div class="tDiv"><?php echo $this->_var['lang']['fields_special_type']; ?></div></th>
                                    <th width="15%" class="handle"><?php echo $this->_var['lang']['handler']; ?></th>
                                </tr>
                            </thead>
                            <tbody>
							<?php $_from = $this->_var['title_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'title');if (count($_from)):
    foreach ($_from AS $this->_var['title']):
?>
                            	<tr>
                                	<td class="sign"><div class="tDiv"><input type="checkbox" name="checkbox" class="checkbox" id="checkbox_<?php echo $this->_var['title']['tid']; ?>" /><label for="checkbox_<?php echo $this->_var['title']['tid']; ?>" class="checkbox_stars"></label></div></td>
                                    <td><div class="tDiv"><?php echo $this->_var['title']['tid']; ?></div></td>
                                	<td><div class="tDiv"><?php echo $this->_var['title']['fields_titles']; ?></div></td>
                                    <td><div class="tDiv"><?php echo $this->_var['title']['fields_steps']; ?></div></td>
                                    <td><div class="tDiv"><?php echo $this->_var['title']['fields_special']; ?></div></td>
                                    <td><div class="tDiv"><?php if ($this->_var['title']['special_type'] == 1): ?><?php echo $this->_var['lang']['merchants_top']; ?><?php elseif ($this->_var['title']['special_type'] == 2): ?><?php echo $this->_var['lang']['merchants_bottom']; ?><?php else: ?><?php echo $this->_var['lang']['merchants_not']; ?><?php endif; ?></div></td>
                                    <td class="handle">
                                        <div class="tDiv a2">
										  <a href="merchants_steps.php?act=title_edit&id=<?php echo $this->_var['title']['tid']; ?>"  class="btn_edit"><i class="icon icon-edit"></i><?php echo $this->_var['lang']['edit']; ?></a>
										  <a href="javascript:confirm_redirect('<?php echo $this->_var['lang']['remove_confirm_process']; ?>', 'merchants_steps.php?act=titleList_remove&id=<?php echo $this->_var['title']['tid']; ?>')"  class="btn_trash" title="<?php echo $this->_var['lang']['remove']; ?>"><i class="icon icon-trash"></i><?php echo $this->_var['lang']['remove']; ?></a>
										</div>
                                    </td>
                                </tr>
						    <?php endforeach; else: ?>
								<tr><td class="no-records" colspan="10"><?php echo $this->_var['lang']['no_records']; ?></td></tr>
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
	//列表导航栏设置下路选项
	$(".ps-container").perfectScrollbar();
		
	//分页传值
	listTable.recordCount = '<?php echo $this->_var['record_count']; ?>';
	listTable.pageCount = '<?php echo $this->_var['page_count']; ?>';
	listTable.query = 'query_title';

	<?php $_from = $this->_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
	listTable.filter.<?php echo $this->_var['key']; ?> = '<?php echo $this->_var['item']; ?>';
	<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
</script>
</body>
</html>
<?php endif; ?>