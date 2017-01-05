<!doctype html>
<html>
<head><?php echo $this->fetch('library/admin_html_head.lbi'); ?></head>

<body class="iframe_body">
	<div class="warpper">
    	<div class="title">
            商品 - <?php echo $this->_var['ur_here']; ?>
        </div>
        <div class="content">
        	<div class="tabs_info">
            	<ul>
                    <li <?php if ($this->_var['menu_select']['current'] == '03_category_list'): ?>class="curr"<?php endif; ?>><a href="category.php?act=list">平台商品分类</a></li>
                    <li <?php if ($this->_var['menu_select']['current'] == '03_store_category_list'): ?>class="curr"<?php endif; ?>><a href="category_store.php?act=list">店铺商品分类</a></li>
                </ul>
            </div>		
        	<div class="explanation" id="explanation">
            	<div class="ex_tit"><i class="sc_icon"></i><h4>操作提示</h4><span id="explanationZoom" title="收起提示"></span></div>
                <ul>
                	<li>展示了平台所有的商品分类。</li>
                    <li>可在列表直接增加下一级分类。</li>
                    <li>可在商品分类列表进行转移分类下的商品。</li>
                    <li>鼠标移动“设置”位置，可新增下一级分类、查看下一级分类、转移商品等操作</li>
                </ul>
            </div>
            <div class="flexilist">
            	<!--商品分类列表-->
                <div class="common-head">
                    <div class="fl">
					<?php if ($this->_var['parent_id'] > 0): ?>
                    	<a href="category.php?act=list&parent_id=<?php echo $this->_var['parent_id']; ?>&back_level=<?php echo $this->_var['level']; ?>"><div class="fbutton"><div class="add" title="返回上一级"><span><i class="icon icon-reply"></i>返回上一级</span></div></div></a>
					<?php endif; ?>
					<a href="category.php?act=add<?php if ($this->_var['parent_id']): ?>&parent_id=<?php echo $this->_var['parent_id']; ?><?php endif; ?>"><div class="fbutton"><div class="add" title="添加分类"><span><i class="icon icon-plus"></i>添加分类</span></div></div></a>
                    </div>
                </div>
                <div class="common-content">
                	<div class="list-div">
                    	<table cellpadding="0" cellspacing="0" border="0">
                        	<thead>
                            	<tr>
                                	<th width="8%"><div class="tDiv">级别(<?php echo $this->_var['cat_level']; ?>级)</div></th>
                                	<th width="20%"><div class="tDiv">分类名称</div></th>
                                    <th width="10%"><div class="tDiv">商品数量</div></th>
                                    <th width="10%"><div class="tDiv">数量单位</div></th>
                                    <th width="10%"><div class="tDiv">导航栏</div></th>
                                    <th width="10%"><div class="tDiv">是否显示</div></th>
                                    <th width="10%"><div class="tDiv">价格分级</div></th>
                                    <th width="10%"><div class="tDiv">排序</div></th>
                                    <th width="12%" class="handle">操作</th>
                                </tr>
                            </thead>
                            <tbody>
								<?php $_from = $this->_var['cat_info']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');if (count($_from)):
    foreach ($_from AS $this->_var['cat']):
