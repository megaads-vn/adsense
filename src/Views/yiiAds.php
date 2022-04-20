<script name="pkg-adsense-loadads">
    function adsByGooglePush() {
        setTimeout(function() {
            (adsbygoogle = window.adsbygoogle || []).push({})

        }, 1000);
    }
    adsByGooglePush();
</script>
<?php
$config = \Megaads\Adsense\Yii\MegaAdsenseApplication::getConfig();
$isDispAdsense = \Megaads\Adsense\Yii\MegaAdsenseApplication::isDisplayAdsenseBlock($config);
if ($isDispAdsense) :
    $dataAdFormat = '';
    $adsGoogleStyle = "width:336px;height:280px;";
    if (isset($_GET['adsenseStyle'])) {
        $adsGoogleStyle = $_GET['adsenseStyle'];
    }
?>
    <div class="pkg-adsense-wrapper <?= isset($divClass) ? $divClass  : '' ?>">
        <ins class="adsbygoogle holder" style="display:block; background-color: transparent;<?= $adsGoogleStyle; ?>" data-ad-client="<?= $config->ads_client_id ?>" data-ad-slot="<?= $config->ads_slot_id ?>" <?php echo $dataAdFormat; ?>></ins>
    </div>
<?php endif; ?>