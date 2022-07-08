<?php
namespace Drupal\cb_apache_log_parser\Utils;
use Drupal\node\Entity\Node;

/**
 * apache log file parser class
 * This is a utility class that may be useful.
 *
 * @author Paul Scott, James Tarleton
 * @package utilitiesmv 
 * @copyright Paul Scott, James Tarleton 2006-2021
 */

class CbApacheLog   {
    /**
     * @param void
     * @return void
     * @access public
     */
    public function __construct() {

    }

/**
     * @param void
     * @return void
     * @access public
     */
    public function createEntry($array) {
        if(!array($array)) {
            return;
        }
        /**
        * add Node  $requestarr = array('fullrecord' => $line, 
        'ip' => $ip, 'date' => $date, 'ts' => $ts, 
        'request' => $request, 'servercode' => $servercode, 
        'requrl' => $requrl, 
        'useragent' => $useragent);

        */
        $node = Node::create(['type' => 'cb_apache_log']);
        $node->langcode = "en";
        $node->uid = 1;
        $node->promote = 0;
        $node->sticky = 0;
        $node->title= substr( $array['request'],0, 6);
        $node->body =  $array['fullrecord'];
        $node->field_ts = $array['ts'];
       // $node->field_date = $array['date'];
        $node->field_ip = $array['ip'];
        $node->field_visitor_host = $this->_gethost( $array['ip']);
        $node->field_servercode = $array['servercode'];
        $node->field_useragent = $array['useragent'];
        $node->field_request = $array['request'];
        $node->field_requrl =  substr($array['requrl'], 0, 130);
        $node->field_ts = $array['ts'];
        $node->save();

    } 
    public function _gethost($ip)
    {     /*
        $ipnum = str_replace('.','',$ip);
        if(intval($ipnum)>0){
            $ServerIP = 'ahost'; //gethostbyaddr($ip);
          } else {
            $ServerIP = $ip; // A bad address.
          } 
    
       $host = `host $ip`;
       $host=end(explode(' ',$host));
       $host=substr($host,0,strlen($host)-2);
       $chk=explode("\(",$host);
       if($chk[1]) return $ip." (".$chk[1].")";
       else return $host; */
       $host =    gethostbyaddr($ip) ;
       return (!empty($host )) ? $host : 'fdsjkfhsdk';
    }

}
