<?php 
namespace Megaads\Adsense\Yii;

use CDbCriteria;
use Cfg;
use CJSON;
use Yii;

class MegaAdsenseApplication
{
    public $website;
    
    public $cdn;
    
    public function __construct($config=null) {}
    
    public static function init() {
        $config = self::getConfig();
        $isDispAdsese = self::isDisplayAdsenseBlock($config);
        if ($isDispAdsese) {
            Yii::app()->clientScript->registerScript('pkg-adsense-script', '
                var pkgAdsenseLib = "//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js";
                if (!isLoadedStyle("pkg-adsense-style")) {
                    loadStyle();
                }
                document.onreadystatechange = function() {
                    if (document.readyState == "complete") {
                        if (!isLoadedScript(pkgAdsenseLib)) {
                            loadScript(pkgAdsenseLib);
                        }
                    }
                }
    
                // Detect if library loaded
                function isLoadedScript(lib) {
                    return document.querySelectorAll(`[src="${lib}"]`).length > 0;
                }
    
                function isLoadedStyle(attributeName) {
                    return document.querySelectorAll(`[name="${attributeName}"]`).length > 0;
                }
                // Load library
                function loadScript(lib) {
                    var script = document.createElement("script");
                    script.setAttribute("src", lib);
                    script.setAttribute("name", "pkg-adsense-preload");
                    document.getElementsByTagName("head")[0].appendChild(script);
                    return script;
                }
    
                function loadStyle() {
                    var css = "div.pkg-adsense-wrapper { padding: 10px 25px 20px;} div.pkg-adsense-wrapper .adsbygoogle {margin-left: -22px} .adsbygoogle .holder {text-align: center; vertical-align: middle; padding-top: 40px;}",
                        head = document.head || document.getElementsByTagName("head")[0],
                        style = document.createElement("style");
    
                    head.appendChild(style);
    
                    style.type = "text/css";
                    style.setAttribute("name", "pkg-adsense-style");
                    if (style.styleSheet) {
                        // This is required for IE8 and below.
                        style.styleSheet.cssText = css;
                    } else {
                        style.appendChild(document.createTextNode(css));
                    }
                }   
            ');
        }
    }

    public static function isDisplayAdsenseBlock($config) {
        $retVal = true;
        $requestIp = self::getRealIpAddr();
        $wasIpAllowed = self::isAllowIp($config->ip_range, $requestIp);
        $currenUrl = Yii::app()->request->getPathInfo();
        $slug = preg_replace('/\/stores\/(\w+)-coupons\/(c\/(\d+))?/i', '$1', $currenUrl); 
        $currentPath =  preg_replace('/(\/stores\/\w+-coupons)\/(c\/(\d+))?/i', '$1', $currenUrl);
        $currentPath = "/" . $currentPath;
        if ($currentPath === '//') {
            $currentPath = '/';
        }
        $configType = $config->type;
        $configStores = $config->stores;
        $configUrls = isset($config->list_url) ? $config->list_url : "";
        if ($configUrls !== "") {
            $configUrls = explode("\n", $configUrls);
        } else {
            $configUrls = [];
        }
        if (empty($slug)) {
            $retVal = false;
        } else if (!empty($slug) && $configType == 'block' && (!empty($configStores) && in_array($slug, $configStores))) {
            $retVal = false;
        } else if (!empty($slug) && $configType == 'allow' && (!empty($configStores) && !in_array($slug, $configStores))) {
            $retVal = false;
        } else if ($configType == 'block' && (!empty($configUrls) && in_array($currentPath, $configUrls))) {
            $retVal = false;
        } else if ($configType == 'allow' && (!empty($configUrls) && !in_array($currentPath, $configUrls))) {
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


    public static function getConfig() {
        $retVal = NULL;
        Yii::import('application.modules.coupon.models.Cfg');
        $criteria = new CDbCriteria;
        $criteria->addCondition('`config_name` = \'site.adsense\'');
        $config = Cfg::model()->find($criteria);
        if (!empty($config)) {
            $retVal = CJSON::decode($config->config_value, false);
        }
        return $retVal;
    }

    public static function display($params = []) {
        $basePath = Yii::app()->basePath;
        $_GET['adsenseStyle'] = isset($params['adsenseStyle']) ? $params['adsenseStyle'] : '';
        require $basePath . '/../vendor/megaads/adsense/src/Views/yiiAds.php';
    }

}