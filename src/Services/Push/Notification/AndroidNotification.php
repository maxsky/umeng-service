<?php

namespace UMeng\Services\Push\Notification;

use UMeng\Utils\UMengServiceException;

abstract class AndroidNotification extends UMengNotification {

    // The array for payload, please see API doc for more information
    protected $androidPayload = [
        'display_type' => 'notification',
        'body' => [
            'ticker' => null,
            'title' => null,
            'text' => null,
            //'icon'       => 'xx',
            //largeIcon    => 'xx',
            'play_vibrate' => 'true',
            'play_lights' => 'true',
            'play_sound' => 'true',
            'after_open' => null,
            //'url'        => 'xx',
            //'activity'   => 'xx',
            //custom       => 'xx'
        ],
        //'extra'       => array('key1' => 'value1', 'key2' => 'value2')
    ];

    // Keys can be set in the payload level
    protected $PAYLOAD_KEYS = ['display_type'];

    // Keys can be set in the body level
    protected $BODY_KEYS = [
        'ticker', 'title', 'text', 'builder_id', 'icon', 'largeIcon', 'img', 'play_vibrate', 'play_lights',
        'play_sound', 'after_open', 'url', 'activity', 'custom'
    ];

    public function __construct() {
        $this->data['payload'] = $this->androidPayload;
    }

    /**
     * Set key/value for $data array, for the keys which can be set please see $DATA_KEYS, $PAYLOAD_KEYS, $BODY_KEYS,
     * $POLICY_KEYS
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return void
     * @throws UMengServiceException
     */
    public function setPredefinedKeyValue(string $key, $value) {
        if (in_array($key, $this->DATA_KEYS)) {
            $this->data[$key] = $value;
        } elseif (in_array($key, $this->PAYLOAD_KEYS)) {
            $this->data['payload'][$key] = $value;

            if ($key == 'display_type' && $value == 'message') {
                $this->data['payload']['body']['ticker'] = '';
                $this->data['payload']['body']['title'] = '';
                $this->data['payload']['body']['text'] = '';
                $this->data['payload']['body']['after_open'] = '';

                if (!array_key_exists('custom', $this->data['payload']['body'])) {
                    $this->data['payload']['body']['custom'] = null;
                }
            }
        } elseif (in_array($key, $this->BODY_KEYS)) {
            $this->data['payload']['body'][$key] = $value;

            if ($key == 'after_open' && $value == 'go_custom' && !($this->data['payload']['body']['customer'] ?? null)) {
                $this->data['payload']['body']['custom'] = null;
            }
        } elseif (in_array($key, $this->POLICY_KEYS)) {
            $this->data['policy'][$key] = $value;
        } else {
            if ($key == 'payload' || $key == 'body' || $key == 'policy' || $key == 'extra') {
                throw new UMengServiceException(
                    "You don't need to set value for $key, just set values for the sub keys in it."
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
     * @param mixed  $value
     *
     * @return void
     */
    public function setExtraField(string $key, $value) {
        $this->data['payload']['extra'][$key] = $value;
    }
}
