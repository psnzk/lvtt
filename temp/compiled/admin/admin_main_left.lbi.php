<div class="admin-main-left">
    <div class="admincj_nav">
        <div class="item current">
        	<div class="tit"><a href="javascript:void(0);" data-url="index.php?act=main" data-param="index|main" target="workspace"><i class="nav_icon i_home"></i><h4>首页</h4></a></div>
        </div>
        <?php $_from = $this->_var['nav_top']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'nav_0_62440000_1483515303');if (count($_from)):
    foreach ($_from AS $this->_var['nav_0_62440000_1483515303']):
?>
        <div class="navLeftTab" id="adminNavTabs_<?php echo $this->_var['nav_0_62440000_1483515303']['type']; ?>" style="display:none;">
            <?php $_from = $this->_var['nav_0_62440000_1483515303']['children']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('k', 'menu');$this->_foreach['foo'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['foo']['total'] > 0):
    foreach ($_from AS $this->_var['k'] => $this->_var['menu']):
        $this->_foreach['foo']['iteration']++;
?>
            <?php if (! $this->_var['menu']['action']): ?>
            <div class="item">
                <div class="tit"><a href="javascript:void(0)"><i class="nav_icon icon_<?php echo $this->_var['k']; ?>"></i><h4><?php echo $this->_var['menu']['label']; ?></h4></a></div>
                <?php if ($this->_var['menu']['children']): ?>
                <div class="sub-menu">
                    <ul>
                        <?php $_from = $this->_var['menu']['children']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'child');$this->_foreach['childfoo'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['childfoo']['total'] > 0):
    foreach ($_from AS $this->_var['key'] => $this->_var['child']):
        $this->_foreach['childfoo']['iteration']++;
?>
                        <li <?php if (($this->_foreach['childfoo']['iteration'] <= 1)): ?>class="curr"<?php endif; ?>><s></s><a href="javascript:void(0);" data-url="<?php echo $this->_var['child']['action']; ?>" data-param="<?php echo $this->_var['nav_0_62440000_1483515303']['type']; ?>|<?php echo $this->_var['key']; ?>" target="workspace"><?php echo $this->_var['child']['label']; ?></a></li>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </ul>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </div>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </div>
</div>