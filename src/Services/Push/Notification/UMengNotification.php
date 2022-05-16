<?php

namespace UMeng\Services\Push\Notification;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use UMeng\Utils\UMengServiceException;

abstract class UMengNotification {

    // The upload path
    protected $uploadPath = '/upload';

    // The post path
    protected $postPath = '/api/send';

    // The app master secret
    protected $appMasterSecret = null;

    /*
     * $data is designed to construct the json string for POST request. Note:
     * 1)The key/value pairs in comments are optional.
     * 2)The value for key 'payload' is set in the subclass(AndroidNotification or IOSNotification), as their payload structures are different.
     */
    protected $data = [
        'appkey' => null,
        'timestamp' => null,
        'type' => null,
        //'device_tokens'  => 'xx',
        //'alias'          => 'xx',
        //'file_id'        => 'xx',
        //'filter'         => 'xx',
        //'policy'         => array('start_time' => 'xx', 'expire_time' => 'xx', 'max_send_num' => 'xx'),
        'production_mode' => 'true',
        //'feedback'       => 'xx',
        //'description'    => 'xx',
        //'thirdparty_id'  => 'xx'
    ];

    protected $DATA_KEYS = [
        'appkey', 'timestamp', 'type', 'device_tokens', 'alias', 'alias_type', 'file_id', 'filter', 'production_mode',
        'feedback', 'description', 'thirdparty_id'
    ];

    protected $POLICY_KEYS = ['start_time', 'expire_time', 'max_send_num'];

    // Set key/value for $data array, for the keys which can be set
    // please see $DATA_KEYS, $PAYLOAD_KEYS, $BODY_KEYS, $POLICY_KEYS
    abstract function setPredefinedKeyValue(string $key, $value);

    /**
     * @param string $secret
     *
     * @return void
     */
    public function setAppMasterSecret(string $secret) {
        $this->appMasterSecret = $secret;
    }

    /**
     * @return void
     * @throws UMengServiceException
     */
    public function isComplete(): void {
        if (is_null($this->appMasterSecret)) {
            throw new UMengServiceException('Please set your app master secret for generating the signature!');
        }

        $this->checkArrayValues($this->data);
    }

    /**
     * send the notification to umeng, return response data if SUCCESS, otherwise throw Exception with details.
     *
     * @return array|null
     * @throws UMengServiceException
     */
    public function send(): ?array {
        // check the fields to make sure that they are not null
        $this->isComplete();

        $url = UMENG_PUSH . $this->postPath;

        $client = new Client(['verify' => false]);

        try {
            $response = $client->post($url, [
                'query' => [
                    'sign' => md5('POST' . $url . json_encode($this->data) . $this->appMasterSecret)
                ],
                'json' => $this->data,
                'connect_timeout' => 30,
                'timeout' => 30
            ])->getBody();

        } catch (GuzzleException $e) {
            throw new UMengServiceException($e->getMessage(), $e->getCode(), $e);
        }

        return json_decode($response, true);
    }

    /**
     * Upload file with device_tokens or alias to UMeng
     *
     * @param string $content
     *
     * @return array
     * @throws UMengServiceException
     */
    protected function getUploadResult(string $content): array {
        if ($this->data['appkey'] == null) {
            throw new UMengServiceException('appkey should not be NULL!');
        }

        if ($this->data['timestamp'] == null) {
            throw new UMengServiceException('timestamp should not be NULL!');
        }

        $data = [
            'appkey' => $this->data['appkey'],
            'timestamp' => $this->data['timestamp'],
            'content' => $content
        ];

        $url = UMENG_PUSH . $this->uploadPath;

        $client = new Client(['verify' => false]);

        try {
            $response = $client->post($url, [
                'query' => [
                    'sign' => md5('POST' . $url . json_encode($data) . $this->appMasterSecret)
                ],
                'json' => $data,
                'connect_timeout' => 10,
                'timeout' => 10
            ])->getBody();
        } catch (GuzzleException $e) {
            throw new UMengServiceException($e->getMessage(), $e->getCode(), $e);
        }

        return json_decode($response, true);
    }

    /**
     * @param string $content
     *
     * @return void
     * @throws UMengServiceException
     */
    protected function setUploadFileId(string $content) {
        $result = $this->getUploadResult($content);

        if ($result['ret'] === 'FAIL') {
            $result = json_encode($result);

            throw new UMengServiceException("Failed to upload file, result: $result");
        } else {
            $this->data['file_id'] = $result['data']['file_id'];
        }
    }

    /**
     * @param array $arr
     *
     * @return void
     * @throws UMengServiceException
     */
    private function checkArrayValues(array $arr): void {
        foreach ($arr as $key => $value) {
            if (is_null($value)) {
                throw new UMengServiceException("$key is null!");
            } elseif (is_array($value)) {
                $this->checkArrayValues($value);
            }
        }
    }
}
