<?php

class XoxzoClient {
  private $sid;
  private $auth_token;

  function __construct($sid, $auth_token){
    $this->sid = $sid;
    if ($this->sid == '') {
      $this->sid = getenv('XOXZO_API_SID');
    }
    $this->auth_token = $auth_token;
    if ($this->auth_token == '') {
      $this->auth_token = getenv('XOXZO_API_AUTH_TOKEN');
    }
    echo $this->sid,"\n";
    echo $this->auth_token,"\n";
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
