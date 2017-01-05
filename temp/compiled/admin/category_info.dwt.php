<!doctype html>
<html>
<head><?php echo $this->fetch('library/admin_html_head.lbi'); ?></head>

<body class="iframe_body">
	<div class="warpper">
    	<div class="title"><a href="<?php echo $this->_var['action_link']['href']; ?>" class="s-back"><?php echo $this->_var['lang']['back']; ?></a>商品 - <?php echo $this->_var['ur_here']; ?></div>
        <div class="content">
        	<div class="explanation" id="explanation">
            	<div class="ex_tit"><i class="sc_icon"></i><h4>操作提示</h4><span id="explanationZoom" title="收起提示"></span></div>
                <ul>
                	<li>标识“<em>*</em>”的选项为必填项，其余为选填项。</li>
                    <li>请按提示信息填写每一个字段。</li>
                </ul>
            </div>
            <div class="flexilist">
                <div class="common-content">
                    <div class="mian-info">
                        <form action="category.php" method="post" name="theForm" enctype="multipart/form-data" id="category_info_form">
                            <div class="switch_info">
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['require_field']; ?>&nbsp;<?php echo $this->_var['lang']['cat_name']; ?>：</div>
                                    <div class="label_value">
									  <?php if ($this->_var['form_act'] == 'insert'): ?>
									  <textarea name="cat_name" cols="48" rows="3" class="textarea"><?php echo htmlspecialchars($this->_var['cat_info']['cat_name']); ?></textarea>
									  <div class="notic bf100"><?php echo $this->_var['lang']['category_name_notic']; ?></div>
									  <?php else: ?>
									  <input type='text' class="text" name='cat_name' maxlength="20" value='<?php echo htmlspecialchars($this->_var['cat_info']['cat_name']); ?>' size='27' />
									  <?php endif; ?>
                                      <div class="form_prompt"></div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="label">手机别名：</div>
                                    <div class="label_value">
                                        <input type='text' name='cat_alias_name' class="text" id="cat_alias_name" maxlength="20" value='<?php if ($this->_var['cat_info']['parent_id'] == 0): ?><?php echo htmlspecialchars($this->_var['cat_info']['cat_alias_name']); ?><?php endif; ?>' size='27' <?php if ($this->_var['cat_info']['parent_id'] != 0): ?>disabled="true"<?php endif; ?> />
                                        <div class="notic">（注：手机端专用）</div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['parent_id']; ?>：</div>
                                    <div class="label_value">
										<div class="search_select">
											<div class="categorySelect">
												<div class="selection">
													<input type="text" name="category_name" id="category_name" class="text w290 valid" value="<?php if ($this->_var['parent_category']): ?><?php echo $this->_var['parent_category']; ?><?php else: ?>顶级分类<?php endif; ?>" autocomplete="off" readonly data-filter="cat_name" />
													<input type="hidden" name="parent_id" id="category_id" value="<?php echo empty($this->_var['parent_id']) ? '0' : $this->_var['parent_id']; ?>" data-filter="cat_id" />
												</div>
												<div class="select-container w319" style="display:none;">
													<?php echo $this->fetch('library/filter_category.lbi'); ?>
												</div>
											</div>
										</div>
                                        <div class="notic">不选择分类默认为顶级分类</div>
                                    </div>
                                </div>								
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['measure_unit']; ?>：</div>
                                    <div class="label_value">
										<input type="text" class="text text_4" name='measure_unit' value='<?php echo $this->_var['cat_info']['measure_unit']; ?>' size="12" autocomplete="off" />
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['sort_order']; ?>：</div>
                                    <div class="label_value">
										<input type="text" class="text text_4" name='sort_order' <?php if ($this->_var['cat_info']['sort_order']): ?>value='<?php echo $this->_var['cat_info']['sort_order']; ?>'<?php else: ?> value="50"<?php endif; ?> size="15" autocomplete="off" />
                                    </div>
                                </div>	
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['is_show']; ?>：</div>
                                    <div class="label_value">
                                        <div class="checkbox_items">
                                            <div class="checkbox_item">
                                                <input type="radio" class="ui-radio" name="is_show" id="is_show_1" value="1" <?php if ($this->_var['cat_info']['is_show'] != 0): ?> checked="true" <?php endif; ?>  />
                                                <label for="is_show_1" class="ui-radio-label"><?php echo $this->_var['lang']['yes']; ?></label>
                                            </div>
                                            <div class="checkbox_item">
                                                <input type="radio" class="ui-radio" name="is_show" id="is_show_0" value="0" <?php if ($this->_var['cat_info']['is_show'] == 0): ?> checked="true" <?php endif; ?>  />
                                                <label for="is_show_0" class="ui-radio-label"><?php echo $this->_var['lang']['no']; ?></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['show_in_nav']; ?>：</div>
                                    <div class="label_value">
                                        <div class="checkbox_items">
                                            <div class="checkbox_item">
                                                <input type="radio" class="ui-radio" name="show_in_nav" id="show_in_nav_1" value="1" <?php if ($this->_var['cat_info']['show_in_nav'] != 0): ?> checked="true" <?php endif; ?>  />
                                                <label for="show_in_nav_1" class="ui-radio-label"><?php echo $this->_var['lang']['yes']; ?></label>
                                            </div>
                                            <div class="checkbox_item">
                                                <input type="radio" class="ui-radio" name="show_in_nav" id="show_in_nav_0" value="0" <?php if ($this->_var['cat_info']['show_in_nav'] == 0): ?> checked="true" <?php endif; ?>  />
                                                <label for="show_in_nav_0" class="ui-radio-label"><?php echo $this->_var['lang']['no']; ?></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>	
								<?php if ($this->_var['cat_info']['parent_id'] != 0): ?>
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['show_category_top']; ?>：</div>
                                    <div class="label_value">
                                        <div class="checkbox_items">
                                            <div class="checkbox_item">
                                                <input type="radio" class="ui-radio" name="is_top_show" id="is_top_show_1" value="1" <?php if ($this->_var['cat_info']['is_top_show'] != 0): ?> checked="true" <?php endif; ?>  />
                                                <label for="is_top_show_1" class="ui-radio-label"><?php echo $this->_var['lang']['yes']; ?></label>
                                            </div>
                                            <div class="checkbox_item">
                                                <input type="radio" class="ui-radio" name="is_top_show" id="is_top_show_0" value="0" <?php if ($this->_var['cat_info']['is_top_show'] == 0): ?> checked="true" <?php endif; ?>  />
                                                <label for="is_top_show_0" class="ui-radio-label"><?php echo $this->_var['lang']['no']; ?></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>	
								<?php endif; ?>
								<?php if ($this->_var['cat_info']['parent_id'] == 0): ?>
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['show_category_top_css']; ?>：</div>
                                    <div class="label_value">
                                        <div class="checkbox_items">
                                            <div class="checkbox_item">
                                                <input type="radio" class="ui-radio" name="is_top_style" id="is_top_style_1" value="1" <?php if ($this->_var['cat_info']['is_top_style'] != 0): ?> checked="true" <?php endif; ?>  />
                                                <label for="is_top_style_1" class="ui-radio-label"><?php echo $this->_var['lang']['yes']; ?></label>
                                            </div>
                                            <div class="checkbox_item">
                                                <input type="radio" class="ui-radio" name="is_top_style" id="is_top_style_0" value="0" <?php if ($this->_var['cat_info']['is_top_style'] == 0): ?> checked="true" <?php endif; ?>  />
                                                <label for="is_top_style_0" class="ui-radio-label"><?php echo $this->_var['lang']['no']; ?></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="item" <?php if ($this->_var['cat_info']['is_top_style'] == 0): ?>style="display:none"<?php endif; ?>>
                                    <div class="label">顶级分类页模板：</div>
                                    <div class="label_value">
                                      	<div class="imitate_select select_w120">
                                            <div class="cite">默认模板</div>
                                            <ul style="display: none;">
                                                <li><a href="javascript:;" data-value="0" class="ftx-01">默认模板</a></li>
                                                <li><a href="javascript:;" data-value="1" class="ftx-01">女装模板</a></li>
                                                <li><a href="javascript:;" data-value="2" class="ftx-01">家电模板</a></li>
                                                <li><a href="javascript:;" data-value="3" class="ftx-01">食品模板</a></li>
                                            </ul>
                                            <input name="top_style_tpl" type="hidden" value="<?php echo $this->_var['cat_info']['top_style_tpl']; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="item" <?php if ($this->_var['cat_info']['is_top_style'] == 0): ?>style="display:none"<?php endif; ?>>
                                    <div class="label">顶级分类页菜单图标：</div>
                                    <div class="label_value">                                    
                                    	<div class="type-file-box">
                                            <input type="button" name="button" id="button" class="type-file-button" value="" />
                                            <input type="file" class="type-file-file" id="cat_icon" name="cat_icon" size="30" data-state="imgfile" hidefocus="true" value="" />
                                            <?php if ($this->_var['cat_info']['cat_icon']): ?>
                                            <span class="show">
                                                <a href="../<?php echo $this->_var['cat_info']['cat_icon']; ?>" target="_blank" class="nyroModal"><i class="icon icon-picture" onmouseover="toolTip('<img src=<?php echo $this->_var['cat_info']['cat_icon']; ?>>')" onmouseout="toolTip()"></i></a>
                                            </span>
                                            <?php endif; ?>
                                            <input type="text" name="textfile" class="type-file-text" id="textfield" readonly />
                                        </div>
                                        <div class="notic">图标尺寸为18*18比例，大小不能超过200KB，图片只能为jpg、png、gif格式</div>
                                    </div>
                                </div>									
								<?php endif; ?>
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['show_in_index']; ?>：</div>
                                    <div class="label_value">
                                        <div class="checkbox_items">
                                            <div class="checkbox_item">
                                                <input type="checkbox" class="ui-checkbox" name="cat_recommend[]" id="cat_recommend_1" value="1" <?php if ($this->_var['cat_recommend'] [ 1 ] == 1): ?> checked="true" <?php endif; ?>  />
                                                <label for="cat_recommend_1" class="ui-label"><?php echo $this->_var['lang']['index_best']; ?></label>
                                            </div>
                                            <div class="checkbox_item">
                                                <input type="checkbox" class="ui-checkbox" name="cat_recommend[]" id="cat_recommend_2" value="2" <?php if ($this->_var['cat_recommend'] [ 2 ] == 1): ?> checked="true" <?php endif; ?>  />
                                                <label for="cat_recommend_2" class="ui-label"><?php echo $this->_var['lang']['index_new']; ?></label>
                                            </div>
                                            <div class="checkbox_item">
                                                <input type="checkbox" class="ui-checkbox" name="cat_recommend[]" id="cat_recommend_3" value="3" <?php if ($this->_var['cat_recommend'] [ 3 ] == 1): ?> checked="true" <?php endif; ?>  />
                                                <label for="cat_recommend_3" class="ui-label"><?php echo $this->_var['lang']['index_hot']; ?></label>
                                            </div>											
                                        </div>
                                    </div>
                                </div>
                                <div class="item" <?php if (! $this->_var['cat_name_arr']): ?>style="display:none"<?php endif; ?>>
                                    <div class="label"><?php echo $this->_var['lang']['category_herf']; ?>：</div>
                                    <div class="label_value">
                                        <textarea name='category_links' rows="6" cols="48" class="textarea"><?php echo $this->_var['cat_info']['category_links']; ?></textarea>
                                        <div class="notic"><?php echo $this->_var['lang']['category_herf_notic']; ?></div>
                                    </div>
                                </div>
                                <div class="item" <?php if ($this->_var['parent_id'] != 0 || $this->_var['form_act'] == 'insert'): ?>style=" display:none"<?php endif; ?>>
                                    <div class="label">分类树顶级分类模块内容：</div>
                                    <div class="label_value">
                                        <textarea name='category_topic' rows="6" cols="48" class="textarea"><?php echo $this->_var['cat_info']['category_topic']; ?></textarea>
                                        <div class="notic">(格式：名称+"英文竖线"+链接地址)，每行一条数据。</div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['filter_attr']; ?>：</div>
                                    <div class="label_value">	
									  <table width="100%" id="tbody-attr" align="center">
										<?php if ($this->_var['attr_cat_id'] == 0): ?>
										<tr>
										  <td>
                                           <a href="javascript:;" onclick="addFilterAttr(this)" class="fl mr10 w20 tc">[+]</a>
                                           <div class="imitate_select select_w170">
                                                <div class="cite"><?php echo $this->_var['lang']['sel_goods_type']; ?></div>
                                                <ul style="display: none;">
                                                    <?php echo $this->_var['goods_type_list']; ?>
                                                </ul>
                                                <input name="goods_type" type="hidden" value="0">
                                            </div>
                                            <div class="imitate_select select_w120">
                                                <div class="cite"><?php echo $this->_var['lang']['sel_goods_type']; ?></div>
                                                <ul style="display: none;">
                                                    <li><a href="javascript:;" data-value="0" class="ftx-01"><?php echo $this->_var['lang']['sel_filter_attr']; ?></a></li>
                                                </ul>
                                                <input name="filter_attr[]" type="hidden" value="0">
                                            </div>
										  </td>
										</tr> 
										<?php endif; ?>           
										<?php $_from = $this->_var['filter_attr_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'filter_attr');$this->_foreach['filter_attr_tab'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['filter_attr_tab']['total'] > 0):
    foreach ($_from AS $this->_var['filter_attr']):
        $this->_foreach['filter_attr_tab']['iteration']++;
?>
										<tr>
										  <td>
											 <?php if ($this->_foreach['filter_attr_tab']['iteration'] == 1): ?>
											   <a href="javascript:;" onclick="addFilterAttr(this)" class="fl mr10 w20 tc">[+]</a>
											 <?php else: ?>
											   <a href="javascript:;" onclick="removeFilterAttr(this)" class="fl mr10 w20 tc">[-]&nbsp;</a>
											 <?php endif; ?>
											 <div class="imitate_select select_w170">
												<div class="cite"><?php echo $this->_var['lang']['sel_goods_type']; ?></div>
												<ul style="display: none;">
													<?php echo $this->_var['goods_type_list']; ?>
												</ul>
												<input name="goods_type" type="hidden" value="<?php echo $this->_var['filter_attr']['goods_type']; ?>">
											 </div>
											 <div class="imitate_select select_w120">
												<div class="cite"><?php echo $this->_var['lang']['sel_goods_type']; ?></div>
												<ul style="display: none;">
													<li><a href="javascript:;" data-value="0" class="ftx-01"><?php echo $this->_var['lang']['sel_filter_attr']; ?></a></li>
													<?php $_from = $this->_var['filter_attr']['option']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
													<li><a href="javascript:;" data-value="<?php echo $this->_var['key']; ?>" class="ftx-01"><?php echo $this->_var['item']; ?></a></li>
													<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
												</ul>
												<input name="filter_attr[]" type="hidden" value="<?php echo $this->_var['filter_attr']['filter_attr']; ?>">
											 </div>
											 <!--<div class="notic bf100 ml0"><?php echo $this->_var['lang']['filter_attr_notic']; ?></div>-->
										  </td>
										</tr>
										<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
									  </table>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['grade']; ?>：</div>
                                    <div class="label_value">					
									  <input type="text" name="grade" value="<?php echo empty($this->_var['cat_info']['grade']) ? '0' : $this->_var['cat_info']['grade']; ?>" size="40" class="text mr10" autocomplete="off" />
									  <div class="form_prompt"></div>
                                      <div class="notic"><?php echo $this->_var['lang']['notice_grade']; ?></div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['cat_style']; ?>：</div>
                                    <div class="label_value">					
									  <input type="text" name="style" value="<?php echo htmlspecialchars($this->_var['cat_info']['style']); ?>" size="40" class="text mr10" autocomplete="off" />
									  <div class="notic"><?php echo $this->_var['lang']['notice_style']; ?></div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['keywords']; ?>：</div>
                                    <div class="label_value">					
										<input type="text" name="keywords" value='<?php echo $this->_var['cat_info']['keywords']; ?>' size="50" class="text mr10" autocomplete="off" />										
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['cat_desc']; ?>：</div>
                                    <div class="label_value">					
										<textarea name='cat_desc' rows="6" cols="48" class="textarea"><?php echo $this->_var['cat_info']['cat_desc']; ?></textarea>							
                                    </div>
                                </div>
								<?php if ($this->_var['cat_info']['parent_id'] == 0 && $this->_var['form_act'] == 'update'): ?>								
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['categoryFile']; ?>：</div>
                                    <div class="label_value">					
									  <table width="100%" align="center" id="documentTitle_table" style="border:none; padding:0px;">
										<?php if ($this->_var['form_act'] == 'update' && $this->_var['title_list']): ?>
										<tr>
										  <td>
												<a href="category.php?act=titleFileView&cat_id=<?php echo $this->_var['cat_id']; ?>"><?php echo $this->_var['lang']['see_zj_list']; ?></a>
										  </td>
										</tr>  	
										<?php else: ?>
											<tr>
											  <td>
												  <a onclick="addCategoryFile(this)" href="javascript:;" class="fl mr10 w20 tc">[+]</a>
												  <?php echo $this->_var['lang']['document_title']; ?> <input type="hidden" value="0" size="40" name="dt_id[]"><input type="text" value="" size="40" name="document_title[]" class="text" autocomplete="off" />
											  </td>
											</tr> 
											<?php if ($this->_var['title_list']): ?>
											<?php $_from = $this->_var['title_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'title');if (count($_from)):
    foreach ($_from AS $this->_var['title']):
?>
											<tr>
											  <td>
												  <a onclick="removeCategoryFile(this,<?php echo $this->_var['title']['dt_id']; ?>)" href="javascript:;" class="fl mr10 w20 tc">[-]</a>
												  <?php echo $this->_var['lang']['document_title']; ?> <input type="hidden" value="0" size="40" name="dt_id[]"><input type="text" value="<?php echo $this->_var['title']['dt_title']; ?>" size="40" name="document_title[]" class="text" autocomplete="off" />
											  </td>
											</tr>
											 <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>  
											 <?php endif; ?>
										 <?php endif; ?>         
									  </table>																			
                                    </div>
                                </div>
								<?php elseif ($this->_var['form_act'] == 'insert'): ?>
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['categoryFile']; ?>：</div>
                                    <div class="label_value">					
									  <table width="100%" align="center" id="documentTitle_table" style="border:none">
										<tr>
										  <td>
											  <a onclick="addCategoryFile(this)" href="javascript:;" class="fl mr10 w20 tc">[+]</a>
											  <label class="fl lh mr10"><?php echo $this->_var['lang']['document_title']; ?></label>
											  <input type="text" value="" size="40" name="document_title[]" class="text" autocomplete="off" />
											  <input type="hidden" value="0" size="40" name="dt_id[]">
										  </td>
										</tr>        
									  </table>																			
                                    </div>
                                </div>								
								<?php endif; ?>								
                                <div class="item">
                                    <div class="label">&nbsp;</div>
                                    <div class="label_value info_btn">
										<input type="submit" value="<?php echo $this->_var['lang']['button_submit']; ?>" class="button" id="submitBtn" />
										<input type="reset" value="<?php echo $this->_var['lang']['button_reset']; ?>" class="button button_reset" />
										<input type="hidden" name="act" value="<?php echo $this->_var['form_act']; ?>" />
										<input type="hidden" name="old_cat_name" value="<?php echo $this->_var['cat_info']['cat_name']; ?>" />
										<input type="hidden" name="cat_id" value="<?php echo $this->_var['cat_info']['cat_id']; ?>" />
                                    </div>
                                </div>								
                            </div>
                        </form>
                    </div>
                </div>
            </div>
		</div>
    </div>
	<?php echo $this->fetch('library/pagefooter.lbi'); ?>	
    
    <script type="text/javascript">
	$(function(){
		//表单验证
		$("#submitBtn").click(function(){
			if($("#category_info_form").valid()){
				$("#category_info_form").submit();
			}
		});
		
		jQuery.validator.addMethod("specialchar", function(value, element) {

		  return this.optional(element) || !/[@\/'\\"#$%&\^*]/.test(value);   
		},("不能包含特殊字符"));
		
		$('#category_info_form').validate({
			errorPlacement:function(error, element){
				var error_div = element.parents('div.label_value').find('div.form_prompt');
				element.parents('div.label_value').find(".notic").hide();
				error_div.append(error);
			},
			rules:{
				cat_name :{
					required : true,
					specialchar:""
				},
				grade :{
					min : 0,
					max : 10
				}
			},
			messages:{
				cat_name:{
					 required : '<i class="icon icon-exclamation-sign"></i>'+catname_empty
				},
				grade:{
					 min : '<i class="icon icon-exclamation-sign"></i>价格区间不能小于0',
					 max : '<i class="icon icon-exclamation-sign"></i>价格区间不能大于10'
				}
			}			
		});
	});
    
    /**
     * 新增一个筛选属性
     */
    function addFilterAttr(obj)
    {
      var src = obj.parentNode.parentNode;
      var tbl = document.getElementById('tbody-attr');
      var filterAttr = document.getElementsByName("filter_attr[]");
      var row  = tbl.insertRow(tbl.rows.length);
      var cell = row.insertCell(-1);
      cell.innerHTML = src.cells[0].innerHTML.replace(/(.*)(addFilterAttr)(.*)(\[)(\+)/i, "$1removeFilterAttr$3$4-");
      filterAttr[filterAttr.length-1].value = 0;
      
    }
    
    /**
     * 删除一个筛选属性
     */
    function removeFilterAttr(obj)
    {
      var row = rowindex(obj.parentNode.parentNode);
      var tbl = document.getElementById('tbody-attr');
    
      tbl.deleteRow(row);
    }
    
    //ecmoban模板堂 --zhuo start
    
    //判断选择的分类是否是顶级分类，如果是则可用 类目证件
    function get_cat_parent_val(f,lev){
        var cat_alias_name = document.getElementById("cat_alias_name");
        var title_list = document.getElementsByName("document_title[]");
        var cat_parent_id = f + "_" + Number(lev - 1);
        
        var arr = new Array();
        var str = new String(cat_parent_id);
        var arr = str.split("_");
        var sf = Number(arr[0]);
        var slevel = Number(arr[1]);
    
        catList(sf, lev);
    
        for(i=0; i<title_list.length; i++){
            if(sf != 0){
                title_list[i].disabled = true;
                title_list[i].value = '';
                cat_alias_name.disabled = true;
                cat_alias_name.value = '';
                
            }else{
				//顶级分类为0
                title_list[i].disabled = false;
                cat_alias_name.disabled = false;
            }	
        }
    }
    /**
       * 添加类目证件
       */
      function addCategoryFile(obj)
      {  
         var title_list = document.getElementsByName("document_title[]");
         var catParent = document.getElementById('category_id').value; 
    
         if(catParent != 0){
             alert('该分类必须是顶级分类才能使用!');
    
             for(i=0; i<title_list.length; i++){
                 title_list[i].value = '';
             }
             
             return false;
        }
          
        var src      = obj.parentNode.parentNode;
        var tbl      = document.getElementById('documentTitle_table');
    
        var row  = tbl.insertRow(tbl.rows.length);
        var cell = row.insertCell(-1);
        cell.innerHTML = src.cells[0].innerHTML.replace(/(.*)(addCategoryFile)(.*)(\[)(\+)/i, "$1removeCategoryFile$3$4-");
    
        title_list[title_list.length-1].value = "";
      }
    
      /**
       * 删除类目证件
       */
      function removeCategoryFile(obj,dt_id)
      {
          if(dt_id > 0){
           if (confirm('确实要删除该信息吗')){
               <?php if ($this->_var['cat_id'] > 0): ?>
               location.href = 'category.php?act=title_remove&dt_id=' + dt_id + '&cat_id=' + <?php echo $this->_var['cat_id']; ?>;  
               <?php endif; ?>
           }
          }else{
              var row = rowindex(obj.parentNode.parentNode);
              var tbl = document.getElementById('documentTitle_table');
        
              tbl.deleteRow(row);
          }
      }
    //ecmoban模板堂 --zhuo end
    
    //-->
    
    //顶级分类页模板 by wu
    $(document).ready(function(){
        $("[name='is_top_style']").click(function(){
            if($(this).attr('value')==1)
            {
                $("[name='top_style_tpl']").parents('.item').show();
                $("[name='cat_icon']").parents('.item').show();
            }
            else
            {
                $("[name='top_style_tpl']").parents('.item').hide();
                $("[name='cat_icon']").parents('.item').hide();
            }
        })
    })
    
    function delete_icon(cat_id)
    {
        $.ajax({
            type:'get',
            url:'category.php',
            data:'act=delete_icon&cat_id='+cat_id,
            dataType:'json',
            success:function(data){
                if(data.error==1)
                {	
                    location.reload();
                }
                if(data.error==0)
                {	
                    alert('删除失败');
                }			
            }
        })
    }
    
    // 分类分级 by qin
    function catList(val, level)
    {
        var cat_parent_id = val;
        Ajax.call('goods.php?is_ajax=1&act=sel_cat', 'cat_id='+cat_parent_id+'&cat_level='+level, catListResponse, 'GET', 'JSON');
    }
    
    function catListResponse(result)
    {
        document.getElementById('cat_parent_id').value = result.parent_id + "_" + Number(result.cat_level - 1);  
        if (result.error == '1' && result.message != '')
        {
            alert(result.message);
            return;
        }
        var response = result.content;
        var cat_level = result.cat_level; // 分类级别， 1为顶级分类
        for(var i=cat_level;i<10;i++)
        {
            $("#cat_list"+Number(i+1)).remove();
        }
        if(response)
        {
            $("#cat_list"+cat_level).after(response);
        }
        return;
    }
	
	
	var arr = new Array();
	var sel_filter_attr = "<?php echo $this->_var['lang']['sel_filter_attr']; ?>";
	<?php $_from = $this->_var['attr_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('att_cat_id', 'val');if (count($_from)):
    foreach ($_from AS $this->_var['att_cat_id'] => $this->_var['val']):
?>
		arr[<?php echo $this->_var['att_cat_id']; ?>] = new Array();
		<?php $_from = $this->_var['val']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('i', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['i'] => $this->_var['item']):
?>
		  <?php $_from = $this->_var['item']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('attr_id', 'attr_val');if (count($_from)):
    foreach ($_from AS $this->_var['attr_id'] => $this->_var['attr_val']):
?>
			arr[<?php echo $this->_var['att_cat_id']; ?>][<?php echo $this->_var['i']; ?>] = ["<?php echo $this->_var['attr_val']; ?>", <?php echo $this->_var['attr_id']; ?>];
		  <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
		<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
	<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
	
	//修改 by wu
	function changeCat(obj)
	{
		var obj = $(obj);
		var key = obj.data('value');
		
		if(arr[key]){
			var tArr = arr[key];
			var target = obj.parents(".imitate_select").next().find("ul");
			target.find("li:gt(0)").remove();
			for(var i=0; i<tArr.length; i++){
				var line = "<li><a href='javascript:;' data-value='"+tArr[i][1]+"' class='ftx-01'>"+tArr[i][0]+"</a></li>";
				target.append(line);
			}
		}
	}
    </script>
	
</body>
</html>
