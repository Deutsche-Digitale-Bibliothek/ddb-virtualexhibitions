<?php
// $pageTitle = __('Search') . ' ' . __('(%s total)', $total_results);
$pageTitle = $total_results  . ' ' . __('Search');
echo head(array('title' => $pageTitle, 'bodyclass' => 'search'));
$searchRecordTypes = get_search_record_types();
?>
<?php echo search_filters(); ?>
<?php if ($total_results): ?>
<?php echo pagination_links(); ?>
<table id="search-results">
    <thead>
        <tr>
            <th><?php echo __('Record Type');?></th>
            <th><?php echo __('Title');?></th>
        </tr>
    </thead>
    <tbody>
        <?php $filter = new Zend_Filter_Word_CamelCaseToDash; ?>
        <?php foreach (loop('search_texts') as $searchText): ?>
        <?php $record = get_record_by_id($searchText['record_type'], $searchText['record_id']); ?>
        <?php $recordType = $searchText['record_type']; ?>
        <?php if ($record): ?>
        <?php set_current_record($recordType, $record); ?>
        <tr class="<?php echo strtolower($filter->filter($recordType)); ?>">
            <td>
                <?php echo $searchRecordTypes[$recordType]; ?>
            </td>
            <td>
                <table style="margin:0;">
                    <tr>
                        <td style="border:0;width:0.5%;">
                        <?php if ($recordImage = record_image($recordType, 'square_thumbnail')): ?>
                            <?php echo link_to($record, 'show', $recordImage, array('class' => 'image')); ?>
                        <?php endif; ?>
                        </td>
                        <td style="border:0;">
                            <a href="<?php echo record_url($record, 'show'); ?>"><?php echo $searchText['title'] ? $searchText['title'] : '[Unknown]'; ?></a>
                            <?php if ($recordType === 'Item'): ?>
                                <?php $exhibitPages = ExhibitDdbHelper::findItemInExhibitPage($searchText->record_id); ?>
                                <?php if (is_array($exhibitPages) && !empty($exhibitPages)): ?>
                                <div>
                                    <p style="margin: 0.2em 0 0 0;">Objekt in Ausstellungsseiten:</p>
                                    <ul style="margin:0;padding: 0 0 0 16px;">
                                    <?php foreach ($exhibitPages as $page): ?>
                                        <?php echo ExhibitDdbHelper::getEditPageEntry($page); ?>
                                    <?php endforeach; ?>
                                    </ul>
                                </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <?php endif; ?>
        <?php endforeach; ?>
    </tbody>
</table>
<?php echo pagination_links(); ?>
<?php else: ?>
<div id="no-results">
    <p><?php echo __('Your query returned no results.');?></p>
</div>
<?php endif; ?>
<?php echo foot(); ?>
