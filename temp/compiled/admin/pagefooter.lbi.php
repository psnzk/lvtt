<div id="footer">
    <p><?php echo $this->_var['lang']['copyright']; ?></p>
</div>
<script type="text/javascript">
$(function(){
	
	<?php if ($this->_var['cat_belongs'] == 0): ?>
		$.jqueryAjax('dialog.php', 'is_ajax=1&act=dialog_upgrade', function(data){
			var content = data.content;
			pb({
				id:"categroy_dialog",
				title:"温馨提示",
				width:788,
				content:content,
				ok_title:"确定",
				drag:false,
				foot:false,
				cl_cBtn:false
			});			
		});
	<?php endif; ?>

	$("*[data-toggle='tooltip']").tooltip({
		position: {
			my: "left top+5",
			at: "left bottom"
		}
	});
});
</script>