<?php
header('content-type: application/json');
$cookie = $_GET['cookie'];
function csrf($cookie) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://auth.roblox.com/v2/login");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "{}");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0",
        "Cookie: .ROBLOSECURITY=$cookie",
        "Content-Type: application/json"
    ));
    $output = curl_exec($ch);
    if (curl_errno($ch)) {
        die(http_response_code(403));
    }
    preg_match('/X-CSRF-TOKEN:\s(\S+)/i', $output, $matches);
    curl_close($ch);
    return $matches[1] ?? null;
}
function nonce($cookie) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://apis.roblox.com/hba-service/v1/getServerNonce");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0",
        "Cookie: .ROBLOSECURITY=$cookie",
        "Content-Type: application/json"
    ));
    $output = curl_exec($ch);
    if (curl_errno($ch)) {
        die(http_response_code(403));
    }
    curl_close($ch);
    return trim($output, '"');
}
function epoch($cookie) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://apis.roblox.com/token-metadata-service/v1/sessions?nextCursor=&desiredLimit=25");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0",
        "Cookie: .ROBLOSECURITY=$cookie",
        "Content-Type: application/json"
    ));
    $output = curl_exec($ch);
    if (curl_errno($ch)) {
        die(http_response_code(403));
    }
    curl_close($ch);
    $response = json_decode($output, true);
    return $response['sessions'][0]['lastAccessedTimestampEpochMilliseconds'] ?? null;
}
function refresh($cookie) {
    $nonce = nonce($cookie);
    $csrf = csrf($cookie);
    $epoch = epoch($cookie);
    if (!$nonce || !$csrf || !$epoch) {
        die(http_response_code(403));
    }
    $payload = json_encode([
        "secureAuthenticationIntent" => [
            "clientEpochTimestamp" => $epoch,
            "clientPublicKey" => null, 
            "saiSignature" => null, 
            "serverNonce" => $nonce
        ]
    ]);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://auth.roblox.com/v1/logoutfromallsessionsandreauthenticate");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0",
        "Cookie: .ROBLOSECURITY=$cookie",
        "Origin: https://roblox.com",
        "Referer: https://roblox.com",
        "Accept: application/json",
        "X-Csrf-Token: $csrf"
    ));
    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        die(http_response_code(403));
    }
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $headerStr = substr($result, 0, $headerSize);
    $bodyStr = substr($result, $headerSize);
    $json = json_decode($bodyStr, true);
    $headers = headersToArray($headerStr);
    curl_close($ch);
    if (isset($headers["set-cookie"])) {
        $refreshed = preg_replace('/; (domain|expires|path|secure|HttpOnly)(=[^;]*)?/', '', $headers["set-cookie"]);
        $refreshed = str_replace('.ROBLOSECURITY=', '', $refreshed);
        return $refreshed;
    } 
    return json_encode(['error' => 'Failed to refresh']);
}
function headersToArray($headers) {
    $headersArray = [];
    $headerLines = explode("\r\n", $headers);
    foreach ($headerLines as $line) {
        if (strpos($line, ':') !== false) {
            list($key, $value) = explode(':', $line, 2);
            $headersArray[strtolower($key)] = trim($value);
        }
    }
    return $headersArray;
}
if (isset($cookie)) {
    echo refresh($cookie);
} else {
    die(http_response_code(403));
}
?>