$(document).ready(function () {
    $("#signUp").submit(function (e) {
        e.preventDefault();

        let form = $(this);
        let actionUrl = form.attr("action");

        $.ajax({
            url: actionUrl,
            type: "POST",
            data: form.serialize(),
            dataType: "json",
            beforeSend: function () {
                form.find("button[type=submit]").prop("disabled", true).text("Please wait...");
            },
            success: function (response) {
                if (response.status) {
                    window.location.href = response.route;
                } else {
                    showLoginError(response.message);
                }
            },
            error: function () {
                alert("Something went wrong!");
            },
            complete: function () {
                form.find("button[type=submit]").prop("disabled", false).text("Sign In");
            }
        });
    });

     function showLoginError(message) {
       
        if ($("#login-error").length) {
            $("#login-error").text(message);
        } else {
            $("#signUp").prepend(
                `<div id="login-error" class="alert fs-16 alert-danger bg-danger text-white" role="alert">${message}</div>`
            );
        }
    }
});
