<?php
use Megaads\Adsense\Utils\Adsense;
?>
<script name="pkg-adsense-loadads">
    function adsByGooglePush() {
        setTimeout(function() {
            (adsbygoogle = window.adsbygoogle || []).push({})
        }, 1000);

        setTimeout(function() {
            (adsbygoogle = window.adsbygoogle || []).push({})
        }, 1000);

        setTimeout(function() {
            (adsbygoogle = window.adsbygoogle || []).push({})
        }, 1000);
    }

    // if (!window.isLoadedAds) {
        // var scriptText = 'var isLoadedAds = true;',
        //     head = document.head || document.getElementsByTagName('head')[0],
        //     script = document.createElement('script');
        // head.append(script);
        // script.type = 'text/javascript';
        // script.setAttribute('name', 'pkg-adsense-global-var');
        // script.appendChild(document.createTextNode(scriptText));
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
$adsFooterStyle = "width:840px;height:120px;";
$adsSideStyle = "width:180px;height:568px;";
if (isset($adsenseStyle)) {
    $adsGoogleStyle = $adsenseStyle;
}
?>
<div class="activity-wrapper">
    <div class="activity-vertical activity-left">
        <div class="activity-content">
            <ins class="ad_div ads-left adsbygoogle holder" style="display:block; background-color: transparent;<?= $adsSideStyle; ?>" data-ad-client="<?= $config->ads_client_id ?>" data-ad-slot="<?= $config->ads_slot_id ?>" <?php echo $dataAdFormat; ?>></ins>
        </div>
        <div class="activity-action-bottom">
            <button class="activity-button">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"> <path fill-rule="evenodd" d="M7.646 4.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1-.708.708L8 5.707l-5.646 5.647a.5.5 0 0 1-.708-.708l6-6z"/> </svg>
            </button>
        </div>
    </div>
    <div class="activity-vertical activity-right">
        <div class="activity-content">
            <ins class="ad_div ads-right adsbygoogle holder" style="display:block; background-color: transparent;<?= $adsSideStyle; ?>" data-ad-client="<?= $config->ads_client_id ?>" data-ad-slot="<?= $config->ads_slot_id ?>" <?php echo $dataAdFormat; ?>></ins>
        </div>
        <div class="activity-action-bottom">
            <button class="activity-button">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"> <path fill-rule="evenodd" d="M7.646 4.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1-.708.708L8 5.707l-5.646 5.647a.5.5 0 0 1-.708-.708l6-6z"/> </svg>
            </button>
        </div>
    </div>
    <div class="activity-bottom">
        <button class="activity-bottom-button">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"> <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/> </svg>
        </button>
        <div class="activity-bottom-content">
            <ins class="ad_div ads-footer adsbygoogle holder" style="display:block; background-color: transparent;<?= $adsFooterStyle; ?>" data-ad-client="<?= $config->ads_client_id ?>" data-ad-slot="<?= $config->ads_slot_id ?>" <?php echo $dataAdFormat; ?>></ins>
        </div>
    </div>
</div>
<?php endif; ?>