<?php

namespace UMeng\Services\Push\Android;

use UMeng\Services\Push\Notification\AndroidNotification;

class AndroidGroupCast extends AndroidNotification {

    public function __construct() {
        parent::__construct();

        $this->data['type'] = 'groupcast';
        $this->data['filter'] = null;
    }
}
