
<div class="lazy-ecsc-brand w1200">
    <div class="in-title"><i class="icon-r"></i><a href="brand.php" target="_blank" class="bit">品牌街</a></div>
    <div class="brand-warp">
    	<?php 
$k = array (
  'name' => 'recommend_brands',
  'num' => '14',
);
echo $this->_echash . $k['name'] . '|' . serialize($k) . $this->_echash;
?>
        <?php 
$k = array (
  'name' => 'get_adv_child',
  'ad_arr' => $this->_var['index_brand_banner'],
);
echo $this->_echash . $k['name'] . '|' . serialize($k) . $this->_echash;
?>
    </div>
</div>
