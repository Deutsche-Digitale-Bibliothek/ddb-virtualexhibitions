<?php
$pageTitle = __('Search Omeka ') . __('(%s total)', $total_results);
echo head(array('title' => $pageTitle, 'bodyclass' => 'search'));
$searchRecordTypes = get_search_record_types();
?>

<?php // echo search_filters(); ?>
<?php if ($total_results): ?>
<div class="bb bt">
    <?php echo pagination_links(); ?>
</div>
<div class="search-results">
    <div class="search-results-list">
        <ul class="omeka-results-list unstyled">
            <?php foreach (loop('search_texts') as $searchText): ?>
            <li class="omeka-item bt">
                <?php $record = get_record_by_id($searchText['record_type'], $searchText['record_id']); ?>
                <?php /** var_dump($searchText, $record); //*/ ?>
                <div class="omeka-summary-main-wrapper">
                    <div class="omeka-summary-main">
                        <h2>
                            <a href="<?php echo record_url($record, 'show'); ?>">
                                <?php echo $searchText['title'] ? $searchText['title'] : '[Unknown]'; ?></a>
                        </h2>
                        <!-- <div class="subtitle">
                            datum
                        </div> -->
                        <!-- <ul class="matches unstyled">
                            <li class="matching-item">
                                <span>
                                    matching string<br>
                                    <?php // echo $record->description; ?>
                                </span>
                            </li>
                        </ul> -->
                    </div>
                    <div class="omeka-extra">
                        <ul class="types unstyled">
                            <li>Typ: <?php echo $searchRecordTypes[$searchText['record_type']]; ?></li>
                        </ul>
                    </div>
                </div>
                <div class="omeka-summary-thumbnail-wrapper">
                    <div class="omeka-summary-thumbnail">
                        <?php
                        $thmbnailLink = '';
                        switch ($searchText->record_type) {
                            case 'Item':
                                if ($record->hasThumbnail()) {
                                    $thmbnailLink = link_to_item(
                                        file_image('square_thumbnail', array(), $record),
                                        array(), 'show', $record);
                                }
                                break;
                            case 'ExhibitPage':
                                if (isset($record->pagethumbnail) && !empty($record->pagethumbnail)) {

                                    $pageThumbnail = '<img class="ddb-omeka-page-thumbnail" '
                                        . 'alt="Exhibit Page" '
                                        . 'src="' . WEB_FILES. '/layout/pagethumbnail/'
                                        . $record->pagethumbnail . '">';

                                    $thmbnailLink = '<a href="'
                                        . record_url($record, 'show') . '">'
                                        . $pageThumbnail
                                        . '</a>';
                                }
                                break;
                            case 'Exhibit':
                                if (isset($record->cover) && !empty($record->cover)) {

                                    $pageThumbnail = '<img class="ddb-omeka-exhibit-thumbnail" '
                                        . 'alt="Exhibit" '
                                        . 'src="' . WEB_FILES. '/layout/cover/'
                                        . $record->cover . '">';

                                    $thmbnailLink = '<a href="'
                                        . record_url($record, 'show') . '">'
                                        . $pageThumbnail
                                        . '</a>';
                                }
                                break;
                            default:
                                # code...
                                break;
                        }
                        echo $thmbnailLink;
                        ?>
                        <!-- <a href="#" src=""></a> -->
                    </div>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
<div class="bt">
<?php echo pagination_links(); ?>
</div>
<?php else: ?>
<div id="no-results">
    <p><?php echo __('Your query returned no results.');?></p>
</div>
<?php endif; ?>

<?php echo foot(); ?>