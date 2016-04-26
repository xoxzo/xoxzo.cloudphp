# xoxzo.cloudphp
This is the PHP client library for Xoxzo Cloud API.
You can send sms or make a phone call and payback mp3 files.

## sample code

send sms
```
use xoxzo\cloudphp\XoxzoClient;

$sid = <Your Xoxzo SID>;
$auth_token = <Your Xoxzo AUTH TOKEN>
$recipient = '+818012345678';
$sender = '818012345678';
$message = 'Hello form Xoxzo PHP lib';

$xc = new XoxzoClient($sid,$auth_token);
$resp = $xc->send_sms($message, $recipient,$sender);
if ($resp->errors != null){
  print "Error status: $resp->errors\n";
  return;
}

$msgid = $resp->messages[0]->msgid;
$resp = $xc->get_sms_delivery_status($msgid);
var_dump($resp);

```

## playback mp3
```
use xoxzo\cloudphp\XoxzoClient;

$$sid = <Your Xoxzo SID>;
$auth_token = <Your Xoxzo AUTH TOKEN>
$recipient = '+818012345678';
$recording_url = 'http://exmaple.com/exmaple.mp3';
$caller = '8108012345678';

$xc = new XoxzoClient($sid,$auth_token);

$resp = $xc->call_simple_playback($caller, $recipient, $recording_url);

if ($resp->errors != null){
  print "Error status: $resp->errors\n";
  return;
}

$callid = $resp->messages[0]->callid;
$resp = $xc->get_simple_playback_status($callid);
var_dump($resp);
```
