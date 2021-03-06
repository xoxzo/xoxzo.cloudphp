<?php
namespace xoxzo\cloudphp;
require 'vendor/autoload.php';

use \GuzzleHttp\Client as HttpClient;
use \GuzzleHttp\Exception\TransferException as TransferException;

define("XOXZO_HTTP_EXCEPTION", 499);

class XoxzoResponse
{
    public $errors;

    public $messages;

    function __construct($errors, $messages = array())
    {
        $this->errors = $errors;
        $this->messages = $messages;
    }
}

class XoxzoClient
{
    private $sid;
    private $auth_token;

    function __construct($sid, $auth_token)
    {
        $basic_auth_data = ['auth' => [$sid, $auth_token]];
        $this->client = new HttpClient();
        $api_host = "https://api.xoxzo.com";
        $this->xoxzo_api_sms_url = $api_host . "/sms/messages/";
        $this->xoxzo_api_call_url = $api_host . "/voice/calls/";
        $this->xoxzo_api_voice_simple_url = $api_host . "/voice/simple/playbacks/";
        $this->xoxzo_api_dins_url = $api_host . "/voice/dins/";
        $this->guzzle_options = ['http_errors' => false] + $basic_auth_data;
    }

    private function handleException($e)
    {
        if ($e->hasResponse()) {
            $resp = $e->getResponse();
            $stat = $resp->getStatusCode();
            $msgs = $resp->getBody()->getContents();
        }
        else {
            $stat = XOXZO_HTTP_EXCEPTION;
            $msgs = $e->getHandlerContext() ['error'];
        }

        return (new XoxzoResponse($stat, $msgs));
    }

    private function parse($resp)
    {
        $stat = $resp->getStatusCode();
        if (($stat == 200) or ($stat == 201)) {
            $stat = null;
        }

        $msgs = json_decode($resp->getBody());
        return (new XoxzoResponse($stat, $msgs));
    }

    public function send_sms($message, $recipient, $sender)
    {
        $params = ['form_params' => ['message' => $message, 'recipient' => $recipient, 'sender' => $sender]];
        try {
            $resp = $this->client->post($this->xoxzo_api_sms_url, $this->guzzle_options + $params);
            return $this->parse($resp);
        }
        catch(TransferException $e) {
            return $this->handleException($e);
        }
    }

    public function get_sms_delivery_status($msgid)
    {
        $url = $this->xoxzo_api_sms_url . $msgid;
        try {
            $resp = $this->client->get($url, $this->guzzle_options);
            return $this->parse($resp);
        }
        catch(TransferException $e) {
            return $this->handleException($e);
        }
    }

    public function get_sent_sms_list($sent_date="")
    {
        if ($sent_date != "") {
            $url = $this->xoxzo_api_sms_url . '?sent_date' . $sent_date;
        }
        else {
            $url = $this->xoxzo_api_sms_url;
        }

        try {
            $resp = $this->client->get($url, $this->guzzle_options);
            return $this->parse($resp);
        }
        catch(TransferException $e) {
            return $this->handleException($e);
        }
    }

    public function call_simple_playback($caller, $recipient, $recording_url)
    {
        $params = ['form_params' => ['caller' => $caller, 'recipient' => $recipient, 'recording_url' => $recording_url]];
        try {
            $resp = $this->client->post($this->xoxzo_api_voice_simple_url, $this->guzzle_options + $params);
            return $this->parse($resp);
        }
        catch(TransferException $e) {
            return $this->handleException($e);
        }
    }

    public function call_tts_playback($caller, $recipient, $tts_message, $tts_lang)
    {
        $params = ['form_params' => ['caller' => $caller, 'recipient' => $recipient, 'tts_message' => $tts_message, 'tts_lang' => $tts_lang]];
        try {
            $resp = $this->client->post($this->xoxzo_api_voice_simple_url, $this->guzzle_options + $params);
            return $this->parse($resp);
        }
        catch(TransferException $e) {
            return $this->handleException($e);
        }
    }

    public function get_simple_playback_status($callid)
    {
        $url = $this->xoxzo_api_call_url . $callid;
        try {
            $resp = $this->client->get($url, $this->guzzle_options);
            return $this->parse($resp);
        }
        catch(TransferException $e) {
            return $this->handleException($e);
        }
    }

    public function get_din_list($search_string="")
    {
        if ($search_string != "") {
            $url = $this->xoxzo_api_dins_url . "?" . $search_string;
        } else {
            $url = $this->xoxzo_api_dins_url;

        }
        try {
            $resp = $this->client->get($url, $this->guzzle_options);
            return $this->parse($resp);
        }
        catch(TransferException $e) {
            return $this->handleException($e);
        }
    }

    public function subscribe_din($din_uid)
    {
        $url = $this->xoxzo_api_dins_url . "subscriptions/";
        $params = ['form_params' => ['din_uid' => $din_uid]];
        try {
            $resp = $this->client->post($url, $this->guzzle_options + $params);
            return $this->parse($resp);
        }
        catch(TransferException $e) {
            return $this->handleException($e);
        }
    }

    public function unsubscribe_din($din_uid)
    {
        $url = $this->xoxzo_api_dins_url . "subscriptions/" . $din_uid . "/";

        try {
            $resp = $this->client->delete($url, $this->guzzle_options);
            return $this->parse($resp);
        }
        catch(TransferException $e) {
            return $this->handleException($e);
        }
    }

    public function get_subscription_list()
    {
        $url = $this->xoxzo_api_dins_url . "subscriptions/";

        try {
            $resp = $this->client->get($url, $this->guzzle_options);
            return $this->parse($resp);
        }
        catch(TransferException $e) {
            return $this->handleException($e);
        }
    }

    public function set_action_url($din_uid, $action_url)
    {
        $url = $this->xoxzo_api_dins_url . "subscriptions/" . $din_uid . "/";
        $params = ['form_params' => ['action_url' => $action_url]];
        try {
            $resp = $this->client->post($url, $this->guzzle_options + $params);
            return $this->parse($resp);
        }
        catch(TransferException $e) {
            return $this->handleException($e);
        }
    }
}

?>
