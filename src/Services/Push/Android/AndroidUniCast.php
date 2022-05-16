<?php

namespace UMeng\Services\Push\Android;

use UMeng\Services\Push\Notification\AndroidNotification;

class AndroidUniCast extends AndroidNotification {

    public function __construct() {
        parent::__construct();

        $this->data['type'] = 'type';
        $this->data['device_tokens'] = null;
    }
}
