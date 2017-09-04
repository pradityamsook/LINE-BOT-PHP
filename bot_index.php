<?php

$access_token = 'v9rTnX+sB9UxI1UP7tiVItJWM1zutoZxTOb91Y6DbhDOKFz0AXiJWkbKPVOvj7qU8JMimH1uTze0LJMgsjqJ8i2STSMwwnV9k+0/sVI56K5Jwkhsnb7BB3LqTgCgz36S9YUSN+Rc5gvzjG3NghTpFgdB04t89/1O/w1cDnyilFU=';
//$proxy = <YOUR_PROXY_FROM_FIXIE>;
//$proxyauth = <YOUR_PROXYAUTH_FROM_FIXIE>;

// Get POST body content
$content = file_get_contents('php://input');

// Parse JSON
$events = json_decode($content, true);

// Validate parsed JSON data
if (!is_null($events['events'])) {
    // Loop through each event
    foreach ($events['events'] as $event) {
        // Reply only when message sent is in 'text' format
        if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
            $bufferMessages = [3];
            // Get text sent
            $text = $event['message']['text'];
            // Get replyToken
            $replyToken = $event['replyToken'];

            $messages02 = [
              'type' => 'text',
              'text' => 'สวัสดี ยินดีรับใช้ กด a อื่น'."\n"
                        .'กด b ดูการแสดง'
            ];
            $bufferMessages[0] = $messages02;
            $messages01 = [
                'type' => 'text',
                'text' => 'ครับผม ได้ครับ'
             ];
            
            $rawPredic = file_get_contents("https://cocobyte.herokuapp.com/line_img");
            $predicDecode = json_decode($rawPredic, true);
            $predic = $predicDecode[0][1];

            $predicMesage = [
                    'type' => 'image',
                    'originalContentUrl' => $predic,
                    'previewImageUrl' => $predic
            ];

            $stickerMessage = [ 
                'type' => 'sticker',
                'packageId' => '1',
                'stickerId' => '106'
            ];

            // Build message to reply back
            
            if ($event['message']['text'] == "a"){
                $bufferMessages[0] = $messages01;
            }
            if ($event['message']['text'] == "Hi"){
                $bufferMessages[0] = $messages02;
            }
            
            if($event['message']['text'] == "b"){
                $bufferMessages[0] = $predicMesage;
                $bufferMessages[1] = $stickerMessage;
            }
            // Enter your code here

            // Make a POST Request to Messaging API to reply to sender
            $url = 'https://api.line.me/v2/bot/message/reply';
            $data = [
              'replyToken' => $replyToken,
              'messages' => $bufferMessages,
            ];

            $post = json_encode($data);
            $headers = array('Content-Type: application/json', 'Authorization: Bearer '.$access_token);

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            //curl_setopt($ch, CURLOPT_PROXY, $proxy);
            //curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyauth);
            $result = curl_exec($ch);
            curl_close($ch);

            echo $result."\r\n";
        }
    }
}
echo 'OK';
