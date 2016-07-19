# xoxzo.cloudphp

This is the PHP client library for Xoxzo Cloud API. You can send sms or make a phone call and payback mp3 files.

## Sample Code

### Send SMS

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
#### Explanation

+ First, you need to create `XoxzoClient()` object. You must provide xoxzo sid and auth_token when initializing this object. You can get sid and auth_token after you sign up the xoxzo account and access the xoxzo dashboard.

+ Then you can call `send_sms()` method. You need to provide three parameters.

  - message: sms text you want to send.
  - recipient: phone number of the sms recipient. This must start with Japanese country code "+81" and follow the E.164 format.
  - sender: this number will be displayed on the recipient device.

+ This method will return `XoxzoResponse` object. If `XoxzoResponse.errors == null`, `XoxzoResponse->messages[0]->msgid` is the meesage id that you can pass to the `get_sms_delivery_status() call.

+ You can check the sms delivery status by `get_sms_delivery_status()` method. You will provide message-id of the sms you want to check.

-----
### Playback MP3
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

#### Explanation

+ You can call `call_simple_playback()` method to playback MP3 files. You need to provide three parameters.

  - caller: this number will be displayed on the recipient device.
  - recording_url: MP3 file URL.
  - recipient: phone number of the sms recipient. This must start with Japanese country code "+81" and follow the E.164 format.

+ This method will return `XoxzoResponse` object. If `XoxzoResponse.errors == null`, `XoxzoResponse->messages[0]->callid` is the call id that you can pass to the `get_simple_playback_status() call.

+ You can check the call status by `get_simple_playback_status()` method. You will provide call-id of the phone call you want to check.


-----

### DIN (Dial in numbers)

### Subscribe DIN

```
$resp = $xc->get_din_list();
$a_din_uid = $resp->messages[0]->din_uid;
$resp = $xc->subscribe_din($a_din_uid);
```

#### Explanation

1. In order to subscribe DIN, you must find available unsubscribed DINs using get_din_list() method.

2. Then you subscribe a DIN via subscribe_din() method specifying din unique id.

### Set action URL

```
$sample_acrion_url = "http://example.com/dummy_url";
$resp = $xc->set_action_url($a_din_uid, $sample_acrion_url);
```

#### Explanation

1. Once you subscribed the DIN, you can set action url to the DIN. This URL will be called in the event of the DIN gets called.
The URL will called by http GET method with the parameters, caller and recipient.
