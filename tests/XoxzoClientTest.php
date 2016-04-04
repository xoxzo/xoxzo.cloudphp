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

  public function test_send_sms_01() {
    # $this->xc->send_sms("Hello form Xoxzo PHP","+818054695209","814512345678");
  }

  public function test_get_sms_delivery_status_01() {
    # good msgid
    $resp = $this->xc->get_sms_delivery_status("W0kYZfyBeTpqcPv2AnKolSjOwDr3d87h");
    $this->assertObjectHasAttribute('msgid', $resp);
  }

  /**
   * @expectedException     GuzzleHttp\Exception\ClientException
   * @expectedExceptionCode 404
  */

  public function test_get_sms_delivery_status_02() {
    # bad msgid
    $resp =  $this->xc->get_sms_delivery_status("W0kYZfyBeTpqcPv2AnKolSjOwDr3d87i");
  }

  public function test_get_sms_delivery_status_03() {
    # retreave all msgids
    $resp =  $this->xc->get_sms_delivery_status("");
    $this->assertTrue(is_array($resp));
  }
}
?>
