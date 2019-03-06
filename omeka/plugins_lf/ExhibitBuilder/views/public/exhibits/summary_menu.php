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
        <img src="<?php echo img('ddb-studio-logo.png'); ?>" alt="DDB Studio Logo">
    </div>
    <div class="menu-body">
        <div id="menu-scrollable" class="menu-scrollable">
            <ul id="menu" class="menu">
            <?php foreach ($sectionTitles as $sectionKey => $sectionTitle): ?>
                <li data-menuanchor="s<?php echo $sectionKey; ?>" id="menuanchor-s<?php echo $sectionKey; ?>">
                    <?php if($sectionKey == 0): ?>
                    <div class="menu-box menu-icon menu-icon-transparent icon-home"></div>
                    <?php else: ?>
                    <div class="menu-box menu-icon menu-icon-transparent icon-text"
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
