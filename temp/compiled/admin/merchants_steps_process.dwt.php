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
                	<li>标识“<em>*</em>”的选项为必填项，其余为选填项。</li>
                    <li>需先选择所属流程，请合理设定流程信息。</li>
                </ul>
            </div>
            <div class="flexilist">
                <div class="common-content">
                    <div class="mian-info">
						<form method="post" action="merchants_steps.php" name="theForm" id="merchants_steps_form">
                            <div class="switch_info">
                                <div class="items">
                                    <div class="item">
                                        <div class="label"><?php echo $this->_var['lang']['fields_steps']; ?>：</div>
                                        <div class="label_value">
											<div id="process_steps" class="imitate_select select_w320">
												<div class="cite"><?php echo $this->_var['lang']['settled_need_know']; ?></div>
												<ul>
													<li><a href='javascript:;' data-value='1' class='ftx-01'><?php echo $this->_var['lang']['settled_need_know']; ?></a></li>
													<li><a href='javascript:;' data-value='2' class='ftx-01'><?php echo $this->_var['lang']['company_info_aut']; ?></a></li>
													<li><a href='javascript:;' data-value='3' class='ftx-01'><?php echo $this->_var['lang']['shop_info_aut']; ?></a></li>
												</ul>
                                                <input name="process_steps" type="hidden" value="<?php echo $this->_var['process_info']['process_steps']; ?>" id="process_steps_val">
											</div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="label"><?php echo $this->_var['lang']['require_field']; ?><?php echo $this->_var['lang']['steps_process_title']; ?>：</div>
                                        <div class="label_value">
											<input type="text" name="process_title" id="process_title" class="text" value="<?php echo $this->_var['process_info']['process_title']; ?>" autocomplete="off" />
											<div class="form_prompt"></div>
                                        </div>
                                    </div>
									<div class="item">
                                        <div class="label"><?php echo $this->_var['lang']['steps_process_article']; ?>：</div>
                                        <div class="label_value">
											<input type="text" name="process_article" id="process_article" class="text" value="<?php echo empty($this->_var['process_info']['process_article']) ? '0' : $this->_var['process_info']['process_article']; ?>" autocomplete="off" />
                                            <div class="form_prompt"></div>
										</div>
                                    </div>
									<div class="item">
                                        <div class="label"><?php echo $this->_var['lang']['steps_sort']; ?>：</div>
                                        <div class="label_value">
											<input type="text" name="steps_sort" id="steps_sort" class="text" value="<?php echo empty($this->_var['process_info']['steps_sort']) ? '0' : $this->_var['process_info']['steps_sort']; ?>" autocomplete="off" />
                                            <div class="form_prompt"></div>
										</div>
                                    </div>
									<div class="item">
                                        <div class="label"><?php echo $this->_var['lang']['fields_next']; ?>：</div>
                                        <div class="label_value">
											<input type="text" name="fields_next" id="fields_next" class="text" value="<?php echo $this->_var['process_info']['fields_next']; ?>" autocomplete="off" />
											<div class="form_prompt"></div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="label">&nbsp;</div>
                                        <div class="label_value info_btn">
											<input type="submit" value="<?php echo $this->_var['lang']['button_submit']; ?>" class="button" id="submitBtn" />
											<input type="reset" value="<?php echo $this->_var['lang']['button_reset']; ?>" class="button button_reset" />
											<input type="hidden" name="act" value="<?php echo $this->_var['form_action']; ?>" />
											<input type="hidden" name="id" value="<?php echo $this->_var['process_info']['id']; ?>" />
                                        </div>
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
			if($("#merchants_steps_form").valid()){
				$("#merchants_steps_form").submit();
			}
		});
	
		$('#merchants_steps_form').validate({
			errorPlacement:function(error, element){
				var error_div = element.parents('div.label_value').find('div.form_prompt');
				element.parents('div.label_value').find(".notic").hide();
				error_div.append(error);
			},
			rules:{
				process_title :{
					required : true
				},
				process_article:{
					required : true,
					digits:true
				},
				steps_sort:{
					required : true,
					digits:true
				}
			},
			messages:{
				process_title:{
					required : '<i class="icon icon-exclamation-sign"></i>请输入流程信息标题'
				},
				process_article:{
					required : '<i class="icon icon-exclamation-sign"></i>文章ID不能为空',
					digits : '<i class="icon icon-exclamation-sign"></i>文章ID必须为整数'
				},
				steps_sort:{
					required : '<i class="icon icon-exclamation-sign"></i>排序不能为空',
					digits : '<i class="icon icon-exclamation-sign"></i>排序必须为整数'
				}
			}			
		});
	});
    </script>
</body>
</html>