?>
                            	<tr>
                                	<td>
                                    	<div class="tDiv first_setup">
                                        	<div class="setup_span">
                                            	<em><i class="icon icon-cog"></i>设置<i class="arrow"></i></em>
                                                <ul>
													<?php if ($this->_var['level'] < 2): ?>
                                                	<li><a href="category.php?act=add&parent_id=<?php echo $this->_var['cat']['cat_id']; ?>">新增下一级</a></li>
                                                    <li><a href="category.php?act=list&parent_id=<?php echo $this->_var['cat']['cat_id']; ?>&level=<?php echo $this->_var['level']; ?>">查看下一级</a></li>
													<?php endif; ?>
                                                    <li><a href="javascript:void(0);" ectype="transfer_goods" data-cid="<?php echo $this->_var['cat']['cat_id']; ?>">转移商品</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                    <td><div class="tDiv"><a href="goods.php?act=list&cat_id=<?php echo $this->_var['cat']['cat_id']; ?>" class="ftx-01"><?php echo $this->_var['cat']['cat_name']; ?></a></div></td>
                                    <td><div class="tDiv"><?php echo $this->_var['cat']['goods_num']; ?></div></td>
                                    <td><div class="tDiv"><input type="text" name="measure_unit" class="text w40" value="<?php echo $this->_var['cat']['measure_unit']; ?>" onkeyup="listTable.editInput(this, 'edit_measure_unit', <?php echo $this->_var['cat']['cat_id']; ?>)"/></div></td>
                                    <td>
                                    	<div class="tDiv">
                                        	<div class="switch <?php if ($this->_var['cat']['show_in_nav']): ?>active<?php endif; ?>" title="<?php if ($this->_var['cat']['show_in_nav']): ?>是<?php else: ?>否<?php endif; ?>" onclick="listTable.switchBt(this, 'toggle_show_in_nav', <?php echo $this->_var['cat']['cat_id']; ?>)">
                                            	<div class="circle"></div>
                                            </div>
                                            <input type="hidden" value="0" name="">
                                        </div>
                                    </td>
                                    <td>
                                    	<div class="tDiv">
                                        	<div class="switch <?php if ($this->_var['cat']['is_show']): ?>active<?php endif; ?>" title="<?php if ($this->_var['cat']['is_show']): ?>是<?php else: ?>否<?php endif; ?>" onclick="listTable.switchBt(this, 'toggle_is_show', <?php echo $this->_var['cat']['cat_id']; ?>)">
                                            	<div class="circle"></div>
                                            </div>
                                            <input type="hidden" value="0" name="">
                                        </div>
                                    </td>
                                    <td><div class="tDiv"><input type="text" name="grade" class="text w40" value="<?php echo $this->_var['cat']['grade']; ?>" onkeyup="listTable.editInput(this, 'edit_grade', <?php echo $this->_var['cat']['cat_id']; ?>)"/></div></td>
                                    <td><div class="tDiv"><input type="text" name="sort_order" class="text w40" value="<?php echo $this->_var['cat']['sort_order']; ?>" onkeyup="listTable.editInput(this, 'edit_sort_order', <?php echo $this->_var['cat']['cat_id']; ?>)"/></div></td>
                                    <td class="handle">
                                        <div class="tDiv a2">
                                            <a href="category.php?act=edit&amp;cat_id=<?php echo $this->_var['cat']['cat_id']; ?>" class="btn_edit"><i class="icon icon-edit"></i>编辑</a>
                                            <a href="javascript:remove_cat(<?php echo $this->_var['cat']['cat_id']; ?>,<?php echo $this->_var['cat']['level']; ?>);" class="btn_trash"><i class="icon icon-trash"></i>删除</a>
                                        </div>
                                    </td>
                                </tr>
								<?php endforeach; else: ?>
								<tr><td class="no-records" colspan="10"><?php echo $this->_var['lang']['no_records']; ?></td></tr>
								<?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            </tbody>                         
                        </table>        
                    </div>
                </div>
                <!--商品分类列表end-->
            </div>
		</div>
	</div>
         <?php echo $this->fetch('library/pagefooter.lbi'); ?>
    <script type="text/javascript" src="js/jquery.purebox.js"></script>
    <script type="text/javascript">
		//转移分类
		$(document).on('click',"*[ectype='transfer_goods']",function(){
			var cat_id = $(this).data("cid");
			$.jqueryAjax("category.php", "act=move&cat_id="+cat_id, function(data){
				var content = data.content;
				pb({
					id:"transfer_dialog",
					title:"转移商品",
					width:732,
					content:content,
					ok_title:"开始转移",
					cl_title:"重置",
					drag:false,
					foot:true,
					onOk:function(){
						$("#moveCategory").submit();
					}
				});
				$.category();  //分类选择
			});
		});
		
		function remove_cat(cat_id,level){
			if (confirm('确定删除吗')) {
			   Ajax.call('category.php?is_ajax=1&act=remove', "cat_id="+cat_id+"&level="+level, remove_catResponse, "GET", "JSON");
			}
		}
	
		function remove_catResponse(result){
			if(result.error == 2){
				alert(result.massege);
			}else{
				window.location.reload();
				//$("#"+result.level+"_"+result.cat_id).remove();
			}
		}	
    </script>
</body>
</html>
