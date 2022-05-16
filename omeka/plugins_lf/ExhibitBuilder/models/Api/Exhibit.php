<?php

class Api_Exhibit extends Omeka_Record_Api_AbstractRecordAdapter
{
    public function getRepresentation(Omeka_Record_AbstractRecord $record)
    {
        $representation = array();
        $representation['id'] = $record->id;
        $representation['title'] = $record->title;
        $representation['subtitle'] = $record->subtitle;
        $representation['shorttitle'] = $record->shorttitle;
        $representation['url'] = self::getResourceUrl("/exhibits/{$record->id}");
        $representation['slug'] = $record->slug;
        $representation['description'] = $record->description;
        $representation['credits'] = $record->credits;
        $representation['featured'] = (bool) $record->featured;
        $representation['public'] = (bool) $record->public;
        $representation['exhibit_type'] = $record->exhibit_type;
        $representation['theme'] = $record->theme;
        $representation['institutions'] = unserialize($record->institutions);
        $representation['imprint'] = unserialize($record->imprint);
        $representation['legal'] = unserialize($record->legal);
        $representation['team'] = unserialize($record->team);
        $representation['titlebackground'] = $record->titlebackground;
        $representation['titlebackgroundcolor'] = $record->titlebackgroundcolor;
        $representation['titlebgpos'] = $record->titlebgpos;
        $representation['titleimage'] = $record->titleimage;
        $representation['titlelogo'] = $record->titlelogo;
        $representation['titlelogopos'] = $record->titlelogopos;
        $representation['startpagethumbnail'] = $record->startpagethumbnail;
        $representation['ctrl_colorscheme'] = $record->ctrl_colorscheme;
        $representation['colorpalette'] = $record->colorpalette;
        $representation['nav_color'] = $record->nav_color;

        $representation['added'] = self::getDate($record->added);
        $representation['modified'] = self::getDate($record->modified);
        $representation['owner'] = array(
                'id' => $record->owner_id,
                'resource' => 'users',
                'url' => self::getResourceUrl("/users/{$record->owner_id}")
                );
        $pageCount = get_db()->getTable('ExhibitPage')->count(array('exhibit'=>$record->id));
        $representation['pages'] = array(
                'count' => $pageCount,
                'resource' => 'exhibit_pages',
                'url' => self::getResourceUrl("/exhibit_pages?exhibit={$record->id}")
                );
        return $representation;
    }

    public function getResourceId()
    {
        return "ExhibitBuilder_Exhibits";
    }

    // Set data to a record during a POST request.
    public function setPostData(Omeka_Record_AbstractRecord $record, $data)
    {
        // Set properties directly to a new record.
    }

    // Set data to a record during a PUT request.
    public function setPutData(Omeka_Record_AbstractRecord $record, $data)
    {
        // Set properties directly to an existing record.
    }

}