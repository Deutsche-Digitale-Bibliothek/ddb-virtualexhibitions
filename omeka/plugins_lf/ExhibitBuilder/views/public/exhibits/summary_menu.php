<nav class="menu-container" id="menu-container">
    <div class="menu-header">
        <h2>DDB Studio</h2>
        <p>Eine virtuelle Ausstellung der Deutschen Digitalen Bibliothek in Zusammenarbeit mit</p>
        <ul>
            <li><a href="#">Link zu Kooperationspartner</a></li>
            <li><a href="#">Link zu Kooperationspartner</a></li>
        </ul>
    </div>
    <div class="menu-body">
        <ul id="menu" class="menu">
        <?php foreach ($sectionTitles as $sectionKey => $sectionTitle): ?>
            <li data-menuanchor="s<?php echo $sectionKey; ?>">
                <a href="#s<?php echo $sectionKey; ?>"><?php echo $sectionTitle; ?></a>
            </li>
        <?php endforeach; ?>
        </ul>
    </div>
</nav>
