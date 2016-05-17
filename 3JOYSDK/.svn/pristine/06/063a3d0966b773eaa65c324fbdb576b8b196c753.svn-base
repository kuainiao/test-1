<?php

require_once 'Model.php';

class DownloadModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function selectAndDownloadUrl()
    {
        $db = $this->db;
        $result = $db->getResultsByCondition('sdk_download');
        foreach($result as $row)
        {
            return $row['NS_url'];
        }
    }

    public function updateDownloadCount()
    {
        $db = $this->db;
        $upOrderStatus = $db->updateTable('sdk_download','','');
        return $upOrderStatus;
    }

}