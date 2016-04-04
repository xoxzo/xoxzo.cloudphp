<?php

namespace xoxzo\cloudphp\tests;

require_once 'src/XoxzoClient.php';
use xoxzo\cloudphp\XoxzoClient;

class XoxzoClientTest extends \PHPUnit_Framework_TestCase {

  public function test01() {
    $sid = getenv('XOXZO_API_SID');
    $auth_token = getenv('XOXZO_API_AUTH_TOKEN');
    $xc = new XoxzoClient($sid,$auth_token);
  }
}
?>
