<?php

namespace UMeng\Services\Push\iOS;

use Exception;
use UMeng\Services\Push\Notification\IOSNotification;

class IOSFileCast extends IOSNotification {

    public function __construct() {
        parent::__construct();
        $this->data['type'] = 'filecast';
        $this->data['file_id'] = null;
    }

    /**
     * return file_id if SUCCESS, else throw Exception with details.
     *
     * @param $content
     *
     * @return void
     * @throws Exception
     */
    public function uploadContents($content) {
        $this->setUploadFileId($content);
    }

    /**
     * @return mixed|null
     */
    public function getFileId() {
        return $this->data['file_id'] ?? null;
    }
}
