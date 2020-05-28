<?php $ddbStudioLink = 'https://www.deutsche-digitale-bibliothek.de/content/journal/ausstellungen'; ?>
<nav class="menu-container" id="menu-container">
    <div class="menu-header">
    <?php if ($exhibitType === 'litfass_ddb'): ?>
        <a href="<?php echo $ddbStudioLink; ?>" rel="noopener" target="_blank">
        <?php if ($navcolor === 'dark'): ?>
            <img src="<?php echo img('3_DDB_Logo_2_s_pos_RGB_R_96dpi.png'); ?>" alt="DDB Studio Logo">
        <?php else: ?>
            <img src="<?php echo img('3_DDB_Logo_2_s_pos_RGB_R_96dpi.png'); ?>" alt="DDB Studio Logo">
        <?php endif; ?>
        </a>
        <p class="menu-text menu-text-red">
        <?php if (!empty($institutions)): ?>
            <?php echo __('Eine virtuelle Ausstellung der<br>Deutschen Digitalen Bibliothek<br>in Zusammenarbeit mit'); ?>
        <?php else: ?>
            <?php echo __('Eine virtuelle Ausstellung der<br>Deutschen Digitalen Bibliothek'); ?>
        <?php endif; ?>
        </p>
        <ul>
        <?php foreach ($institutions as $institution): ?>
            <li>
                <?php if (!empty($institution['url'])): ?>
                <a target="_blank" rel="noopener" href="<?php echo strip_tags($institution['url']); ?>">
                <?php endif; ?>
                <?php echo strip_tags($institution['name']); ?>
                <?php if (!empty($institution['url'])): ?>
                </a>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <?php if (!empty($institutions)): ?>
        <p class="menu-text menu-text-blue"><?php echo __('Eine virtuelle Ausstellung von'); ?></p>
        <?php endif; ?>
        <ul>
        <?php foreach ($institutions as $institution): ?>
            <li>
                <?php if (!empty($institution['url'])): ?>
                <a target="_blank" rel="noopener" href="<?php echo strip_tags($institution['url']); ?>">
                <?php endif; ?>
                <?php echo strip_tags($institution['name']); ?>
                <?php if (!empty($institution['url'])): ?>
                </a>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
        </ul>
        <a href="<?php echo $ddbStudioLink; ?>" rel="noopener">
            <?php
            switch ($exhibitType) {
                case 'litfass_featured':
                    echo '<img src="' . img('2_ddb_api_dt_rot.png') . '" alt="Unterstützt von Deutsche Digitale Bibliothek">';
                    break;

                default:
                    echo '<img src="' . img('1_dbb_siegel_de_service_rot_invert.png') . '" alt="Ein Service von Deutsche Digitale Bibliothek">';
                    break;
            }
        ?>
        </a>
    <?php endif; ?>
    </div>
    <div class="menu-body">
        <div id="menu-scrollable" class="menu-scrollable">
            <ul id="menu" class="menu">
            <?php $menuChapterCounter = 0; ?>
            <?php foreach ($sectionTitles as $sectionKey => $sectionTitle): ?>
                <li data-menuanchor="s<?php echo $sectionKey; ?>" id="menuanchor-s<?php echo $sectionKey; ?>"
                <?php echo ($sectionTitle['type'] === 'ddb-litfass-chapter')? ' class="chapter"' : 'class="type-' . $sectionTitle['type'] . '"'; ?>>
                    <?php if($sectionKey == 0): ?>
                    <?php $startpagethumbnail = metadata('exhibit', 'startpagethumbnail'); ?>
                    <div class="menu-box menu-icon menu-icon-transparent icon-home"
                    <?php echo (isset($startpagethumbnail) && !empty($startpagethumbnail))? 'style="background-image:url('
                        . WEB_FILES . '/layout/startpagethumbnail/' . $startpagethumbnail . ');"' : ''; ?>></div>
                    <?php elseif ($sectionTitle['type'] === 'ddb-litfass-chapter'): $menuChapterCounter++; ?>
                    <div class="menu-box menu-number"><?php echo ExhibitDdbHelper::getLeadingZeroNum($menuChapterCounter); ?></div>
                    <?php else: ?>
                    <div class="menu-box menu-icon menu-icon-transparent icon-<?php echo $sectionTitle['menu_icon']; ?>"
                    <?php echo (!empty($sectionTitle['pagethumbnail']))? 'style="background-image:url('
                        . WEB_FILES . '/layout/pagethumbnail/' . $sectionTitle['pagethumbnail'] . ');"' : ''; ?>></div>
                    <?php endif; ?>
                    <a href="#s<?php echo $sectionKey; ?>"><?php echo $sectionTitle['title']; ?></a>
                </li>
            <?php endforeach; ?>
                <li class="type-privacy">
                    <div class="menu-box menu-icon menu-icon-transparent icon-privacy"></div>
                    <a href="https://www.deutsche-digitale-bibliothek.de/content/datenschutzerklaerung" rel="noopener" target="_blank"><?php echo __('Datenschutz'); ?></a>
                </li>
            </ul>
        </div>
    </div>
</nav>
