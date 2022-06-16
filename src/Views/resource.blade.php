<?php

use Megaads\Adsense\Utils\Adsense;

$config = Adsense::option('site.adsense', '');
$isDispAdsense = Adsense::isDisplayAdsenseBlock($config);
if ($isDispAdsense) : ?>
<script name="adsense-package">
    var pkgAdsenseLib = '//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js';
    if (!isLoadedStyle('pkg-adsense-style')) {
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
        let retval = false;
        const allScriptTag = document.querySelectorAll('script');
        if (allScriptTag.length > 0) {
            const pattern = new RegExp(lib, 'gi');
            for (const scriptTag of allScriptTag) {
                if (pattern.test(scriptTag.src)) {
                    retval = true;
                    break;
                }
            }
        }
        return retval;
    }

    function isLoadedStyle(attributeName) {
        return document.querySelectorAll('[name="' + attributeName + '"]').length > 0;
    }
    // Load library
    function loadScript(lib) {
        var script = document.createElement('script');
        script.setAttribute('src', lib);
        script.setAttribute('name', 'pkg-adsense-preload');
        document.getElementsByTagName('head')[0].appendChild(script);
        return script;
    }

    function loadStyle() {
        var css = 'div.pkg-adsense-wrapper { padding: 10px 25px 20px;} div.pkg-adsense-wrapper .adsbygoogle {margin-left: -22px} .adsbygoogle .holder {text-align: center; vertical-align: middle; padding-top: 40px;}',
            head = document.head || document.getElementsByTagName('head')[0],
            style = document.createElement('style');

        head.appendChild(style);

        style.type = 'text/css';
        style.setAttribute('name', 'pkg-adsense-style');
        if (style.styleSheet) {
            // This is required for IE8 and below.
            style.styleSheet.cssText = css;
        } else {
            style.appendChild(document.createTextNode(css));
        }
    }
</script>
<?php endif; ?>