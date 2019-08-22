<?php

namespace QL\Ext;

use JonnyW\PhantomJs\Client;

/**
 * @Author: hyq
 * @Date:   2019-08-16 19:27:52
 * @Last Modified by:   hyq
 * @Last Modified time: 2019-08-20 19:55:19
 * @version         1.0
 * PhantomJs操作扩展
 */

class PhantomJs extends AQuery
{
    
    protected static $browser = null;

    protected static function getBrowser($phantomJsBin,$commandOpt = [])
    {
        $defaultOpt = [
            '--load-images' => 'false',
            '--ignore-ssl-errors'  => 'true'
        ];
        $commandOpt = array_merge($defaultOpt, $commandOpt);
        
        if(self::$browser == null){
            self::$browser = Client::getInstance();
            self::$browser->getEngine()->setPath($phantomJsBin);
        }
        foreach ($commandOpt as $k => $v) {
            $str = sprintf('%s=%s',$k,$v);
            self::$browser->getEngine()->addOption($str);
        }
        return self::$browser;
    }
    
    public function run(array $args)
    {
        $defalut = [
            'binpath'=>'/usr/bin/phantomjs', 
            'debug' => false
        ];
        $args = array_merge($defalut,$args);
        $queryList = $this->getInstance();
        $phantomJsBin = $args['binpath'];
        $url = $args['url'];
        $debug = $args['debug'];
        $client = self::getBrowser($phantomJsBin);
        $request = $client->getMessageFactory()->createRequest();
        if($url instanceof Closure){
            $request = $url($request);
        }else{
            $request->setMethod('GET');
            $request->setUrl($url);
        }
        $response = $client->getMessageFactory()->createResponse();
        if($debug) {
            $client->getEngine()->debug(true);
        }
        $client->send($request, $response);
        if($debug){
            print_r($client->getLog());
            print_r($response->getConsole());
        }
        $html = $response->getContent();
//         print_r($html);
//         print_r(PHP_EOL . PHP_EOL);
        $queryList->html = '<!DOCTYPE html><html>' . $html . '</html>';
        //在 QueryList中添加setEncode
/**
//     public function setEncode($outputEncoding = 'UTF-8', $inputEncoding = null){
//         $outputEncoding && $this->outputEncoding = $outputEncoding;
//         $inputEncoding && $this->inputEncoding = $inputEncoding;
//         if(!$this->inputEncoding){
//             $this->inputEncoding = $this->_getEncode($this->html);
//         }
//     }
*/
        $queryList->setEncode('UTF-8'); 
        return $queryList;
    }

}