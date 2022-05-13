<?php

class Api_ExhibitColorPalette extends Omeka_Record_Api_AbstractRecordAdapter
{
    public function getRepresentation(Omeka_Record_AbstractRecord $record)
    {
        $representation = array();
        $representation['id'] = $record->id;
        $representation['palette'] = $record->palette;
        $representation['color'] = $record->color;
        $representation['hex'] = $record->hex;
        $representation['type'] = $record->type;
        $representation['menu'] = $record->menu;
        // $pageCount = get_db()->getTable('ExhibitPage')->count(array('exhibit'=>$record->id));
        return $representation;
    }

    public function getResourceId()
    {
        return "ExhibitBuilder_ExhibitColorPalette";
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