<?php
//zend by QQ:2172298892
namespace app\http\index\controllers;

class Api extends \app\http\site\controllers\Index
{
	public function actionIndex()
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
		header('Access-Control-Allow-Headers: X-HTTP-Method-Override, Content-Type, x-requested-with, Authorization');
		$module = array(
			array(
				'id'     => 'mod-123456',
				'module' => 'header',
				'data'   => array('use_lbs' => true)
				),
			array(
				'id'     => 'mod-223456',
				'module' => 'slider',
				'data'   => array(
					array('img' => '//m.360buyimg.com/mobilecms/s720x322_jfs/t3466/159/1673036314/98401/52f24697/582d7253Nfbb2ccb1.jpg!q70.jpg', 'url' => ''),
					array('img' => '//img1.360buyimg.com/da/jfs/t3571/319/1616309303/100087/2d2d4e6f/582c189cN5f15e1e7.jpg', 'url' => ''),
					array('img' => '//img1.360buyimg.com/da/jfs/t3481/310/1527924808/100659/337391d8/582aa8bbNbda77a28.jpg', 'url' => ''),
					array('img' => '//m.360buyimg.com/mobilecms/s720x322_jfs/t3835/260/1362577098/88254/a87797d7/582bd904N5d401415.jpg!q70.jpg', 'url' => ''),
					array('img' => '//img1.360buyimg.com/da/jfs/t3838/310/1093179147/88726/6c981ec1/5823f816N4eb35f11.jpg', 'url' => ''),
					array('img' => '//m.360buyimg.com/mobilecms/s720x322_jfs/t3361/309/1597879668/90295/8486cff9/582c1a12N6afd1e9e.jpg!q70.jpg', 'url' => ''),
					array('img' => '//m.360buyimg.com/mobilecms/s720x322_jfs/t3541/227/1686424628/86808/e0a5b02e/582d728eN82ec4a36.jpg!q70.jpg', 'url' => ''),
					array('img' => '//m.360buyimg.com/mobilecms/s720x322_jfs/t3661/288/1667840217/65815/ce835a34/582e56aeN004242af.jpg!q70.jpg', 'url' => '')
					)
				),
			array(
				'id'     => 'mod-323456',
				'module' => 'nav',
				'data'   => array(
					array('name' => '商创超市', 'icon' => '//m.360buyimg.com/mobilecms/s80x80_jfs/t3664/66/52394080/14046/acfe1fa3/57fdae81Ne7ddbab9.png', 'url' => ''),
					array('name' => '全球购', 'icon' => '//m.360buyimg.com/mobilecms/s80x80_jfs/t3286/167/1907269933/15789/da204cbe/57d53f16Nf3431cbd.png', 'url' => ''),
					array('name' => '服装城', 'icon' => '//m.360buyimg.com/mobilecms/s80x80_jfs/t3208/285/1806438443/12227/e35aa8d/57d5407cN0d6adf20.png', 'url' => ''),
					array('name' => '商创生鲜', 'icon' => '//m.360buyimg.com/mobilecms/s80x80_jfs/t3079/222/1812395993/14681/29321e2c/57d54122N700d9c1b.png', 'url' => ''),
					array('name' => '商创到家', 'icon' => '//m.360buyimg.com/mobilecms/s80x80_jfs/t3526/59/1682136876/18624/c2527e82/582db4b5N726903c5.png', 'url' => ''),
					array('name' => '充值中心', 'icon' => '//m.360buyimg.com/mobilecms/s80x80_jfs/t3352/333/597400517/7357/7a0bb4bd/580eb210N30889c25.png', 'url' => ''),
					array('name' => '惠赚钱', 'icon' => '//m.360buyimg.com/mobilecms/s80x80_jfs/t3424/278/301037516/11616/98748707/58096edbNcd05f66b.png', 'url' => ''),
					array('name' => '领券', 'icon' => '//m.360buyimg.com/mobilecms/s80x80_jfs/t2998/309/2273695416/11154/f4ae1409/57d542e9N71c56086.png', 'url' => ''),
					array('name' => '物流查询', 'icon' => '//m.360buyimg.com/mobilecms/s80x80_jfs/t3199/169/1818813995/12570/62402b0d/57d54364Needc47cd.png', 'url' => ''),
					array('name' => '我的关注', 'icon' => '//m.360buyimg.com/mobilecms/s80x80_jfs/t3211/295/1824792746/12749/a74e2524/57d543ebN25337ef2.png', 'url' => '')
					)
				),
			array(
				'id'     => 'mod-423456',
				'module' => 'press',
				'data'   => array(
					array('title' => '物流查询', 'time' => '2011-08-05', 'url' => ''),
					array('title' => '物流查询', 'time' => '2011-08-05', 'url' => ''),
					array('title' => '物流查询', 'time' => '2011-08-05', 'url' => ''),
					array('title' => '物流查询', 'time' => '2011-08-05', 'url' => '')
					)
				),
			array(
				'id'     => 'mod-523456',
				'module' => 'seckill',
				'data'   => array(
					array('goods_name' => '轻雅多维营养代餐粉 果蔬纤维 膳食纤维 魔芋粉产品低卡纤体奶昔饱腹非左旋肉碱减肥16天量', 'goods_thumb' => 'http://192.168.1.92/dscmall/images/201407/thumb_img/77_thumb_G_1405028778248.jpg', 'shop_price' => '$125.10', 'market_price' => '$225.50', 'url' => '#'),
					array('goods_name' => '轻雅多维营养代餐粉 果蔬纤维 膳食纤维 魔芋粉产品低卡纤体奶昔饱腹非左旋肉碱减肥16天量', 'goods_thumb' => 'http://192.168.1.92/dscmall/images/201407/thumb_img/77_thumb_G_1405028778248.jpg', 'shop_price' => '$125.10', 'market_price' => '$225.50', 'url' => '#'),
					array('goods_name' => '轻雅多维营养代餐粉 果蔬纤维 膳食纤维 魔芋粉产品低卡纤体奶昔饱腹非左旋肉碱减肥16天量', 'goods_thumb' => 'http://192.168.1.92/dscmall/images/201407/thumb_img/77_thumb_G_1405028778248.jpg', 'shop_price' => '$125.10', 'market_price' => '$225.50', 'url' => '#'),
					array('goods_name' => '轻雅多维营养代餐粉 果蔬纤维 膳食纤维 魔芋粉产品低卡纤体奶昔饱腹非左旋肉碱减肥16天量', 'goods_thumb' => 'http://192.168.1.92/dscmall/images/201407/thumb_img/77_thumb_G_1405028778248.jpg', 'shop_price' => '$125.10', 'market_price' => '$225.50', 'url' => '#')
					)
				),
			array(
				'id'     => 'mod-623456',
				'module' => 'ads',
				'data'   => array(
					'style' => 'style_1',
					'list'  => array(
						array('img' => '//m.360buyimg.com/mobilecms/s720x322_jfs/t3541/227/1686424628/86808/e0a5b02e/582d728eN82ec4a36.jpg!q70.jpg', 'url' => ''),
						array('img' => '//m.360buyimg.com/mobilecms/s720x322_jfs/t3541/227/1686424628/86808/e0a5b02e/582d728eN82ec4a36.jpg!q70.jpg', 'url' => ''),
						array('img' => '//m.360buyimg.com/mobilecms/s720x322_jfs/t3541/227/1686424628/86808/e0a5b02e/582d728eN82ec4a36.jpg!q70.jpg', 'url' => ''),
						array('img' => '//m.360buyimg.com/mobilecms/s720x322_jfs/t3541/227/1686424628/86808/e0a5b02e/582d728eN82ec4a36.jpg!q70.jpg', 'url' => '')
						)
					)
				),
			array(
				'id'     => 'mod-223456',
				'module' => 'slider',
				'data'   => array(
					array('img' => '//m.360buyimg.com/mobilecms/s720x322_jfs/t3541/227/1686424628/86808/e0a5b02e/582d728eN82ec4a36.jpg!q70.jpg', 'url' => ''),
					array('img' => '//m.360buyimg.com/mobilecms/s720x322_jfs/t3661/288/1667840217/65815/ce835a34/582e56aeN004242af.jpg!q70.jpg', 'url' => '')
					)
				),
			array(
				'id'     => 'mod-723456',
				'module' => 'heading',
				'data'   => array('title' => '精品推荐', 'subhead' => 'xxxx', 'url' => 'x', 'count_down' => '2017-11-11')
				),
			array(
				'id'     => 'mod-823456',
				'module' => 'more',
				'data'   => array(
					array('goods_name' => '轻雅多维营养代餐粉 果蔬纤维 膳食纤维 魔芋粉产品低卡纤体奶昔饱腹非左旋肉碱减肥16天量', 'goods_thumb' => 'http://192.168.1.92/dscmall/images/201407/thumb_img/77_thumb_G_1405028778248.jpg', 'shop_price' => '$125.10', 'market_price' => '$225.50', 'url' => '#'),
					array('goods_name' => '轻雅多维营养代餐粉 果蔬纤维 膳食纤维 魔芋粉产品低卡纤体奶昔饱腹非左旋肉碱减肥16天量', 'goods_thumb' => 'http://192.168.1.92/dscmall/images/201407/thumb_img/77_thumb_G_1405028778248.jpg', 'shop_price' => '$125.10', 'market_price' => '$225.50', 'url' => '#'),
					array('goods_name' => '轻雅多维营养代餐粉 果蔬纤维 膳食纤维 魔芋粉产品低卡纤体奶昔饱腹非左旋肉碱减肥16天量', 'goods_thumb' => 'http://192.168.1.92/dscmall/images/201407/thumb_img/77_thumb_G_1405028778248.jpg', 'shop_price' => '$125.10', 'market_price' => '$225.50', 'url' => '#'),
					array('goods_name' => '轻雅多维营养代餐粉 果蔬纤维 膳食纤维 魔芋粉产品低卡纤体奶昔饱腹非左旋肉碱减肥16天量', 'goods_thumb' => 'http://192.168.1.92/dscmall/images/201407/thumb_img/77_thumb_G_1405028778248.jpg', 'shop_price' => '$125.10', 'market_price' => '$225.50', 'url' => '#')
					)
				),
			array(
				'id'     => 'mod-923456',
				'module' => 'tabbar',
				'data'   => array(
					array('name' => '首页', 'icon' => 'i-home', 'url' => '#/', 'active' => 1),
					array('name' => '分类', 'icon' => 'i-cate', 'url' => '#/category', 'active' => 0),
					array('name' => '搜索', 'icon' => 'i-shop', 'url' => '#/search', 'active' => 0),
					array('name' => '购物车', 'icon' => 'i-flow', 'url' => '#/cart', 'active' => 0),
					array('name' => '我', 'icon' => 'i-user', 'url' => '#/user', 'active' => 0)
					)
				)
			);
		exit(json_encode($module));
	}
}

?>
