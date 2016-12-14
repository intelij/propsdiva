<?php
$settings_url = '//cms.vicomi.com/products/feelback/settings?token='.get_option('vicomi_feelbacks_api_key');
?>

<div class="wrap">
    <iframe src="<?php echo $settings_url ?>" style="width: 100%; height: 80%; min-height: 600px;"></iframe>
</div>


