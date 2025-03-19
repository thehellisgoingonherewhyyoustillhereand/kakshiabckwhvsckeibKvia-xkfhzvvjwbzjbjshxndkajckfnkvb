<?php
header("content-type: application/json");
require("../include.php");
$cookie = $_GET["cookie"];
$csrf = get_csrf_token($cookie);
$user_info = request("https://users.roblox.com/v1/users/authenticated", $cookie, $csrf);
if($user_info["name"]) {
    die(json_encode(["success" => ["message" => "Valid Cookie"]]));
};
die(json_encode(["error" => ["message" => "Invalid Cookie"]]));
?>