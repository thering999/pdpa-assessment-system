<?php
// Webhook handler: POST event ไป endpoint ภายนอก
function send_webhook($event, $data) {
    $url = getenv('WEBHOOK_URL');
    if (!$url) return;
    $payload = json_encode(['event'=>$event,'data'=>$data]);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($payload)
    ]);
    curl_exec($ch);
    curl_close($ch);
}
// ตัวอย่างการใช้งาน: send_webhook('assessment_submitted', ['user_id'=>1,'score'=>80]);
