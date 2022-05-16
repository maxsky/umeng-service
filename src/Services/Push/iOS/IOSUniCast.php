<?php

namespace UMeng\Services\Push\iOS;

use UMeng\Services\Push\Notification\IOSNotification;

class IOSUniCast extends IOSNotification {

    public function __construct() {
        parent::__construct();

        $this->data['type'] = 'unicast';
        $this->data['device_tokens'] = null;
    }
}
