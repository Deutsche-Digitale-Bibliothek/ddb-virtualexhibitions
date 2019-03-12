<?php $files = $item->getFiles(); ?>
<?php if ($files && $files[0]): ?>
<div class="options-container d-lonely-none">
<label for="s_options-' . $order.'"><?php echo $label; ?></label>&nbsp;
<?php echo get_view()->formText("s_options[$order]", $options, ['class' => 'zoom_s_option', 'readonly' => 'readonly']); ?>
<button id="get-zoom-helper-<?php echo $order; ?>" class="zoom-helper-button" data-zoom-helperfor="s_options-<?php echo $order; ?>" data-zoom-image="<?php echo $files[0]->getWebPath('fullsize'); ?>">Zoomausschnitt wählen</button>
</div>
<?php endif; ?>
<script>
jQuery(document).ready(function($) {
    $('#get-zoom-helper-<?php echo $order; ?>').ginaZoomSelector();
});
</script>