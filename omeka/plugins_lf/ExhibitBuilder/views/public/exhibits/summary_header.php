<div id="header" class="header">
    <svg class="icon-expand icon" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px" y="0px" width="22px" height="22px" viewBox="0 0 22 22" enable-background="new 0 0 22 22" xml:space="preserve">
        <g transform="translate(2177 -848)">
            <path fill="none" stroke="#FFFFFF" stroke-width="2" d="M-2176,856v-7h7" />
            <line fill="none" stroke="#FFFFFF" stroke-width="2" x1="-2176" y1="849" x2="-2168" y2="857" />
            <path fill="none" stroke="#FFFFFF" stroke-width="2" d="M-2156,862v7h-7" />
            <line fill="none" stroke="#FFFFFF" stroke-width="2" x1="-2156" y1="869" x2="-2164" y2="861" />
        </g>
    </svg>
    <svg id="toggle-menu" class="icon-menu icon" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
        x="0px" y="0px" width="26px" height="18px" viewBox="0 0 26 18" enable-background="new 0 0 26 18" xml:space="preserve">
        <g transform="translate(2115 -827)" class="active">
            <line fill="none" stroke="#FFFFFF" stroke-width="2" x1="-2115" y1="828" x2="-2089" y2="828" />
            <line fill="none" stroke="#FFFFFF" stroke-width="2" x1="-2115" y1="836" x2="-2089" y2="836" />
            <line fill="none" stroke="#FFFFFF" stroke-width="2" x1="-2115" y1="844" x2="-2089" y2="844" />
        </g>
        <g>
            <line x1="1" y1="18" x2="18" y2="1" stroke="#FFFFFF" stroke-width="2"/>
            <line x1="1" y1="1" x2="18" y2="18" stroke="#FFFFFF" stroke-width="2"/>
        </g>
    </svg>
    <div class="header-section-bar" id="header-section-bar">
    <?php for ($i=0; $i < $sectionCounter; $i++): ?>
        <div data-headeranchor="<?php echo $i; ?>"></div>
    <?php endfor; ?>
    </div>
</div>
