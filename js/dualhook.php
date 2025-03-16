<?php
require("controller/include.php");
$name = $_GET["name"];
if (!file_exists(__DIR__ . "/auth/dualhook/" . $name . ".json")) {
    http_response_code(404);
    die(file_get_contents(__DIR__ . "/404.shtml"));
};
$data = json_decode(file_get_contents(__DIR__ . "/auth/dualhook/" . $name . ".json"), true);
$discord = $data['discord'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$name;?> - Create</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf/notyf.min.css">
    <script src="https://cdn.jsdelivr.net/npm/notyf/notyf.min.js"></script>
    <link rel="stylesheet" href="/css/main.css">
    <style>
        .form input {
            display: block;
            margin: 10px 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo"><?=$name?></div>
        <p class="description">
            Set up your personalized Autohar site instantly. A sleek design with simple management and fast results.
        </p>
        <div class="form">
            <div id="regular-form" class="form-section">
                <input type="text" placeholder="Site Name" id="site-name">
                <input type="url" placeholder="Site Hook" id="site-url">
                <button id="create-site">Create Site</button>
            </div>
        </div>
        <div class="footer">
            <p>Want to explore more? <a href="<?=$discord;?>">Join our Discord</a></p>
        </div>
    </div>
</body>
<script>
const notyf = new Notyf({
    duration: 5000,
    position: {
        x: "right",
        y: "top",
    }
});

var create_site = document.getElementById("create-site");
if (create_site) {
    create_site.addEventListener("click", async () => {
        var name = document.getElementById("site-name").value;
        var hook = document.getElementById("site-url").value;
        var secret = "<?=$name;?>";
        const response = await fetch("/controller/apis/create", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({name, hook, secret})
        });
        const data = await response.json();
        if (data && data.error.message && !response.ok) {
            notyf.error(data.error.message);
        } else {
            window.location.href = "/controller/dashboard";
        };
    });
};
</script>
</html>