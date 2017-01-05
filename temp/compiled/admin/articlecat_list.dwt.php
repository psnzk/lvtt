<?php if ($this->_var['full_page']): ?>
<!doctype html>
<html>
<head><?php echo $this->fetch('library/admin_html_head.lbi'); ?></head>

<body class="iframe_body">
	<div class="warpper">
    	<div class="title">文章 - <?php echo $this->_var['ur_here']; ?></div>
        <div class="content">
        	<div class="explanation" id="explanation">
            	<div class="ex_tit"><i class="sc_icon"></i><h4>操作提示</h4><span id="explanationZoom" title="收起提示"></span></div>
                <ul>
                	<li>该页面展示所有文章分类。</li>
                    <li>可添加子分类以及编辑修改、删除分类。</li>
                </ul>
            </div>
            <div class="flexilist">
            	<!--商品分类列表-->
                <div class="common-head">
                    <div class="fl">
                    	<a href="<?php echo $this->_var['action_link']['href']; ?>"><div class="fbutton"><div class="add" title="<?php echo $this->_var['action_link']['text']; ?>"><span><i class="icon icon-plus"></i><?php echo $this->_var['action_link']['text']; ?></span></div></div></a>
                    </div>
                    <?php if ($this->_var['action_link1']): ?>
                    <div class="fl">
                    	<a href="<?php echo $this->_var['action_link1']['href']; ?>"><div class="fbutton"><div class="reply" title="返回上级"><span><i class="icon icon-reply"></i>返回上级</span></div></div></a>
                    </div>
                    <?php endif; ?>
                    <div class="refresh">
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
                                    <th width="10%"></th>
                                    <th width="20%"><div class="tDiv"><?php echo $this->_var['lang']['cat_name']; ?></div></th>
                                    <th width="10%"><div class="tDiv"><?php echo $this->_var['lang']['type']; ?></div></th>
                                    <th width="28%"><div class="tDiv"><?php echo $this->_var['lang']['cat_desc']; ?></div></th>
                                    <th width="10%"><div class="tDiv"><?php echo $this->_var['lang']['sort_order']; ?></div></th>
                                    <th width="10%"><div class="tDiv"><?php echo $this->_var['lang']['show_in_nav']; ?></div></th>
                                    <th width="12%" class="handle"><?php echo $this->_var['lang']['handler']; ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $_from = $this->_var['articlecat']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');if (count($_from)):
    foreach ($_from AS $this->_var['cat']):
?>
                            	<tr>
                                    <td>
                                    	<div class="tDiv first_setup">
                                        	<div class="setup_span">
                                            	<em><i class="icon icon-cog"></i>设置<i class="arrow"></i></em>
                                                <ul>
                                                    <li><a href="<?php echo $this->_var['cat']['add_child']; ?>">新增下一级</a></li>
                                                    <li><a href="<?php echo $this->_var['cat']['child_url']; ?>">查看下一级</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                    <td><div class="tDiv"><a href="article.php?act=list&amp;cat_id=<?php echo $this->_var['cat']['cat_id']; ?>" class="ftx-01"><strong><?php echo htmlspecialchars($this->_var['cat']['cat_name']); ?></strong></a></div></td>
                                    <td><div class="tDiv"><?php echo htmlspecialchars($this->_var['cat']['type_name']); ?></div></td>
                                    <td><div class="tDiv"><?php echo htmlspecialchars($this->_var['cat']['cat_desc']); ?></div></td>
                                    <td><div class="tDiv"><input name="sort_order" class="text w40" value="<?php echo $this->_var['cat']['sort_order']; ?>" onkeyup="listTable.editInput(this, 'edit_sort_order',<?php echo $this->_var['cat']['cat_id']; ?> )" type="text"></div></td>
                                    <td>
                                    	<div class="tDiv">
                                            <div class="switch <?php if ($this->_var['cat']['show_in_nav'] == 1): ?>active<?php endif; ?>" title="<?php if ($this->_var['cat']['show_in_nav'] == 1): ?>是<?php else: ?>否<?php endif; ?>" onclick="listTable.switchBt(this, 'toggle_show_in_nav', <?php echo $this->_var['cat']['cat_id']; ?>)">
                                            	<div class="circle"></div>
                                            </div>
                                            <input type="hidden" value="0" name="">
                                        </div>
                                    </td>
                                    <td class="handle">
                                        <div class="tDiv a2">
                                            <a href="articlecat.php?act=edit&amp;id=<?php echo $this->_var['cat']['cat_id']; ?>" class="btn_edit"><i class="icon icon-edit"></i><?php echo $this->_var['lang']['edit']; ?></a>
                                            <?php if ($this->_var['cat']['cat_type'] != 2 && $this->_var['cat']['cat_type'] != 3 && $this->_var['cat']['cat_type'] != 4): ?>
                                            <a href="javascript:;" onclick="listTable.remove(<?php echo $this->_var['cat']['cat_id']; ?>, '<?php echo $this->_var['lang']['drop_confirm']; ?>')" title="<?php echo $this->_var['lang']['remove']; ?>" class="btn_trash"><i class="icon icon-trash"></i><?php echo $this->_var['lang']['remove']; ?></a>
                                            <?php endif; ?>
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
                <!--商品分类列表end-->
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
		//列表导航栏设置下路选项
    	$(".ps-container").perfectScrollbar();
		
    </script>     
</body>
</html>
<?php endif; ?>
