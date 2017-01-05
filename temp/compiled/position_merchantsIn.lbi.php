
<div class="bd jbannerImg">
    <ul>
      <?php $_from = $this->_var['ad_child']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'child');if (count($_from)):
    foreach ($_from AS $this->_var['child']):
?>
          <li><a href="<?php echo $this->_var['child']['ad_link']; ?>" target="_blank"><img src="<?php echo $this->_var['child']['ad_code']; ?>" alt="" class="goodsimg" /></a></li>
      <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
    </ul>
</div>
<div class="jbannerTab hd">
    <ul class="fr">
    	<?php $_from = $this->_var['ad_child']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'child');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['child']):
?>
        <li><?php echo $this->_var['key']; ?></li>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>  
    </ul>
</div>  
