
<?php if ($this->_var['recommend_brands']): ?>
<div class="brand-logos">
    <div class="brand-warp-list">
        <ul>
        	<?php $_from = $this->_var['recommend_brands']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'brand');if (count($_from)):
    foreach ($_from AS $this->_var['brand']):
?>
            <li><a href="<?php echo $this->_var['brand']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['brand']['brand_logo']; ?>" width="129" height="57" alt="<?php echo $this->_var['brand']['brand_name']; ?>"></a></li>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </ul>
    </div>
    <a href="javascript:void(0);" class="prev"></a>
    <a href="javascript:void(0);" class="next"></a>
</div>
<?php endif; ?>