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
                <div class="list-div" id="listDiv">
                	<table class="ecsc-default-table ecsc-table-seller mt20">
                        <tr>
                            <th width="30%"><?php echo $this->_var['lang']['item_name']; ?></th>
                            <th width="14%"><?php echo $this->_var['lang']['item_ifshow']; ?></th>
                            <th width="14%"><?php echo $this->_var['lang']['item_opennew']; ?></th>
                            <th width="14%"><?php echo $this->_var['lang']['item_vieworder']; ?></th>
                            <th width="14%"><?php echo $this->_var['lang']['item_type']; ?></th>
                            <th width="14%"><?php echo $this->_var['lang']['handler']; ?></th>
                        </tr>
                        <?php $_from = $this->_var['navdb']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'val');if (count($_from)):
    foreach ($_from AS $this->_var['val']):
?>
                        <tr>
                          <td align="left"><!-- <?php if ($this->_var['val']['id']): ?> --><?php echo $this->_var['val']['name']; ?><!-- <?php else: ?> -->&nbsp;<!-- <?php endif; ?> --></td>
                          <td align="center">
                           <!-- <?php if ($this->_var['val']['id']): ?> -->
                           <img src="images/<?php if ($this->_var['val']['ifshow'] == '1'): ?>yes<?php else: ?>no<?php endif; ?>.gif" onClick="listTable.toggle(this, 'toggle_ifshow', <?php echo $this->_var['val']['id']; ?>)" />
                           <!-- <?php endif; ?> --></td>
                          <td align="center">
                           <!-- <?php if ($this->_var['val']['id']): ?> -->
                            <img src="images/<?php if ($this->_var['val']['opennew'] == '1'): ?>yes<?php else: ?>no<?php endif; ?>.gif" onClick="listTable.toggle(this, 'toggle_opennew', <?php echo $this->_var['val']['id']; ?>)" />
                           <!-- <?php endif; ?> --></td>
                          <td align="center"><!-- <?php if ($this->_var['val']['id']): ?> --><span onClick="listTable.edit(this, 'edit_sort_order', <?php echo $this->_var['val']['id']; ?>)"><?php echo $this->_var['val']['vieworder']; ?></span><!-- <?php endif; ?> --></td>
                          <td align="center"><!-- <?php if ($this->_var['val']['id']): ?> --><?php echo $this->_var['lang'][$this->_var['val']['type']]; ?><!-- <?php endif; ?> --></td>
                          <td align="center" class="handler_icon">
                          <!-- <?php if ($this->_var['val']['id']): ?> -->
                          <a href="merchants_navigator.php?act=edit&id=<?php echo $this->_var['val']['id']; ?>" title="<?php echo $this->_var['lang']['edit']; ?>"><i class="icon icon-edit"></i></a>
                          <a href="merchants_navigator.php?act=del&id=<?php echo $this->_var['val']['id']; ?>" onClick="return confirm('<?php echo $this->_var['lang']['ckdel']; ?>');" title="<?php echo $this->_var['lang']['remove']; ?>"><i class="icon icon-trash"></i></a>
                          <!-- <?php endif; ?> -->
                          </td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr><td class="no-records" colspan="10"><?php echo $this->_var['lang']['no_records']; ?></td></tr>
                        <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
                        <tfoot>
                            <tr>
                                <td colspan="20">
                                    <?php echo $this->fetch('page.dwt'); ?>
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
<?php echo $this->fetch('library/seller_footer.lbi'); ?>
</body>
</html>
<?php endif; ?>
