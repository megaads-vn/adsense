<?php 
namespace Megaads\Adsense\Utils;

use Config;
use DB;
use Illuminate\Support\Facades\Route;

class Adsense 
{

    public function __construct()
    {
        
    }

    public function init() {
        
    }

    public static function display($params = []) {
        return view('adsense::ads', $params);
    }

    public static function option($key, $default = NULL) {
        $retval = $default;
        $configTbl = Config::get('adsense.config_tbl', 'config');
        $findConfig = DB::table($configTbl)->where('key', $key)->first(['value', 'data_type']);
        if (!empty($findConfig)) {
            $retval = $findConfig->value;
            if ($findConfig->data_type == 'json') {
                $retval = json_decode($retval, false);
            }
        }
        return $retval;
    }

    public static function isDisplayAdsenseBlock($config) {
        $retVal = true;
        $requestIp = self::getRealIpAddr();
        $wasIpAllowed = self::isAllowIp($config->ip_range, $requestIp);
        $routeParameters = Route::current()->parameters();
        $slug = isset($routeParameters['slug']) ? $routeParameters['slug'] : null;
        $configType = $config->type;
        $configStores = $config->stores;
        if (empty($slug)) {
            $retVal = false;
        } else if (!empty($slug) && $configType == 'block' && (empty($configStores) || in_array($slug, $configStores))) {
            $retVal = false;
        } else if (!empty($slug) && $configType == 'allow' && (!empty($configStores) && !in_array($slug, $configStores))) {
            $retVal = false;
        }
        if ($retVal && !$wasIpAllowed) {
            $retVal = false;
        } else if ($retVal && !$config->enable) {
            $retVal = false;
        }
        return $retVal;
    }
    
    public static function getRealIpAddr() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {   //check ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {   //to check ip is pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    public static function isAllowIp($ipRange, $requestIp) {
        $retVal = TRUE;
        $ip2Long = ip2long($requestIp);
        $ipRange = str_replace("\\", "", $ipRange);
        $data = json_decode($ipRange);
        if (count($data) > 0) {
            foreach ($data as $range) {
                if ($ip2Long >= $range->start && $ip2Long <= $range->end) {
                    $retVal = FALSE;
                    break;
                }
            }
        }
        return $retVal;
    }

    public static function passParamsToDirective($expression) {
        $retVal = [];
        if (!empty($expression)) {
            foreach ($expression as $items) {
                $explodeItem = explode('=', $items);
                $retVal[$explodeItem[0]] = $explodeItem[1];
            }
        }
        return $retVal;
    }
}