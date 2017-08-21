<?php
namespace Atidata\finance;

class Exchange{

    private $_juhe = 'http://web.juhe.cn:8080/finance/exchange/rmbquot?key=%s';
    private $_juhe_key;

    public function __construct($juhekey){
        $this->_juhe_key = $juhekey;
        $this->_juhe = sprintf($this->_juhe, $this->_juhe_key);
    }

    public function getRmbQuot()
    {
        $result = $this->_requestCurl($this->_juhe);
        return $result;
    }

    /**
     * 请求远程服务器
     * @param  string  $url    请求的路径
     * @param  array/boolean $params 请求的参数，键值对数组
     * @param  boolean $ispost 是否为post请求
     * @return string          返回的字符串信息
     */
    private function _requestCurl($url,$params=FALSE,$ispost=FALSE)
    {
        $httpInfo = array();
        $ch = curl_init();

        curl_setopt( $ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1 );
        // curl_setopt( $ch, CURLOPT_USERAGENT , 'JuheData' );
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 60 );
        curl_setopt( $ch, CURLOPT_TIMEOUT , 60);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        if( $ispost )
        {
            curl_setopt( $ch , CURLOPT_POST , true );
            curl_setopt( $ch , CURLOPT_POSTFIELDS , $params );
            curl_setopt( $ch , CURLOPT_URL , $url );
        }
        else
        {
            if($params){
                curl_setopt( $ch , CURLOPT_URL , $url.'?'.$params );
            }else{
                curl_setopt( $ch , CURLOPT_URL , $url);
            }
        }
        $response = curl_exec( $ch );
        if ($response === FALSE) {
            //echo "cURL Error: " . curl_error($ch);
            return false;
        }
        $httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );
        $httpInfo = array_merge( $httpInfo , curl_getinfo( $ch ) );
        curl_close( $ch );
        unset($ch);
        return $response;
    }

}
