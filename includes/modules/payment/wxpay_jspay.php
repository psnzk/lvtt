<?php
/**
 * ECSHOP 微信支付
 * ============================================================================
 * 版权所有 2014 上海商创网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecmoban.com；
 * ============================================================================
 * $Author: z1988.com $
 * $Id: upop_wap.php 17063 2010-03-25 06:35:46Z douqinghua $
 */
if (!defined('IN_ECS')) {
    die('Hacking attempt');
}

// 包含配置文件
$payment_lang = ROOT_PATH . 'languages/' . $GLOBALS['_CFG']['lang'] . '/payment/'. basename(__FILE__);

if (file_exists($payment_lang)) {
    global $_LANG;

    include_once($payment_lang);
}


/* 模块的基本信息 */
if (isset($set_modules) && $set_modules == TRUE) {
    $i = isset($modules) ? count($modules) : 0;
    /* 代码 */
    $modules[$i]['code'] = basename(__FILE__, '.php');
    /* 描述对应的语言项 */
    $modules[$i]['desc'] = 'wxpay_jspay_desc';
    /* 是否支持货到付款 */
    $modules[$i]['is_cod'] = '0';
    /* 是否支持在线支付 */
    $modules[$i]['is_online'] = '1';
    /* 作者 */
    $modules[$i]['author'] = 'z1988.com';
    /* 网址 */
    $modules[$i]['website'] = 'http://mp.weixin.qq.com/';
    /* 版本号 */
    $modules[$i]['version'] = '3.3';
    /* 配置信息 */
    $modules[$i]['config'] = array(
        // 微信公众号身份的唯一标识
        array(
            'name' => 'wxpay_jspay_appid',
            'type' => 'text',
            'value' => ''
        ),
        // JSAPI接口中获取openid，审核后在公众平台开启开发模式后可查看
        array(
            'name' => 'wxpay_jspay_appsecret',
            'type' => 'text',
            'value' => ''
        ),
        // 商户支付密钥Key
        array(
            'name' => 'wxpay_jspay_key',
            'type' => 'text',
            'value' => ''
        ),
        // 受理商ID
        array(
            'name' => 'wxpay_jspay_mchid',
            'type' => 'text',
            'value' => ''
        )
    );
    
    return;
}

$lib_path	= dirname(__FILE__).'/wxpay/';
require_once $lib_path."WxPay.Config.php";
require_once $lib_path."WxPay.Api.php";
require_once $lib_path."WxPay.Notify.php";
require_once $lib_path."WxPay.JsApiPay.php";
require_once $lib_path."log.php";

/**
 * 微信支付类
 */
class wxpay_jspay
{
	private $dir  ;
	private $site_url;


	function _config( $payment )
	{
		WxPayConfig::set_appid( $payment['wxpay_jspay_appid'] );
		WxPayConfig::set_mchid( $payment['wxpay_jspay_mchid'] );
		WxPayConfig::set_key( $payment['wxpay_jspay_key'] );
		WxPayConfig::set_appsecret( $payment['wxpay_jspay_appsecret']);	
	}
	
