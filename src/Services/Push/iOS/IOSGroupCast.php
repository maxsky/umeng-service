<?php

namespace UMeng\Services\Push\iOS;

use UMeng\Services\Push\Notification\IOSNotification;

class IOSGroupCast extends IOSNotification {

    public function __construct() {
        parent::__construct();

        $this->data['type'] = 'groupcast';
        $this->data['filter'] = null;
    }
}
