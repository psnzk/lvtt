<div class="ecsc-layout-left">
	<div class="sidebar" id="sidebar">
		<div class="column-menu">
			<div class="add-quickmenu">
				<a href="javascript:void(0);"><i class="icon-plus"></i>添加快捷功能菜单</a>
			</div>
			<ul id="seller_center_left_menu" style="padding-top:10px;">
				<?php $_from = $this->_var['user_menu_pro']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'menu_0_34939400_1481119655');if (count($_from)):
    foreach ($_from AS $this->_var['menu_0_34939400_1481119655']):
?>
				<li id="quicklink_<?php echo $this->_var['menu_0_34939400_1481119655']['action']; ?>" data-action="<?php echo $this->_var['menu_0_34939400_1481119655']['action']; ?>" style="display: block;"><a href="<?php echo $this->_var['menu_0_34939400_1481119655']['url']; ?>"> <?php echo $this->_var['menu_0_34939400_1481119655']['label']; ?> </a></li>
				<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>	
			</ul>
			<div class="sitemap-menu-arrow"></div>
			<div class="sitemap-menu">
				<div class="title-bar">
					<h2><i class="icon-sitemap"></i>管理导航<em>小提示：添加您经常使用的功能到首页侧边栏，方便操作。</em></h2>
					<span id="closeSitemap" class="close">X</span>
				</div>
				<div class="content" id="quicklink_list">
					<?php $_from = $this->_var['seller_menu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'menu_0_34955000_1481119655');if (count($_from)):
    foreach ($_from AS $this->_var['menu_0_34955000_1481119655']):
?>
					<dl>
						<dt><?php echo $this->_var['menu_0_34955000_1481119655']['label']; ?></dt>
						<?php $_from = $this->_var['menu_0_34955000_1481119655']['children']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'child_0_34962900_1481119655');if (count($_from)):
    foreach ($_from AS $this->_var['child_0_34962900_1481119655']):
?>
						<dd <?php if ($this->_var['child_0_34962900_1481119655']['status']): ?>class="selected"<?php endif; ?>>
							<i nctype="btn_add_quicklink" data-quicklink-act="<?php echo $this->_var['child_0_34962900_1481119655']['action']; ?>" class="icon-check" title="添加为常用功能菜单" data-action="<?php echo $this->_var['child_0_34962900_1481119655']['action']; ?>" data-status="<?php echo $this->_var['child_0_34962900_1481119655']['status']; ?>"></i>
							<a href="<?php echo $this->_var['child_0_34962900_1481119655']['url']; ?>"> <?php echo $this->_var['child_0_34962900_1481119655']['label']; ?> </a>
						</dd>
						<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
					</dl>
					<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" type="text/javascript">
$(document).ready(function(){
    //添加删除快捷操作
    $('[nctype="btn_add_quicklink"]').on('click', function() {
	
		//by wu start
		var menu_curr = $(this).data('action');
		var menu_status = $(this).data('status');
		var menu_left = $("#seller_center_left_menu").find("[data-action="+menu_curr+"]");
		if(menu_left.length > 0)
		{
			menu_left.remove();
		}

		var menu_count = $('#seller_center_left_menu').find('li').length;
		if(menu_status ==0 && menu_count>7)
		{
			prototype("<i class='alert_error'></i>快捷操作最多添加8个","错误提示");
			return;
		}
		
		$.ajax({
			type:'get',
			url:'index.php',
			data:'act=change_user_menu&action='+menu_curr+'&status='+menu_status,
			dataType:'json',
			success:function(data)
			{
				if(data.error == 1)
				{
					$('[nctype="btn_add_quicklink"][data-action='+menu_curr+']').attr('data-status', 1);
				}
				if(data.error == 2)
				{
					$('[nctype="btn_add_quicklink"][data-action='+menu_curr+']').attr('data-status', 0);
				}				
			}			
		})		
		//by wu end	
	
        var $quicklink_item = $(this).parent();
        var items = $(this).attr('data-quicklink-act');
        if($quicklink_item.hasClass('selected'))
		{
            $quicklink_item.removeClass("selected");
        }
		else
		{
			$quicklink_item.addClass("selected");
			var $link = $quicklink_item.find('a');
			var menu_name = $link.text();
			var menu_link = $link.attr('href');
			var menu_item='<li id="quicklink_' + items + '" data-action="' + menu_curr + '"><a href="' + menu_link + '">' + menu_name + '</a></li>'
			$(menu_item).appendTo('#seller_center_left_menu').hide().fadeIn();
        }
    })
});

//添加快捷菜单 by wu
function addUserMenu(obj, memu)
{
	var um = $(obj);
	if(um.parents().attr('class') == undefined)
	{
		alert(um.parents().attr('class'));
	}
	if(um.parents().attr('class') == 'selected')
	{
		
	}
}
</script>