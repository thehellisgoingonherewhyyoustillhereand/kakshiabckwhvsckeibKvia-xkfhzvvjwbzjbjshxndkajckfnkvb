<?php
header("Content-Type: application/json");
if (file_exists(__DIR__ . "/stats.json")) {
    $data = file_get_contents(__DIR__ . "/stats.json");
    die($data);
} else {
    die(json_encode(["error" => "Could not fetch leaderboard!"]));
};
?>