<?php

namespace UMeng\Services\Push\iOS;

use Exception;
use UMeng\Services\Push\Notification\IOSNotification;

class IOSCustomizedCast extends IOSNotification {

    public function __construct() {
        parent::__construct();
        $this->data['type'] = 'customizedcast';
        $this->data['alias_type'] = null;
    }

    /**
     * @return void
     * @throws Exception
     */
    public function isComplete(): void {
        parent::isComplete();

        if (!array_key_exists('alias', $this->data) && !array_key_exists('file_id', $this->data))
            throw new Exception('You need to set alias or upload file for customizedcast!');
    }

    /**
     * Upload file with device_tokens or alias to Umeng
     *
     * @param $content
     *
     * @return void
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
