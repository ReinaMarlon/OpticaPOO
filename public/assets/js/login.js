document.getElementById("loginForm").addEventListener("submit", function (e) {
    e.preventDefault();
    
    let username = document.getElementById("username").value.trim();
    let password = document.getElementById("password").value.trim();
    let errorMsg = document.getElementById("error-msg");

    if (username === "" || password === "") {
        errorMsg.textContent = "Todos los campos son obligatorios";
        errorMsg.style.display = "block";
        return;
    }

    fetch("../src/controllers/sessionValidate.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `username=${username}&password=${password}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = data.redirect;
        } else {
            errorMsg.textContent = data.message;
            errorMsg.style.display = "block";
        }
    });
});
