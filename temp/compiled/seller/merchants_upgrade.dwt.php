<?php if ($this->_var['full_page']): ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><?php echo $this->fetch('library/seller_html_head.lbi'); ?></head>

<body>
<?php echo $this->fetch('library/seller_header.lbi'); ?>
<?php echo $this->fetch('library/url_here.lbi'); ?>
<div class="ecsc-layout">
    <div class="site wrapper">
        <?php echo $this->fetch('library/seller_menu_left.lbi'); ?>
        <div class="ecsc-layout-right">
            <div class="main-content" id="mainContent">
				<?php echo $this->fetch('library/seller_menu_tab.lbi'); ?>
                <?php endif; ?>
                <form method="post" action="order.php?act=operate" name="listForm" onsubmit="return check()">
                <div class="table_list" id="listDiv">
                    <table border="0" cellpadding='1' cellspacing='1'  class="ecsc-default-table ecsc-table-seller mt20">
                        <tr>
                            <th width="12%"><?php echo $this->_var['lang']['grade_name']; ?></th>
                            <th width="10%"><?php echo $this->_var['lang']['goods_sun']; ?></th>
                            <th width="8%"><?php echo $this->_var['lang']['seller_temp']; ?></th>
                            <th width="18%"><?php echo $this->_var['lang']['grade_introduce']; ?></th>
                            <th width="30%"><?php echo $this->_var['lang']['entry_criteria']; ?></th>
                            <th width="12%"><?php echo $this->_var['lang']['grade_img']; ?></th>
                            <th width="10%"><?php echo $this->_var['lang']['handler']; ?></th>
                        </tr>
                        <?php $_from = $this->_var['garde_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                        <tr>
                            <td align="center" class="first-cell">
                                <?php echo htmlspecialchars($this->_var['list']['grade_name']); ?>
                            </td>
                            <td align="center"><?php echo htmlspecialchars($this->_var['list']['goods_sun']); ?></td>
                            <td align="center"><?php echo htmlspecialchars($this->_var['list']['seller_temp']); ?></td>
                            <td align="center"><?php echo htmlspecialchars($this->_var['list']['grade_introduce']); ?></td>
                            <td align="center"><span><?php echo $this->_var['list']['entry_criteria']; ?></span></td>
                            <td align="center"><?php if ($this->_var['list']['grade_img']): ?><a href="../<?php echo $this->_var['list']['grade_img']; ?>"  title="<?php echo $this->_var['lang']['see_img']; ?>" target="_blank"><img src="../<?php echo $this->_var['list']['grade_img']; ?>" width="50" height="50"></a><?php endif; ?></td>
                            <td align="center" nowrap="true">
                                <?php if ($this->_var['list']['id'] == $this->_var['grade_id']): ?>
                                    <span class="red">已成功</span>
                                <?php else: ?>           
                                    <a href="merchants_upgrade.php?act=application_grade&grade_id=<?php echo $this->_var['list']['id']; ?>" title="申请">立即申请</a>&nbsp;
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr><td class="no-records" colspan="10"><?php echo $this->_var['lang']['no_article']; ?></td></tr>
                        <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>

                    </table>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php echo $this->fetch('library/seller_footer.lbi'); ?>
<script type="text/javascript" language="JavaScript">
    onload = function()
    {
    // 开始检查订单
    startCheckOrder();
    }
    
</script>
</body>
</html>
