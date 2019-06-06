<?php
/**
 * @copyright Roy Rosenzweig Center for History and New Media, 2007-2012
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @package ExhibitBuilder
 */

/**
 * ExhibitPage model.
 *
 * @package ExhibitBuilder
 */
class ExhibitPage extends Omeka_Record_AbstractRecord
{
    public $id;
    public $parent_id; //@TODO: change this in database, and add to update scripts
    public $exhibit_id; //@TODO: change this in database, and add to update scripts
    public $layout;
    public $slug;
    public $title;
    public $hide_title;
    public $order;
    public $widget;
    public $pagethumbnail;
    public $backgroundcolor;
    public $pageoptions;

    protected $_related = array('ExhibitPageEntry'=>'loadOrderedChildren');

    public function _initializeMixins()
    {
        $this->_mixins[] = new Mixin_Order($this, 'ExhibitPageEntry', 'page_id', 'ExhibitPageEntry');
        $this->_mixins[] = new Mixin_Slug($this, array(
            'parentFields' => array('exhibit_id', 'parent_id'),
            'slugEmptyErrorMessage' => __('A slug must be given for each page of an exhibit.'),
            'slugLengthErrorMessage' => __('A slug must be 30 characters or less.'),
            'slugUniqueErrorMessage' => __('This page slug has already been used.  Please modify the slug so that it is unique.')));
        $this->_mixins[] = new Mixin_Search($this);
    }

    /**
     * In order to validate:
     * 1) must have a layout
     * 2) Must have a title
     * 3) must be properly ordered
     *
     * @return void
     */
    protected function _validate()
    {
        if (empty($this->layout)) {
            $this->addError('layout', __('A layout must be provided for each exhibit page.'));
        }

        if (!strlen($this->title)) {
            $this->addError('title', __('Exhibit pages must be given a title.'));
        }

    }

    protected function afterSave($args)
    {
        $exhibit = $this->getExhibit();
        if (!$exhibit->public) {
            $this->setSearchTextPrivate();
        }
        $this->setSearchTextTitle($this->title);
        $this->addSearchText($this->title);
        foreach ($this->ExhibitPageEntry as $entry) {
            $this->addSearchText($entry->text);
            $this->addSearchText($entry->caption);
        }

        if ($args['post']) {
            $post = $args['post'];

            // Begin Grandgeorg Websolutions
            if (isset($post['Text'])) {
                $textCount = count(@$post['Text']);
            } else {
                $textCount = 0;
            }
            if (isset($post['Item'])) {
                $itemCount = count(@$post['Item']);
            } else {
                $itemCount = 0;
            }
            // End Grandgeorg Websolutions

            $highCount = ($textCount > $itemCount) ? $textCount : $itemCount;

            $entries = $this->ExhibitPageEntry;

            for ($i=1; $i <= $highCount; $i++) {

                 // Begin Grandgeorg Websolutions
                if (isset($entries) && isset($entries[$i]) && !empty($entries[$i])) {
                    $ip = $entries[$i];
                } else {
                    $ip = new ExhibitPageEntry();
                    $ip->page_id = $this->id;
                }
                // End Grandgeorg Websolutions

                $text = @$post['Text'][$i];
                $item_id = (int) @$post['Item'][$i];
                $file_id = (int) @$post['File'][$i];
                $caption = @$post['Caption'][$i];
                // Begin Grandgeorg Websolutions
                $s_options = @$post['s_options'][$i];
                // End Grandgeorg Websolutions
                $ip->text = $text ? $text : null;
                // Begin Grandgeorg Websolutions
                $ip->caption = $caption ?
                    // htmlspecialchars(
                        substr(
                            html_entity_decode(
                                strip_tags($caption),
                                ENT_COMPAT | ENT_HTML5,
                                'UTF-8'),
                            0, 153)
                    // ENT_COMPAT | ENT_HTML5, 'UTF-8')
                    : null;
                $ip->s_options = $s_options ? $s_options : null;
                // End Grandgeorg Websolutions
                $ip->item_id = $item_id ? $item_id : null;
                $ip->file_id = $file_id ? $file_id : null;
                $ip->order = $i;
                $ip->save();
            }
        }
    }

    public function previous()
    {
        return $this->getDb()->getTable('ExhibitPage')->findPrevious($this);
    }

    public function next()
    {
        return $this->getDb()->getTable('ExhibitPage')->findNext($this);
    }

    public function firstChildOrNext()
    {
        if($firstChild = $this->getFirstChildPage()) {
            return $firstChild;
        } else {
            //see if there's a next page on the same level
            $next = $this->next();
            if($next) {
                return $next;
            }
            //no next on same level, so bump up one level and go to next page
            $parent = $this->getParent();
            if($parent) {
                $parentNext = $parent->next();
                return $parentNext;
            }
        }
    }

    public function previousOrParent()
    {
        $previous = $this->previous();
        if($previous) {
            if($previousLastChildPage = $previous->getLastChildPage()) {
                return $previousLastChildPage;
            }
            return $previous;
        } else {
            $parent = $this->getParent();
            if($parent) {
                return $parent;
            }
        }
    }

    public function getParent()
    {
        return $this->getTable()->find($this->parent_id);
    }

    public function getChildPages()
    {
        return $this->getTable()->findBy(array('parent'=>$this->id, 'sort_field'=>'order'));
    }

    public function getFirstChildPage()
    {
        return $this->getTable()->findEndChild($this, 'first');
    }

    public function getLastChildPage()
    {
        return $this->getTable()->findEndChild($this, 'last');
    }

    public function countChildPages()
    {
        return $this->getTable()->count(array('parent'=>$this->id));
    }

    /**
     * Get the ancestors of the page
     *
     * @return array
     */

    public function getAncestors()
    {
        $ancestors = array();
        $page = $this;
        while ($page->parent_id) {
            $page = $page->getParent();
            $ancestors[] = $page;
        }
        $ancestors = array_reverse($ancestors);
        return $ancestors;

    }

    public function getExhibit()
    {
        return $this->getTable('Exhibit')->find($this->exhibit_id);
    }

    protected function _delete()
    {
        if ($this->ExhibitPageEntry) {
            foreach ($this->ExhibitPageEntry as $ip) {
                $ip->delete();
            }
        }

        //bump all child pages up to being children of the parent
        $childPages = $this->getChildPages();
        foreach($childPages as $child) {
            if($this->parent_id) {
                $child->parent_id = $this->parent_id;
            } else {
                $child->parent_id = NULL;
            }
            $child->save();
        }
    }

    public function getPageEntries()
    {
        return $this->ExhibitPageEntry;
    }

    public function getRecordUrl($action = 'show')
    {
        if ('show' == $action) {
            return exhibit_builder_exhibit_uri($this->getExhibit(), $this);
        }
        return array('module' => 'exhibit-builder', 'controller' => 'exhibits',
                     'action' => $action, 'id' => $this->id);
    }
}
