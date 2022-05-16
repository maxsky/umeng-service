<?php

namespace UMeng\Services\Push\Android;

use UMeng\Services\Push\Notification\AndroidNotification;
use UMeng\Utils\UMengServiceException;

class AndroidCustomizedCast extends AndroidNotification {

    public function __construct() {
        parent::__construct();

        $this->data['type'] = 'customizedcast';
        $this->data['alias_type'] = null;
    }

    public function isComplete(): void {
        parent::isComplete();

        if (!array_key_exists('alias', $this->data) && !array_key_exists('file_id', $this->data)) {
            throw new UMengServiceException('You need to set alias or upload file for customizedcast!');
        }
    }

    /**
     * Upload file with device_tokens or alias to UMeng
     *
     * @param string $content
     *
     * @return void return file_id if SUCCESS, else throw Exception with details.
     * @throws UMengServiceException
     */
    public function uploadContents(string $content) {
        $this->setUploadFileId($content);
    }

    public function getFileId() {
        return $this->data['file_id'] ?? null;
    }
}
