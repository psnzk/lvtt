<?php if ($this->_var['full_page']): ?>
<!doctype html>
<html>
<head><?php echo $this->fetch('library/admin_html_head.lbi'); ?></head>

<body class="iframe_body">
	<div class="warpper">
    	<div class="title">商家 - <?php echo $this->_var['ur_here']; ?></div>
        <div class="content">
        	<div class="explanation" id="explanation">
            	<div class="ex_tit"><i class="sc_icon"></i><h4>操作提示</h4><span id="explanationZoom" title="收起提示"></span></div>
                <ul>
                	<li>平台所有入驻商相关信息管理。</li>
                    <li>可对入驻商进行分派权限操作。</li>
                </ul>
            </div>
            <div class="flexilist">
            	<div class="common-head">
                    <form action="javascript:searchUser()" name="searchForm">
                        <div class="search">
                            <div class="input">
                                <input type="text" name="user_name" class="text nofocus" placeholder="会员名称" autocomplete="off" /><input type="submit" value="" class="not_btn" />
                            </div>
                        </div>
                    </form>
					<div class="fl">
						<a href="<?php echo $this->_var['action_link']['href']; ?>"><div class="fbutton"><div class="add" title="<?php echo $this->_var['action_link']['text']; ?>"><span><i class="icon icon-plus"></i><?php echo $this->_var['action_link']['text']; ?></span></div></div></a>
                        <a href="<?php echo $this->_var['action_link2']['href']; ?>"><div class="fbutton"><div class="add" title="<?php echo $this->_var['action_link']['text']; ?>"><span><i class="icon icon-plus"></i><?php echo $this->_var['action_link2']['text']; ?></span></div></div></a>
					</div>
                </div>
                <div class="common-content">
                	<div class="list-div" id="listDiv">
						<?php endif; ?>
						<form method="POST" action="" name="listForm" onsubmit="return confirm_bath()">
                    	<table cellpadding="1" cellspacing="1">
                        	<thead>
                            	<tr>
                                	<th width="3%" class="sign"><div class="tDiv"><input type="checkbox" name="all_list" class="checkbox" id="all_list" /><label for="all_list" class="checkbox_stars"></label></div></th>
                                    <th width="5%"><div class="tDiv"><?php echo $this->_var['lang']['record_id']; ?></div></th>
									<th width="12%"><div class="tDiv"><?php echo $this->_var['lang']['steps_user_name']; ?></div></th>
                                    <th width="12%"><div class="tDiv"><?php echo $this->_var['lang']['steps_shop_name']; ?></div></th>
                                    <th width="5%"><div class="tDiv"><?php echo $this->_var['lang']['seller_rank']; ?></div></th>
                                    <th width="10%"><div class="tDiv"><?php echo $this->_var['lang']['steps_main_categories']; ?></div></th>
                                    <th width="8%"><div class="tDiv"><?php echo $this->_var['lang']['audit_status']; ?></div></th>
                                    <th width="6%"><div class="tDiv"><?php echo $this->_var['lang']['sort_order']; ?></div></th>
                                    <th width="5%"><div class="tDiv"><?php echo $this->_var['lang']['is_street']; ?></div></th>
									<th width="5%"><div class="tDiv"><?php echo $this->_var['lang']['im_service']; ?></div></th>
									<th width="23%" class="handle"><?php echo $this->_var['lang']['handler']; ?></th>
                                </tr>
                            </thead>
                            <tbody>
							    <?php $_from = $this->_var['users_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'users');if (count($_from)):
    foreach ($_from AS $this->_var['users']):
?>
								<tr>
                                    <td class="sign"><div class="tDiv"><input type="checkbox" value="<?php echo $this->_var['users']['shop_id']; ?>" name="checkboxes[]"  class="checkbox" id="checkbox_<?php echo $this->_var['users']['shop_id']; ?>" /><label for="checkbox_<?php echo $this->_var['users']['shop_id']; ?>" class="checkbox_stars"></label></div></td>
                                    <td><div class="tDiv"><?php echo $this->_var['users']['shop_id']; ?></div></td>
                                    <td><div class="tDiv"><?php echo $this->_var['users']['user_name']; ?></div></td>
                                    <td class="red"><div class="tDiv"><?php echo $this->_var['users']['rz_shopName']; ?></div></td>
                                    <td>
                                        <div class="tDiv"><?php if ($this->_var['users']['grade_img']): ?><img src="../<?php echo $this->_var['users']['grade_img']; ?>" width="20" height="20"><?php else: ?>无<?php endif; ?>
                                        </div>
                                    </td>
                                    
                                    <td><div class="tDiv"><?php echo $this->_var['users']['cat_name']; ?></div></td>
                                    <td>
                                    	<div class="tDiv">
                                        <?php if ($this->_var['users']['steps_audit'] == 1): ?>
                                            <span class="blue">
                                            <?php if ($this->_var['users']['merchants_audit'] == 0): ?>
                                            <?php echo $this->_var['lang']['not_audited']; ?>
                                            <?php elseif ($this->_var['users']['merchants_audit'] == 1): ?>
                                            <?php echo $this->_var['lang']['audited_yes_adopt']; ?>
                                            <?php elseif ($this->_var['users']['merchants_audit'] == 2): ?>
                                            <?php echo $this->_var['lang']['audited_not_adopt']; ?>
                                            <?php endif; ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="org"><?php echo $this->_var['lang']['not_yet_info']; ?></span>
                                        <?php endif; ?>
                                        </div> 
                                    </td>
                                    <td>
									<div class="tDiv"><input type="text" name="sort_order" class="text w40" value="<?php echo $this->_var['users']['sort_order']; ?>" onkeyup="listTable.editInput(this, 'edit_sort_order', <?php echo $this->_var['users']['shop_id']; ?>)"/></div>
									</td>
                                    <td>
                                        <div class="tDiv">
                                            <div class="switch mauto <?php if ($this->_var['users']['is_street']): ?>active<?php endif; ?>" onclick="listTable.switchBt(this, 'toggle_is_street', <?php echo $this->_var['users']['shop_id']; ?>)" title="是">
                                                <div class="circle"></div>
                                            </div>
                                            <input type="hidden" value="" name="is_street">
                                        </div>
                                    </td>  
                                    <td>
                                        <div class="tDiv">
                                            <div class="switch mauto <?php if ($this->_var['users']['is_IM']): ?>active<?php endif; ?>" onclick="listTable.switchBt(this, 'toggle_is_IM', <?php echo $this->_var['users']['shop_id']; ?>)" title="是">
                                                <div class="circle"></div>
                                            </div>
                                            <input type="hidden" value="" name="is_IM">
                                        </div>
                                    </td> 
                                    <td class="handle">
                                        <div class="tDiv a3_3">
                                            <a href="visual_editing.php?act=templates&id=<?php echo $this->_var['users']['user_id']; ?>" title="模板管理" class="btn_edit"><i class="icon icon-edit"></i>模板管理</a>
                                            <?php if ($this->_var['users']['merchants_audit'] == 1): ?>
                                            <a href="merchants_users_list.php?act=allot&id=<?php echo $this->_var['users']['user_id']; ?>&login_name=<?php echo $this->_var['users']['hopeLoginName']; ?>" title="<?php echo $this->_var['lang']['allot_priv']; ?>" class="btn_region"><i class="icon icon-cog"></i><?php echo $this->_var['lang']['allot_priv']; ?></a>
                                            <?php endif; ?>
                                            <a href="merchants_users_list.php?act=edit_shop&id=<?php echo $this->_var['users']['user_id']; ?>" title="<?php echo $this->_var['lang']['edit']; ?>" class="btn_edit"><i class="icon icon-edit"></i><?php echo $this->_var['lang']['edit']; ?></a>
                                            <a href="javascript:confirm_redirect('<?php echo $this->_var['lang']['remove_confirm_user']; ?>', 'merchants_users_list.php?act=remove&id=<?php echo $this->_var['users']['user_id']; ?>')" title="<?php echo $this->_var['lang']['remove']; ?>" class="btn_trash"><i class="icon icon-trash"></i><?php echo $this->_var['lang']['remove']; ?></a>	
                                        </div>
                                    </td>
								</tr>
								<?php endforeach; else: ?>
								<tr><td class="no-records" colspan="13"><?php echo $this->_var['lang']['no_records']; ?></td></tr>
								<?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            </tbody>
                            <tfoot>
                            	<tr>
                                    <td colspan="13">
                                    	<div class="list-page">
                                            <?php echo $this->fetch('library/page.lbi'); ?>
                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </form>
                        <?php if ($this->_var['full_page']): ?>
                    </div>
                </div>
            </div>
            <div class="gj_search">
                <div class="search-gao-list" id="searchBarOpen">
                    <i class="icon icon-zoom-in"></i>高级搜索
                </div>
                <div class="search-gao-bar">
                    <div class="handle-btn" id="searchBarClose"><i class="icon icon-zoom-out"></i>收起边栏</div>
                    <div class="title"><h3>高级搜索</h3></div>
                    <form method="get" name="formSearch_senior" action="javascript:searchUser()">
                        <div class="searchContent">
                            <div class="layout-box">
                                <dl>
                                    <dt><?php echo $this->_var['lang']['user_name']; ?></dt>
                                    <dd><input type="text" value="" name="user_name" class="s-input-txt" autocomplete="off" /></dd>
                                </dl>
                                <dl>
                                    <dt><?php echo $this->_var['lang']['steps_shop_name']; ?></dt>
                                    <dd>
                                        <div id="shop_name_select" class="select_w145 imitate_select">
                                            <div class="cite">请选择</div>
                                            <ul>
                                               <li><a href="javascript:;" data-value="0"><?php echo $this->_var['lang']['select_please']; ?></a></li>
                                               <li><a href="javascript:;" data-value="1"><?php echo $this->_var['lang']['s_shop_name']; ?></a></li>
                                               <li><a href="javascript:;" data-value="2"><?php echo $this->_var['lang']['s_qw_shop_name']; ?></a></li>
                                               <li><a href="javascript:;" data-value="3"><?php echo $this->_var['lang']['s_brand_type']; ?></a></li>
                                            </ul>
                                            <input name="store_search" type="hidden" value="0" id="shop_name_val">
                                        </div>
                                    </dd>
                                </dl>
                                <dl style="display:none" id="merchant_box">
                                    <dd>
                                        <div class="select_w145 imitate_select">
                                            <div class="cite">请选择</div>
                                            <ul>
                                               <li><a href="javascript:;" data-value="0">请选择</a></li>
                                               <?php $_from = $this->_var['store_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'store');if (count($_from)):
    foreach ($_from AS $this->_var['store']):
?>
                                               <li><a href="javascript:;" data-value="<?php echo $this->_var['store']['ru_id']; ?>"><?php echo $this->_var['store']['store_name']; ?></a></li>
                                               <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                            </ul>
                                            <input name="merchant_id" type="hidden" value="0" >
                                        </div>
                                    </dd>
                                </dl>
                                <dl id="store_keyword" style="display:none" >
                                    <dd><input type="text" value="" name="store_keyword" class="s-input-txt" autocomplete="off" /></dd>
                                </dl>
                                <dl style="display:none" id="store_type">
                                    <dd>
                                        <div class="select_w145 imitate_select">
                                            <div class="cite">请选择</div>
                                            <ul>
                                               <li><a href="javascript:;" data-value="0"><?php echo $this->_var['lang']['steps_shop_type']; ?></a></li>
                                               <li><a href="javascript:;" data-value="<?php echo $this->_var['lang']['flagship_store']; ?>"><?php echo $this->_var['lang']['flagship_store']; ?></a></li>
                                               <li><a href="javascript:;" data-value="<?php echo $this->_var['lang']['exclusive_shop']; ?>"><?php echo $this->_var['lang']['exclusive_shop']; ?></a></li>
                                               <li><a href="javascript:;" data-value="<?php echo $this->_var['lang']['franchised_store']; ?>"><?php echo $this->_var['lang']['franchised_store']; ?></a></li>
                                               <li><a href="javascript:;" data-value="<?php echo $this->_var['lang']['shop_store']; ?>"><?php echo $this->_var['lang']['shop_store']; ?></a></li>
                                            </ul>
                                            <input name="store_type" type="hidden" value="0" >
                                        </div>
                                    </dd>
                                </dl>
                                <dl>
                                    <dd class="bot_btn">
                                       <input type="submit" class="btn red_btn" name="tj_search" value="提交查询" /><input type="reset" class="btn btn_reset" name="reset" value="重置" />
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
	<?php echo $this->fetch('library/pagefooter.lbi'); ?>
	<script type="text/javascript" src="js/jquery.picTip.js"></script>
    <script type="text/javascript">
            
        //分页传值
        listTable.recordCount = '<?php echo $this->_var['record_count']; ?>';
        listTable.pageCount = '<?php echo $this->_var['page_count']; ?>';
    
        <?php $_from = $this->_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
        listTable.filter.<?php echo $this->_var['key']; ?> = '<?php echo $this->_var['item']; ?>';
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        $.gjSearch("-240px");  //高级搜索
    
    
        $.divselect("#shop_name_select","#shop_name_val",function(obj){
            var val = obj.attr("data-value");
            get_store_search(val);
        });
        function get_store_search(val){
            if(val == 1){
                $("#merchant_box").css("display",'');
                $("#store_keyword").css("display",'none');
                $("#store_type").css("display",'none')
            }else if(val == 2){
                $("#merchant_box").css("display",'none');
                $("#store_keyword").css("display",'');
                $("#store_type").css("display",'none')
            }else if(val == 3){
                $("#merchant_box").css("display",'none');
                $("#store_keyword").css("display",'');
                $("#store_type").css("display",'')
            }else{
                $("#merchant_box").css("display",'none');
                $("#store_keyword").css("display",'none');
                $("#store_type").css("display",'none')
            }
        }
        
        $(function(){
            $('.nyroModal').nyroModal();
        })
        
        /**
         * 搜索用户
         */
        function searchUser()
        {
            var frm = $("form[name='formSearch_senior']");
            listTable.filter['store_search'] = Utils.trim(frm.find("input[name='store_search']").val());
            listTable.filter['merchant_id'] = Utils.trim(frm.find("input[name='merchant_id']").val());
            listTable.filter['store_keyword'] = Utils.trim(frm.find("input[name='store_keyword']").val());
            listTable.filter['store_type'] = Utils.trim(frm.find("input[name='store_type']").val());
            listTable.filter['user_name'] = Utils.trim(($("form[name='searchForm']").find("input[name='user_name']").val() != '') ? $("form[name='searchForm']").find("input[name='user_name']").val() :  frm.find("input[name='user_name']").val());
            listTable.filter['page'] = 1;
            listTable.loadList();
        }
    </script>
</body>
</html>
<?php endif; ?>