<?php

namespace UMeng\Services\Push\Android;

use Exception;
use UMeng\Services\Push\Notification\AndroidNotification;

class AndroidFileCast extends AndroidNotification {

    public function __construct() {
        parent::__construct();

        $this->data['type'] = 'filecast';
        $this->data['file_id'] = null;
    }

    /**
     * @param $content
     *
     * @return void return file_id if SUCCESS, else throw Exception with details.
     * @throws Exception
     */
    public function uploadContents($content) {
        $this->setUploadFileId($content);
    }

    public function getFileId() {
        if (array_key_exists('file_id', $this->data))
            return $this->data['file_id'];
        return null;
    }
}
