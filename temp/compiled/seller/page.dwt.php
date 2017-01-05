<!-- $Id: page.htm 14216 2008-03-10 02:27:21Z testyang $ -->
<div id="turn-page">
    <span id="page-link">
        <div class="pagination">
            <ul>
                <li><?php if ($this->_var['filter']['page'] != 1): ?><a href="javascript:listTable.gotoPageFirst()"><?php endif; ?><span>首页</span><?php if ($this->_var['filter']['page'] != 1): ?></a><?php endif; ?></li>
                <li><?php if ($this->_var['filter']['page'] != 1): ?><a href="javascript:listTable.gotoPagePrev()"><?php endif; ?><span>上一页</span><?php if ($this->_var['filter']['page'] != 1): ?></a><?php endif; ?></li>
                <?php $_from = $this->_var['page_count_arr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'page_count_0_30237500_1481120241');$this->_foreach['pageCount'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['pageCount']['total'] > 0):
    foreach ($_from AS $this->_var['page_count_0_30237500_1481120241']):
        $this->_foreach['pageCount']['iteration']++;
?>
                <?php if ($this->_var['page_count_0_30237500_1481120241'] == $this->_var['filter']['page']): ?>
                	<li><span class="currentpage"><?php echo $this->_var['page_count_0_30237500_1481120241']; ?></span></li>
                <?php else: ?>
                	<li><a href="javascript:listTable.gotoPage(<?php echo $this->_var['page_count_0_30237500_1481120241']; ?>)"><span><?php echo $this->_var['page_count_0_30237500_1481120241']; ?></span></a></li>
                <?php endif; ?>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                <li><?php if ($this->_var['filter']['page'] != $this->_var['filter']['page_count']): ?><a href="javascript:listTable.gotoPageNext()"><?php endif; ?><span>下一页</span><?php if ($this->_var['filter']['page'] != $this->_var['filter']['page_count']): ?></a><?php endif; ?></li>
                <li><?php if ($this->_var['filter']['page'] != $this->_var['filter']['page_count']): ?><a href="javascript:listTable.gotoPageLast()" class="last"><?php endif; ?><span>末页</span><?php if ($this->_var['filter']['page'] != $this->_var['filter']['page_count']): ?></a><?php endif; ?></li>
            </ul>
        </div>
    </span>    
</div>
