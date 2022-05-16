<?php

namespace UMeng\Services\Push\Android;

use UMeng\Services\Push\Notification\AndroidNotification;

class AndroidListCast extends AndroidNotification {

    public function __construct() {
        parent::__construct();

        $this->data['type'] = 'listcast';
        $this->data['device_tokens'] = null;
    }
}