	/**
     * 生成支付代码
     * @param   array   $order  订单信息
     * @param   array   $payment    支付方式信息
     */
	function get_code($order, $payment)
	{
		
		
		$html = '<div style="text-align:center"><button class="btn btn-primary c-btn3" type="button" onclick="javascript:alert(\'请在微信客户端打开链接\')">微信安全支付</button></div>';

		// 网页授权获取用户openid
        if (! isset($_SESSION['wxpay_jspay_openid']) || empty($_SESSION['wxpay_jspay_openid'])) {
			return $html;
            return false;
        }
		$openId = $_SESSION['wxpay_jspay_openid'];
		
		
		$this->_config($payment);
		$root_url = $GLOBALS['ecs']->url();
		//$root_url = str_replace('mobile/', '', $root_url);
		$notify_url = $root_url.'wxpay_jspay_notify.php';
		$return_url	= $GLOBALS['ecs']->url().'respond.php?code='.basename(__FILE__, '.php');
		
		$out_trade_no = $order['order_sn'] . 'O' . $order['log_id'];

		//统一下单
		$tools = new JsApiPay();
		$input = new WxPayUnifiedOrder();
		$input->SetBody( $order['order_sn'] );
		$input->SetAttach( $order['log_id'] );		//商户支付日志
		$input->SetOut_trade_no( $out_trade_no );		//商户订单号 
		$input->SetTotal_fee( strval(($order['order_amount']*100)) ); //总金额
		$input->SetTime_start(date("YmdHis"));
		//$input->SetTime_expire(date("YmdHis", time() + 600));
		//$input->SetGoods_tag("test");
		$input->SetNotify_url( $notify_url );	//通知地址 
		$input->SetTrade_type("JSAPI");	//交易类型
		$input->SetProduct_id( $order['order_sn'] );
		

		$input->SetOpenid($openId);
		$wxpay_order = WxPayApi::unifiedOrder($input);
		
		if ( $wxpay_order['return_code'] != 'FALL' ){
			$jsApiParameters = $tools->GetJsApiParameters($wxpay_order);
		
			$error = '';
			if ( strpos($jsApiParameters, 'error:') === 0 ){
				$error = str_replace('error:', '', $jsApiParameters);
				$jsApiParameters = '{}';
			}
		}else{
			$error = $wxpay_order['return_msg'];
		}
		
		
		$html = '<div style="text-align:center"><button class="btn btn-primary c-btn3" type="button" onclick="javascript:alert(\'请在微信客户端打开链接\')">微信安全支付</button></div>';
        if( empty($error) )
        {
			$js = '<script type="text/javascript">
				function jsApiCall()
				{
					WeixinJSBridge.invoke(
						"getBrandWCPayRequest",
						'.$jsApiParameters.',
						function(res){
							//WeixinJSBridge.log(res.err_msg);
							if(res.err_msg == "get_brand_wcpay_request:ok"){
								//alert(res.err_code+res.err_desc+res.err_msg);
								window.location.href = "'. $return_url .'&from=notify";
								//window.location.replace("'. $root_url .'");
							}else{
								//返回跳转到订单详情页面
								alert(支付失败);
								window.location.href = "./index.php";
							}
						}
					);
				}
				function callpay()
				{
					if (typeof WeixinJSBridge == "undefined"){
						if( document.addEventListener ){
							document.addEventListener("WeixinJSBridgeReady", jsApiCall, false);
						}else if (document.attachEvent){
							document.attachEvent("WeixinJSBridgeReady", jsApiCall); 
							document.attachEvent("onWeixinJSBridgeReady", jsApiCall);
						}
					}else{
						jsApiCall();
					}
				}
				</script>';
			$html = '<div style="text-align:center"><button class="btn btn-primary c-btn3" type="button" onclick="callpay()">微信安全支付</button></div>'.$js;
        }else{
			$html = '<div style="text-align:center"><button class="btn btn-primary c-btn3" type="button" onclick="javascript:alert(\''. $error .'\')">微信安全支付</button></div>';
		}
        
        return $html;
	}
	

    function respond()
    {
		$payment  = get_payment('wxpay_jspay');
		$this->_config($payment);

		$lib_path	= dirname(__FILE__).'/wxpay/';
		$logHandler= new CLogFileHandler($lib_path."logs/".date('Y-m-d').'.log');
		$log = Log::Init($logHandler, 15);
		
		Log::DEBUG("begin notify");
		$notify = new PayNotifyCallBack_jspay( );
		$notify->Handle(true);
		
		$data = $notify->data;
		
		//判断签名
			if ($data['result_code'] == 'SUCCESS') {
				
					$transaction_id = $data['transaction_id'];
				 // 获取log_id
                    $out_trade_no	= explode('O', $data['out_trade_no']);
                    $order_sn		= $out_trade_no[0];
					$log_id			= (int)$out_trade_no[1]; // 订单号log_id
					$payment_amount = $data['total_fee']/100;
						
					/* 检查支付的金额是否相符 */
					if (!check_money($log_id, $payment_amount))
					{
						echo 'fail';
						return false;
					}
						
					$action_note = 'result_code' . ':' 
					. $data['result_code']
					. ' return_code:'
					. $data['return_code']
					. ' orderId:'
					. $data['out_trade_no']		
					. ' openid:'
					. $data['openid']
					. ' '.$GLOBALS['_LANG']['wxpay_jspay_transaction_id'] . ':' 
					. $transaction_id;
					// 完成订单。
					order_paid($log_id, PS_PAYED, $action_note);
					
					return true;
			}else{
				echo 'fail';
			}
			
		return false;
		
    }

}

class PayNotifyCallBack_jspay extends WxPayNotify
{
	public  $data;
	//查询订单
	public function Queryorder($transaction_id)
	{
		$input = new WxPayOrderQuery();
		$input->SetTransaction_id($transaction_id);
		$result = WxPayApi::orderQuery($input);
		Log::DEBUG("query:" . json_encode($result));
		if(array_key_exists("return_code", $result)
			&& array_key_exists("result_code", $result)
			&& $result["return_code"] == "SUCCESS"
			&& $result["result_code"] == "SUCCESS")
		{
			return true;
		}
		return false;
	}
	
	//重写回调处理函数
	public function NotifyProcess($data, &$msg)
	{
		Log::DEBUG("call back:" . json_encode($data));
		
		$this->data = $data;
		$notfiyOutput = array();
		
		if(!array_key_exists("transaction_id", $data)){
			$msg = "输入参数不正确";
			return false;
		}
		//查询订单，判断订单真实性
		if(!$this->Queryorder($data["transaction_id"])){
			$msg = "订单查询失败";
			return false;
		}

		return true;
	}
}

?>