<?php
if ($this->pageCount > 1):
    $getParams = $_GET;
// var_dump($this);
?>
<div class="page-info-nav">
    <div class="page-info">
        <span class="results-overall-index"><?php echo $this->firstItemNumber; ?> - <?php echo $this->lastItemNumber; ?></span> 
        <span>von </span> 
        <span><strong><span class="results-total"><?php echo $this->totalItemCount; ?></span></strong> </span> 
            <span class="results-label">Ergebnissen</span>
    </div>
    <div class="page-nav">
        <ul class="pagination inline">
            <?php if (isset($this->previous)): ?>
            <li class="first-page">
                <?php $getParams['page'] = 1; ?>
                <a class="page-nav-result noclickfocus" href="<?php echo html_escape($this->url(array(), null,  $getParams)); ?>">Anfang</a> 
            </li>
            <li class="pagination_previous prev-page br">
                <?php $getParams['page'] = $previous; ?>
                <a class="page-nav-result noclickfocus" href="<?php echo html_escape($this->url(array(), null, $getParams)); ?>">Zurück</a>
            </li>
            <?php endif; ?>
            
            <li class="pages-overall-index">
            <form action="<?php echo html_escape($this->url()); ?>" method="get" accept-charset="utf-8" style="margin:0;">
            <?php
            $hiddenParams = array();
            $entries = explode('&', http_build_query($getParams));
            foreach ($entries as $entry) {
                if(!$entry) {
                    continue;
                }
                list($key, $value) = explode('=', $entry);
                $hiddenParams[urldecode($key)] = urldecode($value);
            }

            foreach($hiddenParams as $key => $value) {
                if($key != 'page') {
                    echo $this->formHidden($key,$value);
                }
            }
            ?>
            <?php // echo __('Page'); ?>Seite <?php echo __('%s of %s', $this->formText('page', 
                $this->current, array('class' => 'page-input')), '<span class="total-pages">' . $this->last . '</span>'); ?>
            </form>
            </li>
            
            <?php if (isset($this->next)): ?> 
            <li class="pagination_next next-page bl">
                <?php $getParams['page'] = $next; ?>
                <a class="page-nav-result noclickfocus" href="<?php echo html_escape($this->url(array(), null, $getParams)); ?>">Weiter</a>
            </li>
            <li class="last-page">
                <?php $getParams['page'] = $this->last; ?>
                <a class="page-nav-result noclickfocus" href="<?php echo html_escape($this->url(array(), null, $getParams)); ?>">Ende</a>
            </li>

            <?php endif; ?>
        </ul>
    </div>
</div>

<?php endif; ?>
