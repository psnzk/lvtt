<?php if ($this->_var['full_page']): ?>
<!doctype html>
<html>
<head><?php echo $this->fetch('library/admin_html_head.lbi'); ?></head>

<body class="iframe_body">
	<div class="warpper">
    	<div class="title">商品 - <?php echo $this->_var['ur_here']; ?></div>
        <div class="content">	
        	<div class="explanation" id="explanation">
            	<div class="ex_tit"><i class="sc_icon"></i><h4>操作提示</h4><span id="explanationZoom" title="收起提示"></span></div>
                <ul>
                	<li>展示了所有用户评论商品的相关信息。</li>
                    <li>可以屏蔽评论或者删除评论，也可以直接回复评论。</li>
                </ul>
            </div>
            <div class="flexilist">
            	<!--商品分类列表-->
                <div class="common-head">
                    <div class="refresh ml0">
                    	<div class="refresh_tit" title="刷新数据"><i class="icon icon-refresh"></i></div>
                    	<div class="refresh_span">刷新 - 共<?php echo $this->_var['record_count']; ?>条记录</div>
                    </div>
					<div class="search">
                    	<div class="input">
                        	<input type="text" name="keywords" class="text nofocus" placeholder="<?php echo $this->_var['lang']['search_comment']; ?>" autocomplete="off">
							<button class="btn" name="secrch_btn"></button>
                        </div>
                    </div>
                </div>
                <div class="common-content">
					<form method="POST" action="comment_manage.php?act=batch_drop" name="listForm" onsubmit="return confirm_bath()">
                	<div class="list-div" id="listDiv">
                    	<div class="flexigrid ht_goods_list">
						<?php endif; ?>
                    	<table cellpadding="0" cellspacing="0" border="0">
                        	<thead>
                            	<tr>
                                	<th width="3%" class="sign"><div class="tDiv"><input type="checkbox" name="all_list" class="checkbox" id="all_list" /><label for="all_list" class="checkbox_stars"></label></div></th>
                                	<th width="5%"><div class="tDiv">编号</div></th>
                                    <th width="10%"><div class="tDiv">用户名</div></th>
                                    <th width="10%"><div class="tDiv">商家名称</div></th>
                                    <th width="6%"><div class="tDiv">类型</div></th>
                                    <th width="25%"><div class="tDiv">评论对象</div></th>
                                    <th width="10%"><div class="tDiv">IP地址</div></th>
                                    <th width="10%"><div class="tDiv">评论时间</div></th>
                                    <th width="7%"><div class="tDiv">是否显示</div></th>
                                    <th class="handle">操作</th>
                                </tr>
                            </thead>
                            <tbody>
								<?php $_from = $this->_var['comment_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'comment');if (count($_from)):
    foreach ($_from AS $this->_var['comment']):
?>
                            	<tr>
									<td class="sign">
                                        <div class="tDiv">
                                            <input type="checkbox" name="checkboxes[]" value="<?php echo $this->_var['comment']['comment_id']; ?>" class="checkbox" id="checkbox_<?php echo $this->_var['comment']['comment_id']; ?>" />
                                            <label for="checkbox_<?php echo $this->_var['comment']['comment_id']; ?>" class="checkbox_stars"></label>
                                        </div>
                                    </td>
                                    <td><div class="tDiv"><?php echo $this->_var['comment']['comment_id']; ?></div></td>
									<td><div class="tDiv"><?php if ($this->_var['comment']['user_name']): ?><?php echo $this->_var['comment']['user_name']; ?><?php else: ?><?php echo $this->_var['lang']['anonymous']; ?><?php endif; ?></div></td>
									<td><div class="tDiv"><?php if ($this->_var['comment']['ru_name']): ?><font style="color:#F00;"><?php echo $this->_var['comment']['ru_name']; ?></font><?php else: ?><?php echo $this->_var['lang']['self']; ?><?php endif; ?></div></td>
									<td><div class="tDiv"><?php echo $this->_var['lang']['type'][$this->_var['comment']['comment_type']]; ?></div></td>
									<td><div class="tDiv"><a href="../<?php if ($this->_var['comment']['comment_type'] == '0' || $this->_var['comment']['comment_type'] == '2'): ?>goods<?php else: ?>article<?php endif; ?>.php?id=<?php echo $this->_var['comment']['id_value']; ?>" target="_blank"><?php echo $this->_var['comment']['title']; ?></a></div></td>
									<td><div class="tDiv"><?php echo $this->_var['comment']['ip_address']; ?></div></td>
									<td><div class="tDiv"><?php echo $this->_var['comment']['add_time']; ?></div></td>
									<!--<td><div class="tDiv"><?php if ($this->_var['comment']['status'] == 0): ?><?php echo $this->_var['lang']['hidden']; ?><?php else: ?><?php echo $this->_var['lang']['display']; ?><?php endif; ?></div></td>-->
									<td>
                                    	<div class="tDiv">
                                        	<div class="switch <?php if ($this->_var['comment']['status']): ?>active<?php endif; ?>" title="<?php if ($this->_var['comment']['status']): ?>是<?php else: ?>否<?php endif; ?>" onclick="listTable.switchBt(this, 'toggle_status', <?php echo $this->_var['comment']['comment_id']; ?>)">
                                            	<div class="circle"></div>
                                            </div>
                                            <input type="hidden" value="0" name="">
                                        </div>
									</td>
									<td class="handle">
                                        <div class="tDiv ht_tdiv">
                                            <a href="comment_manage.php?act=reply&amp;id=<?php echo $this->_var['comment']['comment_id']; ?>" class="btn_see"><i class="sc_icon sc_icon_see"></i>查看</a>
                                            <a href="javascript:;" onclick="listTable.remove(<?php echo $this->_var['comment']['comment_id']; ?>, '<?php echo $this->_var['lang']['drop_confirm']; ?>')" class="btn_trash"><i class="icon icon-trash"></i>删除</a>
                                        </div>
                                    </td>
                                </tr>
								<?php endforeach; else: ?>
								<tr><td class="no-records" colspan="20"><?php echo $this->_var['lang']['no_records']; ?></td></tr>								
								<?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            </tbody>
                            <tfoot>
                            	<tr>
									<td colspan="12">
                                        <div class="tDiv">
                                            <div class="tfoot_btninfo">
                                                <div class="shenhe">
                                                    <div id="sel_action" class="imitate_select select_w120">
                                                        <div class="cite">请选择</div>
                                                        <ul>
                                                            <li><a href="javascript:;" data-value="remove" class="ftx-01"><?php echo $this->_var['lang']['drop_select']; ?></a></li>
                                                            <li><a href="javascript:;" data-value="allow" class="ftx-01"><?php echo $this->_var['lang']['allow']; ?></a></li>
                                                            <li><a href="javascript:;" data-value="deny" class="ftx-01"><?php echo $this->_var['lang']['forbid']; ?></a></li>
                                                        </ul>
                                                        <input name="sel_action" type="hidden" value="remove" id="">
                                                    </div>
                                                    <input type="hidden" name="act" value="batch" />
                                                    <input type="submit" name="drop" id="btnSubmit" value="<?php echo $this->_var['lang']['button_submit']; ?>" class="btn btn_disabled" disabled="true" ectype="btnSubmit" />
                                                </div>										
                                            </div>
                                            <div class="list-page">
                                                <?php echo $this->fetch('library/page.lbi'); ?>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table> 
						<?php if ($this->_var['full_page']): ?>
                        </div>
                    </div>
					</form>
                </div>
                <!--商品分类列表end-->
            </div>
		</div>
	</div>
 <?php echo $this->fetch('library/pagefooter.lbi'); ?>
    <script type="text/javascript" src="js/jquery.purebox.js"></script>
    <script type="text/javascript">	
	//列表导航栏设置下路选项
	$(".ps-container").perfectScrollbar();
    </script>  
	<script type="text/javascript">
	<!--
	  listTable.recordCount = '<?php echo $this->_var['record_count']; ?>';
	  listTable.pageCount = '<?php echo $this->_var['page_count']; ?>';
	  cfm = new Object();
	  cfm['allow'] = '<?php echo $this->_var['lang']['cfm_allow']; ?>';
	  cfm['remove'] = '<?php echo $this->_var['lang']['cfm_remove']; ?>';
	  cfm['deny'] = '<?php echo $this->_var['lang']['cfm_deny']; ?>';

	  <?php $_from = $this->_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
	  listTable.filter.<?php echo $this->_var['key']; ?> = '<?php echo $this->_var['item']; ?>';
	  <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>

	  
	  /**
	   * 搜索评论
	   */
	  function searchComment()
	  {
		  var keyword = Utils.trim(document.forms['searchForm'].elements['keyword'].value);
		
		  if (keyword.length > 0)
		  {
			listTable.filter['keywords'] = keyword;
			listTable.filter.page = 1;
			listTable.loadList();
		  }
		  else
		  {
			  document.forms['searchForm'].elements['keyword'].focus();
		  }
	  }
	  

	  function confirm_bath()
	  {
		var action = document.forms['listForm'].elements['sel_action'].value;

		return confirm(cfm[action]);
	  }
	//-->
	</script>
</body>
</html>
<?php endif; ?>