<?php
    $title = __('Bilder komprimieren');
    echo head(array('title' => html_escape($title)));
    echo flash();
?>
<?php if ($logfileExists && $checkState !== 'on'): ?>
<a class="button green button-big" href="<?php echo url('gina-image-convert/showlog');?>">
    <?php echo __('Letzte Komprimierung auswerten') ?>
</a>
<?php endif; ?>
<?php if ($checkState === 'on'): ?>
<div id="flash">
    <ul>
        <li class="success">Komprimierungsprozess ist im Gange, Bilder werden verarbeitet...</li>
    </ul>
</div>
<?php else: ?>
<form id="gina-image-compressall-form" method="post" enctype="multipart/form-data" oninput="document.getElementById('x').innerText=parseInt(a.value)">
    <div class="seven columns alpha">
        <?php echo common('image-compressall-form', array('params' => $params), 'compress'); ?>
    </div>
    <div id="save" class="three columns omega">
        <?php echo $this->formSubmit('compressall_submit', __('Auftrag "Alle Bilder komprimieren" jetzt absenden'), array('class'=>'submit big red button')); ?>
        <div style="background: #bb2020; border: 1px solid #791616;border-radius: 2px;color: #fff;text-shadow: -1px -1px 1px rgba(0,0,0,.5);padding:0.5rem">
        <h2 style="text-align:center;">Achtung!</h2>
        <p><strong>Beachten Sie, dass dieser Prozesser sehr rechenintensiv ist und nicht abgebrochen werden kann! Starten Sie erst, wenn Sie sich sicher sind, dass die Parameter richtig eingestellt sind. Wenn Sie Einstellungen erst ausprobieren möchten, komprimieren Sie zuerst einzelne Bilder.</strong></p></div>
    </div>
</form>
<?php endif; ?>
<?php echo foot(); ?>