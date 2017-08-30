<?php
//{"lang":"","auth_pass":"d41d8cd98f00b204e9800998ecf8427e","error_reporting":1,"fm_root":""}
/*--------------------------------------------------
 | PHP FILE MANAGER
 +--------------------------------------------------
 | phpFileManager 1.0
 | By Fabricio Seger Kolling
 | Copyright (c) 2004-2017 Fabrício Seger Kolling
 | E-mail: dulldusk@gmail.com
 | URL: http://phpfm.sf.net
 | Last Changed: 2017-07-10
 +--------------------------------------------------
 | It is the AUTHOR'S REQUEST that you keep intact the above header information
 | and notify it only if you conceive any BUGFIXES or IMPROVEMENTS to this program.
 +--------------------------------------------------
 | LICENSE
 +--------------------------------------------------
 | Licensed under the terms of any of the following licenses at your choice:
 | - GNU General Public License Version 2 or later (the "GPL");
 | - GNU Lesser General Public License Version 2.1 or later (the "LGPL");
 | - Mozilla Public License Version 1.1 or later (the "MPL").
 | You are not required to, but if you want to explicitly declare the license
 | you have chosen to be bound to when using, reproducing, modifying and
 | distributing this software, just include a text file titled "LICENSE" in your version
 | of this software, indicating your license choice. In any case, your choice will not
 | restrict any recipient of your version of this software to use, reproduce, modify
 | and distribute this software under any of the above licenses.
 +--------------------------------------------------
 | CONFIGURATION AND INSTALATION NOTES
 +--------------------------------------------------
 | This program does not include any instalation or configuration
 | notes because it simply does not require them.
 | Just throw this file anywhere in your webserver and enjoy !!
 +--------------------------------------------------
*/
// +--------------------------------------------------
// | Header and Globals
// +--------------------------------------------------
if(!isset($_SERVER['PATH_INFO']) && isset($_SERVER["ORIG_PATH_INFO"])) {
    $_SERVER["PATH_INFO"] = $_SERVER["ORIG_PATH_INFO"];
}
if(!isset($_SERVER['DOCUMENT_ROOT'])) {
    if ( isset($_SERVER['SCRIPT_FILENAME']) ) $path = $_SERVER['SCRIPT_FILENAME'];
    elseif ( isset($_SERVER['PATH_TRANSLATED']) ) $path = str_replace('\\\\', '\\', $_SERVER['PATH_TRANSLATED']);
    $_SERVER['DOCUMENT_ROOT'] = str_replace( '\\', '/', substr($path, 0, 0-strlen($_SERVER['PHP_SELF'])));
}
if (@get_magic_quotes_gpc()) {
    function stripslashes_deep($value){
        return is_array($value)? array_map('stripslashes_deep', $value):$value;
    }
    $_POST = array_map('stripslashes_deep', $_POST);
    $_GET = array_map('stripslashes_deep', $_GET);
    $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
}
// Register Globals (its an old script..)
$blockKeys = array('_SERVER','_SESSION','_GET','_POST','_COOKIE');
foreach ($_GET as $key => $val) if (array_search($key,$blockKeys) === false) $$key=$val;
foreach ($_POST as $key => $val) if (array_search($key,$blockKeys) === false) $$key=$val;
foreach ($_COOKIE as $key => $val) if (array_search($key,$blockKeys) === false) $$key=$val;
// PHP_VERSION_ID is available as of PHP 5.2.7, if our version is lower than that, then emulate it
if (!defined('PHP_VERSION_ID')) {
    $version = explode('.', PHP_VERSION);
    define('PHP_VERSION_ID', ($version[0] * 10000 + $version[1] * 100 + $version[2]));
}
if (PHP_VERSION_ID < 50207) {
    define('PHP_MAJOR_VERSION',   $version[0]);
    define('PHP_MINOR_VERSION',   $version[1]);
    define('PHP_RELEASE_VERSION', $version[2]);
}
// Server Vars
function curl_server_online_check(){
    if (function_exists('curl_init')){
        @$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://phpfm.sf.net");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        @curl_exec($ch);
        $errnum = curl_errno($ch);
        @curl_close($ch);
    }
    return ($errnum == "0");
}
function socket_get_lan_ip($dest='64.0.0.0', $port=80) {
    $addr = '';
    if (function_exists('socket_create')){
        $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        socket_connect($socket, $dest, $port);
        socket_getsockname($socket, $addr, $port);
        socket_close($socket);
    }
    return $addr;
}
function get_client_ip() {
    $ipaddress = '';
    if ($_SERVER['HTTP_CLIENT_IP']) $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if($_SERVER['HTTP_X_FORWARDED_FOR']) $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if($_SERVER['HTTP_X_FORWARDED']) $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if($_SERVER['HTTP_FORWARDED_FOR']) $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if($_SERVER['HTTP_FORWARDED']) $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if($_SERVER['HTTP_X_REAL_IP']) $ipaddress = $_SERVER[