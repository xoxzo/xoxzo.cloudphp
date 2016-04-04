<?php

namespace xoxzo\cloudphp;

class XoxzoClient {
  private $sid;
  private $auth_token;

  function __construct($sid, $auth_token){
    $this->sid = $sid;
    $this->auth_token = $auth_token;
    $api_host = getenv('XOXZO_API_HOST');

    if ($api_host == "") {
      $api_host = "https://api.xoxzo.com";
    }

    $this->xoxzo_api_sms_url = $api_host . "/sms/messages/";
    $this->xoxzo_api_voice_simple_url =  $api_host . "/voice/simple/playback/";
    echo "\n";
    echo $this->sid,"\n";
    echo $this->auth_token,"\n";
    echo $this->xoxzo_api_sms_url,"\n";
    echo $this->xoxzo_api_voice_simple_url,"\n";
  }

  public function send_sms($message, $recipient, $sender){
  }

  public function get_sms_delivery_status($msgid){
  }

  public function call_simple_playback($caller, $recipient, $recording_url){
  }

  public function get_simple_playback_status($callid){
  }
}

# test code
$xc = new XoxzoClient("", "");
?>
