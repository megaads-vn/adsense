<?php
use Megaads\Adsense\Utils\Adsense;
?>
<script name="pkg-adsense-loadads">
    function adsByGooglePush() {
        setTimeout(function() {
            (adsbygoogle = window.adsbygoogle || []).push({})
        }, 1000);
    }
    // if (!window.isLoadedAds) {
    //     var scriptText = 'var isLoadedAds = true;',
    //         head = document.head || document.getElementsByTagName('head')[0],
    //         script = document.createElement('script');
    //     head.append(script);
    //     script.type = 'text/javascript';
    //     script.setAttribute('name', 'pkg-adsense-global-var');
    //     script.appendChild(document.createTextNode(scriptText));
        adsByGooglePush();
    // }
</script>
<?php
$config = Adsense::option('site.adsense', '');
$isDispAdsense = Adsense::isDisplayAdsenseBlock($config);

$alwayShows = isset($popup) ? $popup : false;
if ($isDispAdsense || $alwayShows) :
    $dataAdFormat = '';
    $adsGoogleStyle = "width:336px;height:280px;";
    if (isset($adsenseStyle)) {
        $adsGoogleStyle = $adsenseStyle;
    }
?>
    <div class="pkg-adsense-wrapper <?= isset($divClass) ? $divClass  : '' ?>">
        <ins class="ad_div adsbygoogled holder" style="display:block; background-color: transparent;<?= $adsGoogleStyle; ?>" data-ad-client="<?= $config->ads_client_id ?>" data-ad-slot="<?= $config->ads_slot_id ?>" <?php echo $dataAdFormat; ?>></ins>
    </div>
<?php endif; ?>