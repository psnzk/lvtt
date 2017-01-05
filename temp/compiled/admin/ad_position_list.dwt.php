<?php if ($this->_var['full_page']): ?>
<!doctype html>
<html>
<head><?php echo $this->fetch('library/admin_html_head.lbi'); ?></head>
<body class="iframe_body">
	<div class="warpper">
    	<div class="title"><?php if ($this->_var['type'] == 1): ?>手机<?php else: ?>广告<?php endif; ?> - <?php echo $this->_var['ur_here']; ?></div>
        <div class="content">
        	<div class="explanation" id="explanation">
            	<div class="ex_tit"><i class="sc_icon"></i><h4><?php echo $this->_var['lang']['operating_hints']; ?></h4><span id="explanationZoom" title="<?php echo $this->_var['lang']['fold_tips']; ?>"></span></div>
                <ul>
                	<li>展示网站所有的广告位置。</li>
                    <li>点击查看可查看广告位置相关广告位的广告。</li>
                    <li>可搜索关键词进行查询，侧边栏可进行高级搜索。</li>
                </ul>
            </div>
            <div class="flexilist">
            	<div class="common-head">
                    	<?php if ($this->_var['action_link']): ?>
                    	<div class="fl">
                            <a href="<?php echo $this->_var['action_link']['href']; ?>"><div class="fbutton"><div class="add" title="<?php echo $this->_var['action_link']['text']; ?>"><span><i class="icon icon-plus"></i><?php echo $this->_var['action_link']['text']; ?></span></div></div></a>
                        </div>
                        <?php endif; ?>
                        <div class="refresh">
                        <div class="refresh_tit" title="<?php echo $this->_var['lang']['refresh_data']; ?>"><i class="icon icon-refresh"></i></div>
                        <div class="refresh_span"><?php echo $this->_var['lang']['refresh']; ?> - <?php echo $this->_var['lang']['total_data']; ?><?php echo $this->_var['record_count']; ?><?php echo $this->_var['lang']['data']; ?></div>
                    </div>
                    <form action="javascript:searchAd_position()" name="searchForm">
                        <div class="search">
                            <div class="input">
                                <input type="text" name="keyword" class="text nofocus" placeholder="<?php echo $this->_var['lang']['keyword']; ?>" autocomplete="off" /><input type="submit" value="" class="not_btn" />
                            </div>
                        </div>
                    </form>
                </div>
                <div class="common-content">
                	<div class="list-div"  id="listDiv">
                        <?php endif; ?>
                    	<table cellpadding="0" cellspacing="0" border="0">
                        	<thead>
                            	<tr>
                                    <th width="20%"><div class="tDiv"><?php echo $this->_var['lang']['position_name']; ?></div></th>
                                    <th width="12%"><div class="tDiv"><?php echo $this->_var['lang']['goods_steps_name']; ?></div></th>
                                    <th width="8%"><div class="tDiv"><?php echo $this->_var['lang']['posit_width']; ?></div></th>
                                    <th width="8%"><div class="tDiv"><?php echo $this->_var['lang']['posit_height']; ?></div></th>
                                    <th width="18%"><div class="tDiv"><?php echo $this->_var['lang']['position_model']; ?></div></th>
                                    <th width="19%"><div class="tDiv"><?php echo $this->_var['lang']['position_desc']; ?></div></th>
                                    <th width="15%" class="handle"><?php echo $this->_var['lang']['handler']; ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $_from = $this->_var['position_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                            	<tr>
                                    <td><div class="tDiv"><?php echo htmlspecialchars($this->_var['list']['position_name']); ?></div></td>
                                    <?php if ($this->_var['list']['is_public'] == 1): ?>
                                        <?php if ($this->_var['priv_ru'] == 1): ?>
                                        <td><div class="tDiv"><font style="color:#F60;">公共</font></div></td>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <?php if ($this->_var['priv_ru'] == 1): ?>
                                        <td><div class="tDiv"><?php if ($this->_var['list']['user_name']): ?><font style="color:#F00;"><?php echo $this->_var['list']['user_name']; ?></font><?php else: ?><font class="blue">自营</font><?php endif; ?></div></td>
                                        <?php endif; ?>
                                    <?php endif; ?>   
                                    <td><div class="tDiv"><?php echo $this->_var['list']['ad_width']; ?></div></td>
                                    <td><div class="tDiv"><?php echo $this->_var['list']['ad_height']; ?></div></td>
                                    <td><div class="tDiv"><?php echo $this->_var['list']['position_model']; ?></div></td>
                                    <td><div class="tDiv"><?php echo htmlspecialchars($this->_var['list']['position_desc']); ?></div></td>
                                    <td class="handle">
                                        <div class="tDiv a3">
                                            <a href="<?php if ($this->_var['type'] == 1): ?>touch_ads.php<?php else: ?>ads.php<?php endif; ?>?act=list&pid=<?php echo $this->_var['list']['position_id']; ?>" title="<?php echo $this->_var['lang']['view']; ?><?php echo $this->_var['lang']['ad_content']; ?>" class="btn_see"><i class="sc_icon sc_icon_see"></i><?php echo $this->_var['lang']['view']; ?></a>
                                            <a href="<?php if ($this->_var['type'] == 1): ?>touch_ad_position.php<?php else: ?>ad_position.php<?php endif; ?>?act=edit&id=<?php echo $this->_var['list']['position_id']; ?>" title="<?php echo $this->_var['lang']['edit']; ?>" class="btn_edit"><i class="icon icon-edit"></i><?php echo $this->_var['lang']['edit']; ?></a>
                                            <a href="javascript:;" onclick="listTable.remove(<?php echo $this->_var['list']['position_id']; ?>, '<?php echo $this->_var['lang']['drop_confirm']; ?>')" title="<?php echo $this->_var['lang']['remove']; ?>" class="btn_trash"><i class="icon icon-trash"></i><?php echo $this->_var['lang']['remove']; ?></a>
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
            <div class="gj_search">
                <div class="search-gao-list" id="searchBarOpen">
                    <i class="icon icon-zoom-in"></i><?php echo $this->_var['lang']['advanced_search']; ?>
                </div>
                <div class="search-gao-bar">
                    <div class="handle-btn" id="searchBarClose"><i class="icon icon-zoom-out"></i><?php echo $this->_var['lang']['pack_up']; ?></div>
                    <div class="title"><h3><?php echo $this->_var['lang']['advanced_search']; ?></h3></div>
                    <form method="get" name="formSearch_senior" action="javascript:searchAd_position()">
                        <div class="searchContent">
                            <div class="layout-box">
                                <dl>
                                    <dt><?php echo $this->_var['lang']['keyword']; ?></dt>
                                    <dd><input type="text" value="" name="keyword"  class="s-input-txt" autocomplete="off" /></dd>
                                </dl>
                                <dl>
                                    <dt><?php echo $this->_var['lang']['steps_shop_name']; ?></dt>
                                    <dd>
                                        <div id="shop_name_select" class="select_w120 imitate_select">
                                            <div class="cite"><?php echo $this->_var['lang']['select_please']; ?></div>
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
                                        <div class="select_w120 imitate_select">
                                            <div class="cite"><?php echo $this->_var['lang']['select_please']; ?></div>
                                            <ul>
                                               <li><a href="javascript:;" data-value="0"><?php echo $this->_var['lang']['select_please']; ?></a></li>
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
                                        <div class="select_w120 imitate_select">
                                            <div class="cite"><?php echo $this->_var['lang']['select_please']; ?></div>
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
                            </div>
                        </div>
                        <div class="bot_btn">
                            <input type="submit" class="btn red_btn" name="tj_search" value="<?php echo $this->_var['lang']['button_inquire']; ?>" /><input type="reset" class="btn btn_reset" name="reset" value="<?php echo $this->_var['lang']['button_reset']; ?>" />
                        </div>
                    </form>
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

    function searchAd_position()
    {
        var frm = $("form[name='formSearch_senior']");
        listTable.filter['store_search'] = Utils.trim(frm.find("input[name='store_search']").val());
        listTable.filter['merchant_id'] = Utils.trim(frm.find("input[name='merchant_id']").val());
        listTable.filter['store_keyword'] = Utils.trim(frm.find("input[name='store_keyword']").val());
        listTable.filter['store_type'] = Utils.trim(frm.find("input[name='store_type']").val());
	
        listTable.filter['keyword'] = Utils.trim(($("form[name='searchForm']").find("input[name='keyword']").val() != '') ? $("form[name='searchForm']").find("input[name='keyword']").val() :  frm.find("input[name='keyword']").val());
        listTable.filter['page'] = 1;

        listTable.loadList();
    }
    $.gjSearch("-240px");  //高级搜索
    </script>
</body>
</html>
<?php endif; ?>
