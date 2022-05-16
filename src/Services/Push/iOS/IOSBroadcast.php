<?php

namespace UMeng\Services\Push\iOS;

use UMeng\Services\Push\Notification\IOSNotification;

class IOSBroadcast extends IOSNotification {

    public function __construct() {
        parent::__construct();

        $this->data['type'] = 'broadcast';
    }
}
