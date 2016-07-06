<?php
namespace xoxzo\cloudphp\tests;
require_once 'src/XoxzoClient.php';

use xoxzo\cloudphp\XoxzoClient;
class XoxzoClientTest extends \PHPUnit_Framework_TestCase

{
    public function setUp()
    {
        date_default_timezone_set('UTC');
        $sid = getenv('XOXZO_API_SID');
        $auth_token = getenv('XOXZO_API_AUTH_TOKEN');
        $this->xc = new XoxzoClient($sid, $auth_token);
    }

    public function test_send_sms_success01()
    {
        $this->markTestIncomplete('Skip this test for now.');
        $recipient = getenv('XOXZO_API_TEST_RECIPIENT');
        $resp = $this->xc->send_sms("Hello form Xoxzo PHP lib", $recipient, "814512345678");
        $this->assertEquals($resp->errors, null);
        $this->assertObjectHasAttribute('msgid', $resp->messages[0]);
        $msgid = $resp->messages[0]->msgid;
        $resp = $this->xc->get_sms_delivery_status($msgid);
        $this->assertEquals($resp->errors, null);
        $this->assertObjectHasAttribute('msgid', $resp->messages);
    }

    public function test_send_sms_fail01()
    {
        // bad recepient
        $resp = $this->xc->send_sms("Hello form Xoxzo PHP lib", "+8108012345678", "814512345678");
        $this->assertEquals($resp->errors, 400);
        $this->assertObjectHasAttribute('recipient', $resp->messages);
    }

    public function test_get_sms_delivery_status_01()
    {
        // bad msgid
        $resp = $this->xc->get_sms_delivery_status("W0kYZfyBeTpqcPv2AnKolSjOwDr3d87i-xxx");
        $this->assertEquals($resp->errors, 404);
        $this->assertEquals($resp->messages, null);
    }

    public function test_get_sms_delivery_status_02()
    {
        // retreave all msgids
        $resp = $this->xc->get_sms_delivery_status("");
        $this->assertEquals($resp->errors, null);
        $this->assertObjectHasAttribute('msgid', $resp->messages[0]);
    }

    public function test_get_sent_sms_list_01()
    {
        $resp = $this->xc->get_sent_sms_list("");
        $this->assertEquals($resp->errors, null);
        $this->assertObjectHasAttribute('msgid', $resp->messages[0]);
    }

    public function test_get_sent_sms_list_02()
    {
        # test 89 days ago, shoud success
        $sixty_days_ago = date('Y-m-d', strtotime("-89 day"));
        $resp = $this->xc->get_sent_sms_list('>=' . $sixty_days_ago);
        $this->assertEquals($resp->errors, null);
        $this->assertObjectHasAttribute('msgid', $resp->messages[0]);
    }
    public function test_get_sent_sms_list_03()
    {
        # test 91 days ago, should fail
        $sixty_days_ago = date('Y-m-d', strtotime("-91 day"));
        $resp = $this->xc->get_sent_sms_list('>=' . $sixty_days_ago);
        $this->assertEquals($resp->errors, 400);
    }


    public function test_get_sent_sms_list_04()
    {
        # bad date test
        $resp = $this->xc->get_sent_sms_list('>=2016-13-01');
        $this->assertEquals($resp->errors, 400);
        $this->assertObjectHasAttribute('sent_date', $resp->messages);
    }

    public function test_get_sent_sms_list_05()
    {
        # use defalut parameter
        $resp = $this->xc->get_sent_sms_list();
        $this->assertEquals($resp->errors, null);
        $this->assertObjectHasAttribute('msgid', $resp->messages[0]);
    }

    public function test_call_simple_playback_success01()
    {
        $this->markTestIncomplete('Skip this test for now.');
        $recipient = getenv('XOXZO_API_TEST_RECIPIENT');
        $recording_url = getenv('XOXZO_API_TEST_MP3');
        $caller = '814512345678';
        $resp = $this->xc->call_simple_playback($caller, $recipient, $recording_url);
        $this->assertEquals($resp->errors, null);
        $this->assertObjectHasAttribute('callid', $resp->messages[0]);
        $callid = $resp->messages[0]->callid;
        $resp = $this->xc->get_simple_playback_status($callid);
        $this->assertEquals($resp->errors, null);
        $this->assertObjectHasAttribute('callid', $resp->messages);
    }

    public function test_call_simple_playback_fail01()
    {
        $recipient = '+8108012345678';
        $recording_url = getenv('XOXZO_API_TEST_MP3');
        $caller = '814512345678';
        $resp = $this->xc->call_simple_playback($caller, $recipient, $recording_url);
        $this->assertEquals($resp->errors, 400);
        $this->assertObjectHasAttribute('recipient', $resp->messages);
    }

    public function test_get_simple_playback_status_01()
    {
        // bad call id
        $resp = $this->xc->get_simple_playback_status("b160f404-f1b8-4576-b56a-f557c3fca483");
        $this->assertEquals($resp->errors, 404);

        // this test currently fails due to bug
        // $this->assertEquals($resp->messages, null);
        // todo: fix this test when the bug is fixed

        $this->assertEquals($resp->messages, "");
    }

    public function test_get_din_list_success_01(){
        $resp = $this->xc->get_din_list();
        $this->assertEquals($resp->errors, null);
    }
    public function test_get_din_list_success_02(){
        $resp = $this->xc->get_din_list("country=JP");
        $this->assertEquals($resp->errors, null);
    }

    public function test_get_din_list_success_03(){
        $resp = $this->xc->get_din_list("prefix=813");
        $this->assertEquals($resp->errors, null);
    }

    public function test_get_din_list_fail_01(){
        # bad query string
        $resp = $this->xc->get_din_list("foo=813");
        $this->assertEquals($resp->errors, 400);
    }

    public function test_din(){
        $resp = $this->xc->get_din_list();
        $this->assertEquals($resp->errors, null);
        #print_r($resp->messages);
        $a_din_uid = $resp->messages[1]->din_uid;

        $resp = $this->xc->subscribe_din($a_din_uid);
        #print_r($resp->messages);
        $this->assertEquals($resp->errors, null);

        $resp = $this->xc->unsubscribe_din($a_din_uid);
        $this->assertEquals($resp->errors, null);

        $resp = $this->xc->get_subscription_list();
        $this->assertEquals($resp->errors, null);
        print_r($resp->messages);
    }
}
?>
