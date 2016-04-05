<?php

namespace xoxzo\cloudphp;
require 'vendor/autoload.php';

use \GuzzleHttp\Client as HttpClient;

class XoxzoClient {
  private $sid;
  private $auth_token;

  function __construct($sid, $auth_token){

    $this->sid = $sid;
    $this->auth_token = $auth_token;
    $this->basic_auth_data = ['auth' => [ $sid, $auth_token]];
    $this->client = new HttpClient();
    $api_host = "https://api.xoxzo.com";

    $this->xoxzo_api_sms_url = $api_host . "/sms/messages/";
    $this->xoxzo_api_voice_simple_url =  $api_host . "/voice/simple/playback/";
  }

  public function send_sms($message, $recipient, $sender){
    $params = ['form_params' => [
      'message' => $message,
      'recipient' => $recipient,
      'sender' => $sender
    ]];

    $resp = $this->client->post(
      $this->xoxzo_api_sms_url,
      $this->basic_auth_data + $params);
    return json_decode($resp->getBody());
  }

  public function get_sms_delivery_status($msgid){
    $url = $this->xoxzo_api_sms_url . $msgid;
    $resp = $this->client->get(
      $url,
      $this->basic_auth_data);
    return json_decode($resp->getBody());
  }

  public function call_simple_playback($caller, $recipient, $recording_url){
    $params = ['form_params' => [
      'caller' => $caller,
      'recipient' => $recipient,
      'recording_url' => $recording_url
    ]];

    $resp = $this->client->post(
      $this->xoxzo_api_voice_simple_url,
      $this->basic_auth_data + $params);
    return json_decode($resp->getBody());}

  public function get_simple_playback_status($callid){
    $url = $this->xoxzo_api_voice_simple_url . $callid;
    $resp = $this->client->get(
      $url,
      $this->basic_auth_data);
    return json_decode($resp->getBody());
  }
}
?>
