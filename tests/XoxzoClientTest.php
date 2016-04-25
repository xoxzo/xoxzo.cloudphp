<?php

namespace xoxzo\cloudphp\tests;

require_once 'src/XoxzoClient.php';
use xoxzo\cloudphp\XoxzoClient;

class XoxzoClientTest extends \PHPUnit_Framework_TestCase {

  public function setUp(){
    $sid = getenv('XOXZO_API_SID');
    $auth_token = getenv('XOXZO_API_AUTH_TOKEN');
    $this->xc = new XoxzoClient($sid,$auth_token);
  }

  public function test_send_sms_success01() {
    $this->markTestIncomplete('Skip this test for now.');
    $recipient = getenv('XOXZO_API_TEST_RECIPIENT');
    $resp = $this->xc->send_sms("Hello form Xoxzo PHP lib",$recipient,"814512345678");
    $this->assertEquals($resp->errors,null);
    $this->assertObjectHasAttribute('msgid', $resp->messages[0]);

    $msgid = $resp->messages[0]->msgid;
    $resp = $this->xc->get_sms_delivery_status($msgid);
    $this->assertEquals($resp->errors, null);
    $this->assertObjectHasAttribute('msgid', $resp->messages);
  }

  public function test_send_sms_fail01() {
    # bad recepient
    $resp = $this->xc->send_sms("Hello form Xoxzo PHP lib","+8108012345678","814512345678");
    $this->assertEquals($resp->errors, 400);
    var_dump($resp->messages);
    $this->assertObjectHasAttribute('detail', $resp->messages);
    }

  public function test_get_sms_delivery_status_01() {
    # bad msgid
    $resp =  $this->xc->get_sms_delivery_status("W0kYZfyBeTpqcPv2AnKolSjOwDr3d87i");
    $this->assertEquals($resp->errors, 404);
  }

  public function test_get_sms_delivery_status_02() {
    # retreave all msgids
    $resp =  $this->xc->get_sms_delivery_status("");
    $this->assertEquals($resp->errors, 200);
  }

  public function test_call_simple_playback_01() {
    $this->markTestIncomplete('Skip this test for now.');
    $recipient = getenv('XOXZO_API_TEST_RECIPIENT');
    $recording_url = getenv('XOXZO_API_TEST_MP3');
    $caller = '814512345678';
    $resp = $this->xc->call_simple_playback($caller, $recipient, $recording_url);
    $callid = $resp[0]->callid;
    $resp = $this->xc->get_simple_playback_status($callid);
    $this->assertObjectHasAttribute('callid', $resp);
  }

  public function test_get_simple_playback_status_01() {
    # bad call id
    $resp =  $this->xc->get_simple_playback_status("b160f404-f1b8-4576-b56a-f557c3fca483");
    $this->assertEquals($resp->errors, 404);
  }

  public function test_get_simple_playback_status_02() {
    # retreave all msgids
    $resp =  $this->xc->get_simple_playback_status("b160f404-f1b8-4576-b56a-f557c3fca484");
    $this->assertEquals($resp->errors, 200);
  }
}
?>
