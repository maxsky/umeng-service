<?php

namespace UMeng\Services\Push\iOS;

use UMeng\Services\Push\Notification\IOSNotification;

class IOSListCast extends IOSNotification {

    public function __construct() {
        parent::__construct();

        $this->data['type'] = 'listcast';
        $this->data['device_tokens'] = null;
    }
}
