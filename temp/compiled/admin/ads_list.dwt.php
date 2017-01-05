<?php if ($this->_var['full_page']): ?>
<!doctype html>
<html>
<head><?php echo $this->fetch('library/admin_html_head.lbi'); ?></head>
<body class="iframe_body">
	<div class="warpper">
    	<div class="title"><?php if ($this->_var['ads_type'] == 1): ?>手机<?php else: ?>广告<?php endif; ?> - <?php echo $this->_var['ur_here']; ?></div>
        <div class="content">
        	<div class="explanation" id="explanation">
            	<div class="ex_tit"><i class="sc_icon"></i><h4>操作提示</h4><span id="explanationZoom" title="收起提示"></span></div>
                <ul>
                	<li>该页面展示了商城所有的广告。</li>
                    <li>可搜索广告名称关键词进行查询，侧边栏可进行高级搜索。</li>
                </ul>
            </div>
            <div class="flexilist">
            	<div class="common-head">
                   	<?php if ($this->_var['action_link']): ?>
                    <div class="fl">
                        <a href="<?php echo $this->_var['action_link']['href']; ?>"><div class="fbutton"><div class="add" title="<?php echo $this->_var['action_link']['text']; ?>"><span><i class="icon icon-plus"></i><?php echo $this->_var['action_link']['text']; ?></span></div></div></a>
                    </div>
                    <?php endif; ?>
                    <div class="refresh<?php if (! $this->_var['action_link']): ?> ml0<?php endif; ?>">
                    	<div class="refresh_tit" title="刷新数据"><i class="icon icon-refresh"></i></div>
                    	<div class="refresh_span">刷新 - 共<?php echo $this->_var['record_count']; ?>条记录</div>
                    </div>
                    <form action="javascript:searchAds()" name="searchForm">
                        <div class="search">
                            <div class="select" id="keyword">
                            	<div class="label">广告位置：</div>
                                <div id="keywordselect" class="imitate_select select_w320 mr0">
                                    <div class="cite">选择广告位置</div>
                                    <ul>
                                       <li><a href="javascript:;" data-value="0">选择广告位置</a></li>
                                       <?php $_from = $this->_var['position_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'pos');if (count($_from)):
    foreach ($_from AS $this->_var['pos']):
?>
                                       <li><a href="javascript:;" data-value="<?php echo $this->_var['pos']['position_name']; ?>"><?php echo $this->_var['pos']['position_name']; ?> [<?php echo $this->_var['pos']['ad_width']; ?>×<?php echo $this->_var['pos']['ad_height']; ?>]</a></li>
                                       <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                    </ul>
                                    <input name="keyword" type="hidden" value="0" id="keywordval">
                                </div>
                            </div>
                            <div class="select">
                                <div class="label"><?php echo $this->_var['lang']['steps_shop_name']; ?>：</div>					
                                <div id="shop_name_select" class="imitate_select select_w145">
                                    <div class="cite"><?php echo $this->_var['lang']['select_please']; ?></div>
                                    <ul>
                                       <li><a href="javascript:;" data-value="0"><?php echo $this->_var['lang']['select_please']; ?></a></li>
                                       <li><a href="javascript:;" data-value="1"><?php echo $this->_var['lang']['s_shop_name']; ?></a></li>
                                       <li><a href="javascript:;" data-value="2"><?php echo $this->_var['lang']['s_qw_shop_name']; ?></a></li>
                                       <li><a href="javascript:;" data-value="3"><?php echo $this->_var['lang']['s_brand_type']; ?></a></li>
                                    </ul>
                                    <input name="store_search" type="hidden" value="0" id="shop_name_val">
                                </div>
                            </div>
                            <div class="select ml0" style="display:none" id="merchant_box">
                                <div class="imitate_select select_w145">
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
                            </div>
                            <div class="select ml0" style="display:none" id="store_keyword">
								<input type="text" value="" name="store_keyword" class="text" autocomplete="off" />
                            </div>
                            <div class="select ml0" style="display:none" id="store_type">
                                <div class="imitate_select select_w145">
                                    <div class="cite"><?php echo $this->_var['lang']['steps_shop_type']; ?></div>
                                    <ul>
                                       <li><a href="javascript:;" data-value="0"><?php echo $this->_var['lang']['steps_shop_type']; ?></a></li>
                                       <li><a href="javascript:;" data-value="<?php echo $this->_var['lang']['flagship_store']; ?>"><?php echo $this->_var['lang']['flagship_store']; ?></a></li>
                                       <li><a href="javascript:;" data-value="<?php echo $this->_var['lang']['exclusive_shop']; ?>"><?php echo $this->_var['lang']['exclusive_shop']; ?></a></li>
                                       <li><a href="javascript:;" data-value="<?php echo $this->_var['lang']['franchised_store']; ?>"><?php echo $this->_var['lang']['franchised_store']; ?></a></li>
                                       <li><a href="javascript:;" data-value="<?php echo $this->_var['lang']['shop_store']; ?>"><?php echo $this->_var['lang']['shop_store']; ?></a></li>
                                    </ul>
                                    <input name="store_type" type="hidden" value="0" >
                                </div>
                            </div>
                            <div class="input">
                                <input type="text" name="adName" class="text nofocus" placeholder="广告名称" autocomplete="off" /><input type="submit" value="" class="not_btn" />
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
                                    <th width="3%" class="sign"><div class="tDiv"><input type="checkbox" name="all_list" class="checkbox" id="all_list" /><label for="all_list" class="checkbox_stars"></label></div></th>
                                    <th width="5%"><div class="tDiv"><a href="javascript:listTable.sort('ad_id'); "><?php echo $this->_var['lang']['record_id']; ?></a></div></th>
                                    <th width="14%"><div class="tDiv"><a href="javascript:listTable.sort('ad_name'); "><?php echo $this->_var['lang']['ad_name']; ?></a></div></th>
                                    <th width="8%"><div class="tDiv"><?php echo $this->_var['lang']['goods_steps_name']; ?></div></th>
                                    <th width="15%"><div class="tDiv"><a href="javascript:listTable.sort('position_id'); "><?php echo $this->_var['lang']['position_id']; ?></a></div></th>
                                    <th width="8%"><div class="tDiv"><a href="javascript:listTable.sort('media_type'); "><?php echo $this->_var['lang']['media_type']; ?></a></div></th>
                                    <th width="10%"><div class="tDiv"><a href="javascript:listTable.sort('start_date'); "><?php echo $this->_var['lang']['start_date']; ?></a></div></th>
                                    <th width="10%"><div class="tDiv"><a href="javascript:listTable.sort('end_date'); "><?php echo $this->_var['lang']['end_date']; ?></a></div></th>
                                    <th width="8%"><div class="tDiv tc"><a href="javascript:listTable.sort('click_count'); "><?php echo $this->_var['lang']['click_count']; ?></a></div></th>
                                    <th width="8%"><div class="tDiv tc"><?php echo $this->_var['lang']['ads_stats']; ?></div></th>
                                    <th width="12%" class="handle"><?php echo $this->_var['lang']['handler']; ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $_from = $this->_var['ads_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                            	<tr>
                                    <td class="sign"><div class="tDiv"><input type="checkbox" name="checkbox" class="checkbox" id="checkbox_<?php echo $this->_var['list']['ad_id']; ?>" /><label for="checkbox_<?php echo $this->_var['list']['ad_id']; ?>" class="checkbox_stars"></label></div></td>
                                    <td><div class="tDiv"><?php echo $this->_var['list']['ad_id']; ?></div></td>
                                    <td><div class="tDiv"><span onclick="listTable.edit(this, 'edit_ad_name', <?php echo $this->_var['list']['ad_id']; ?>)" title="<?php echo htmlspecialchars($this->_var['list']['ad_name']); ?>" data-toggle="tooltip" class="span"><?php echo htmlspecialchars($this->_var['list']['ad_name']); ?></span></div></td>
                                    <td><div class="tDiv"><?php if ($this->_var['list']['user_name']): ?><font style="color:#F00;"><?php echo $this->_var['list']['user_name']; ?></font><?php else: ?><font class="blue"><?php echo $this->_var['lang']['self']; ?></font><?php endif; ?></div></td>
                                    <td><div class="tDiv"><?php if ($this->_var['list']['position_id'] == 0): ?><?php echo $this->_var['lang']['outside_posit']; ?><?php else: ?><?php echo $this->_var['list']['position_name']; ?><?php endif; ?></div></td>
                                    <td>
                                        <div class="tDiv">
                                            <?php if (( $this->_var['list']['type'] == $this->_var['lang']['imgage'] )): ?>
                                            <span class="show">
                                                <a href="<?php if (strpos ( $this->_var['list']['ad_code'] , 'www' )): ?><?php echo $this->_var['list']['ad_code']; ?><?php else: ?>../data/afficheimg/<?php echo $this->_var['list']['ad_code']; ?><?php endif; ?>" class="nyroModal"><i class="icon icon-picture" onmouseover="toolTip('<img src=<?php if (strpos ( $this->_var['list']['ad_code'] , 'www' )): ?><?php echo $this->_var['list']['ad_code']; ?><?php else: ?>../data/afficheimg/<?php echo $this->_var['list']['ad_code']; ?><?php endif; ?>>')" onmouseout="toolTip()"></i></a>
                                            </span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td><div class="tDiv"><?php echo $this->_var['list']['start_date']; ?></div></td>
                                    <td><div class="tDiv"><?php echo $this->_var['list']['end_date']; ?></div></td>
                                    <td><div class="tDiv tc"><?php echo $this->_var['list']['click_count']; ?></div></td>
                                    <td><div class="tDiv tc"><?php echo $this->_var['list']['ad_stats']; ?></div></td>
                                    <td class="handle">
                                        <div class="tDiv a3">
                                            <?php if ($this->_var['list']['position_id'] == 0): ?>
                                            <a href="<?php if ($this->_var['ads_type'] == 1): ?>touch_ads.php<?php else: ?>ads.php<?php endif; ?>?act=add_js&type=<?php echo $this->_var['list']['media_type']; ?>&id=<?php echo $this->_var['list']['ad_id']; ?>" title="<?php echo $this->_var['lang']['add_js_code']; ?>" class="btn_see"><i class="sc_icon sc_icon_see"></i><?php echo $this->_var['lang']['view_content']; ?></a>
                                            <?php endif; ?>
                                            <a href="<?php if ($this->_var['ads_type'] == 1): ?>touch_ads.php<?php else: ?>ads.php<?php endif; ?>?act=edit&id=<?php echo $this->_var['list']['ad_id']; ?>" title="<?php echo $this->_var['lang']['edit']; ?>" class="btn_edit"><i class="icon icon-edit"></i><?php echo $this->_var['lang']['edit']; ?></a>
                                            <a href="javascript:;" onclick="listTable.remove(<?php echo $this->_var['list']['ad_id']; ?>, '<?php echo $this->_var['lang']['drop_confirm']; ?>')" title="<?php echo $this->_var['lang']['remove']; ?>" class="btn_trash"><i class="icon icon-trash"></i><?php echo $this->_var['lang']['remove']; ?></a>
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
    <script type="text/javascript" src="js/jquery.picTip.js"></script>
    <script type="text/javascript">
		listTable.recordCount = '<?php echo $this->_var['record_count']; ?>';
		listTable.pageCount = '<?php echo $this->_var['page_count']; ?>';
		
		<?php $_from = $this->_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
		listTable.filter.<?php echo $this->_var['key']; ?> = '<?php echo $this->_var['item']; ?>';
		<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
		
		$(function(){
			//点击查看图片
			$('.nyroModal').nyroModal();
		});
		
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

		function searchAds()
		{
			var frm = $("form[name='searchForm']");
			listTable.filter['store_search'] = Utils.trim(frm.find("input[name='store_search']").val());
			listTable.filter['merchant_id'] = Utils.trim(frm.find("input[name='merchant_id']").val());
			listTable.filter['store_keyword'] = Utils.trim(frm.find("input[name='store_keyword']").val());
			listTable.filter['store_type'] = Utils.trim(frm.find("input[name='store_type']").val());
			
			listTable.filter['adName'] = frm.find("input[name='adName']").val();
			listTable.filter['keyword'] = Utils.trim(frm.find("input[name='keyword']").val());
			listTable.filter['page'] = 1;
			
			listTable.loadList();
		}
    	$.gjSearch("-240px");  //高级搜索
    </script>
</body>
</html>
<?php endif; ?>
