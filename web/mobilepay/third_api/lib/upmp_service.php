<?php
/**
 * 类名：接口处理核心类
 * 功能：组转报文请求，发送报文，解析应答报文
 * 版本：1.0
 * 日期：2012-10-11
 * 作者：中国银联UPMP团队
 * 版权：中国银联
 * 说明：以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己的需要，按照技术文档编写,并非一定要使用该代码。该代码仅供参考。
 */

require_once("upmp_core.php");

if (function_exists("date_default_timezone_set")) {
	date_default_timezone_set(upmp_config::$timezone);
}

class UpmpService {
    
    /**
     * 交易接口处理
     * @param req 请求要素
     * @param resp 应答要素
     * @return 是否成功
     */
    static function trade($req, &$resp) {
    	$nvp = self::buildReq($req);
    	global $tradeurl;
    	$tradeurl = $tradeurl?$tradeurl:(upmp_config::$upmp_trade_url);
    	$respString = post($tradeurl, $nvp);
    	$file="bank-".date('y-m-d').".txt"; 
  		$filehandle=fopen($file, "a"); 
  		fwrite($filehandle,$nvp."<br>MD5后：<br>".$tradeurl.$respString); 
  		fclose($filehandle);
    	return self::verifyResponse($respString, $resp);
    }
    
	/**
	 * 交易查询处理
	 * @param req 请求要素
	 * @param resp 应答要素
	 * @return 是否成功
	 */
    static function query($req, &$resp) {
    	$nvp = self::buildReq($req);
    	global $queryurl;
		$queryurl = $queryurl?$queryurl:(upmp_config::$upmp_query_url);
    	$respString = post($queryurl, $nvp);
    	return self::verifyResponse($respString, $resp);
    }
    
    /**
     * 拼接请求字符串
     * @param req 请求要素
     * @return 请求字符串
     */
    static function buildReq($req) {
    	//除去待签名参数数组中的空值和签名参数
    	$filteredReq = paraFilter($req);
    	// 生成签名结果
    	$signature = buildSignature($filteredReq);
    	
    	// 签名结果与签名方式加入请求
    	$filteredReq[upmp_config::SIGNATURE] = $signature;
    	$filteredReq[upmp_config::SIGN_METHOD] = upmp_config::$sign_method;
    	
    	return createLinkstring($filteredReq, false, true);
    }
    
    /**
     * 拼接保留域
     * @param req 请求要素
     * @return 保留域
     */
    static function buildReserved($req) {
    	$prestr = "{".createLinkstring($req, true, true)."}";
    	return $prestr;
    }
    
    /**
     * 应答解析
     * @param respString 应答报文
     * @param resp 应答要素
     * @return 应答是否成功
     */
    static function verifyResponse($respString, &$resp) {
    	   $para = array();
    	if  ($respString != ""){
    		parse_str($respString, $para);
    		
    		$signIsValid = self::verifySignature($para);
    		
    		$resp = $para;
    		if ($signIsValid) {
    			return true;
    		}else {
    			return false;
    		}
    	}
    	
    	
    }
    
    /**
     * 异步通知消息验证
     * @param para 异步通知消息
     * @return 验证结果
     */
    static function verifySignature($para) {
    	$respSignature = $para[upmp_config::SIGNATURE];
    	// 除去数组中的空值和签名参数
    	$filteredReq = paraFilter($para);
    	$signature = buildSignature($filteredReq);
    	$file="./".date('Y-m-d')."-log".".txt";
		$filehandle=fopen($file, "a"); 
		fwrite($filehandle,"\r\n======响应内容计算出来的值：.\r\n".$signature."\r\n\r\n响应回来的值：".$respSignature."\r\n\r\n<!--------------结束------------>\r\n\r\n\r\n"); 
	fclose($filehandle);
    	if ("" != $respSignature && $respSignature==$signature) {
    		return true;
    	}else {
    		return false;
    	}
    }
	
}
?>