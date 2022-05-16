<?php

namespace UMeng\Services\Push\Notification;

use UMeng\Utils\UMengServiceException;

abstract class IOSNotification extends UMengNotification {

    // The array for payload, please see API doc for more information
    protected $iosPayload = [
        'aps' => [
            'alert' => null
            //'badge'				=>  xx,
            //'sound'				=>	'xx',
            //'content-available'	=>	xx
        ]
        //'key1'	=>	'value1',
        //'key2'	=>	'value2'
    ];

    // Keys can be set in the aps level
    protected $APS_KEYS = ['alert', 'badge', 'sound', 'content-available'];

    public function __construct() {
        $this->data['payload'] = $this->iosPayload;
    }

    /**
     * Set key/value for $data array, for the keys which can be set please see $DATA_KEYS, $PAYLOAD_KEYS, $BODY_KEYS,
     * $POLICY_KEYS
     *
     * @param string $key
     * @param        $value
     *
     * @return void
     * @throws UMengServiceException
     */
    public function setPredefinedKeyValue(string $key, $value) {
        if (in_array($key, $this->DATA_KEYS)) {
            $this->data[$key] = $value;
        } elseif (in_array($key, $this->APS_KEYS)) {
            $this->data['payload']['aps'][$key] = $value;
        } elseif (in_array($key, $this->POLICY_KEYS)) {
            $this->data['policy'][$key] = $value;
        } else {
            if ($key == 'payload' || $key == 'policy' || $key == 'aps') {
                throw new UMengServiceException(
                    "You don't need to set value for $key , just set values for the sub keys in it."
                );
            } else {
                throw new UMengServiceException("Unknown key: $key");
            }
        }
    }

    /**
     * Set extra key/value for Android notification
     *
     * @param string $key
     * @param string $value
     *
     * @return void
     */
    public function setCustomizedField(string $key, string $value): void {
        $this->data['payload'][$key] = $value;
    }
}
