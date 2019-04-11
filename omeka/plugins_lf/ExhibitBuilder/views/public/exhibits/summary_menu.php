<nav class="menu-container" id="menu-container">
    <div class="menu-header">
        <p class="menu-text menu-text-blue">Eine virtuelle Ausstellung von</p>
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
        <p class="menu-text menu-text-red">erstellt mit</p>
        <a href="#"><img src="<?php echo img('ddb-studio-logo.png'); ?>" alt="DDB Studio Logo"></a>
    </div>
    <div class="menu-body">
        <div id="menu-scrollable" class="menu-scrollable">
            <ul id="menu" class="menu">
            <?php $menuChapterCounter = 0; ?>
            <?php foreach ($sectionTitles as $sectionKey => $sectionTitle): ?>
                <li data-menuanchor="s<?php echo $sectionKey; ?>" id="menuanchor-s<?php echo $sectionKey; ?>"
                <?php echo ($sectionTitle['type'] === 'ddb-litfass-chapter')? ' class="chapter"' : 'class="type-' . $sectionTitle['type'] . '"'; ?>>
                    <?php if($sectionKey == 0): ?>
                    <div class="menu-box menu-icon menu-icon-transparent icon-home"></div>
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
            </ul>
        </div>
    </div>
</nav>
