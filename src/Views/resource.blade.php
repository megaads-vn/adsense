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
            loadSideWideAds();
            setTimeout(function() {
                var footerAds = $('.ads-footer');
                var leftAds = $('.ads-left');
                var rigthAds = $('.ads-right');
                if ($(footerAds).html().length > 0) {
                    $('.activity-bottom').addClass('highlight');
                }
                if ($(rigthAds).html().length <= 0) {
                    $('.activity-right').hide();
                }
                if ($(leftAds).html().length <= 0) {
                    $('.activity-left').hide();
                }
            }, 1200);
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
        script.setAttribute("crossorigin", "anonymous");
        document.getElementsByTagName('head')[0].appendChild(script);
        return script;
    }

    function loadStyle() {
        var css = 'div.pkg-adsense-wrapper { padding: 10px 25px 20px;} div.pkg-adsense-wrapper .adsbygoogle {margin-left: -22px} .adsbygoogle .holder {text-align: center; vertical-align: middle; padding-top: 40px;} ',
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

    function loadSideWideAds() {
        var script = `var headerPosition = $('.site-header').position().top;
    var footerPosition = $('.site-footer').position().top;
    var footerHeight = $('.site-footer').height();
    var leftAds = $('.ads-left');
    var rigthAds = $('.ads-right');

    $(window).scroll(function () {
        var scroll = $(window).scrollTop();
        if (scroll > (headerPosition + '150') && scroll < (footerPosition - footerHeight - 370) && $(leftAds).html().length > 0) {
             $('.activity-left').fadeIn();
        } else {
             $('.activity-left').hide();
        }
        if (scroll > (headerPosition + '150') && scroll < (footerPosition - footerHeight - 370) && $(rigthAds).html().length > 0) {
             $('.activity-right').fadeIn();
        } else {
             $('.activity-right').hide();
        }
    });
    $('.activity-button').click(function() {
        $(this).parents('.activity-vertical').css({
            'transform': 'translateY(-30px)',
            'opacity': '0',
        });
    });
    $('.activity-bottom-button').click(function() {
        $(this).parents('.activity-bottom').removeClass('highlight');
    });
    `,
            body = document.body || document.getElementsByTagName('body')[0],
            footerScript = document.createElement('script');
        body.appendChild(footerScript);
        footerScript.type = 'text/javascript';
        footerScript.appendChild(document.createTextNode(script));
    }
</script>
<?php endif; ?>