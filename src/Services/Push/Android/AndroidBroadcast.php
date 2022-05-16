<?php

namespace UMeng\Services\Push\Android;

use UMeng\Services\Push\Notification\AndroidNotification;

class AndroidBroadcast extends AndroidNotification {

    public function __construct() {
        parent::__construct();

        $this->data['type'] = 'broadcast';
    }
}
