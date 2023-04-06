"use strict";
var $ele = (id) => document.getElementById(id);
var $qs = (classSelector) => document.querySelector(classSelector);
var $qsa = (classSelector) => document.querySelectorAll(classSelector);
var reg_email_err = false;
/**
 * Function to sanitizeHTML data before displaying it to the user
 * @param {string} text - HTML/any response string to sanitize before displaying
 */
function sanitizeHTML(text) {
    return $('<div>').text(text).html();
}
 

/**
 * @param {String} togId - the element id of the eye icon on which the user clicks
 * @param {String} passId - html element id of the password to be displayed as text / as password
 * Function to toggle the password visibility by clicking on icon
 */
function togglePasswordVisibility(togId, passId) {
    const type = $ele(passId).getAttribute("type") === "password" ? "text" : "password";
    $ele(passId).setAttribute("type", type);
    $ele(togId).classList.toggle("fa-eye");
}

// function to handle on loading of index.php page
function onLoadingIndexPage() {
    $(".nav_but").click(function() {
        $("#cover-spin").show().delay(500).fadeOut(); 
    })
    loginFormHandler();
}

//Function to handle the toggle of Login form and Registration form and validatiion
function loginFormHandler() {
    // getting all the buttons used in login/register form and containers to toggle
    let switchBtn = $qsa(".switch-btn");

    let changeForm = (e) => {
        $("#switch-cnt").addClass("is-gx");
        $("#cover-spin").show().delay(500).fadeOut();
        setTimeout(function () {
            $("#switch-cnt").removeClass("is-gx");
        }, 1500)

        $("#switch-cnt").toggleClass("is-txr");
        $("#switch__circle").toggleClass("is-txr");

        $("#switch-c1, #switch-c2").toggleClass("is-hidden");
        $("#a-container, #b-container").toggleClass("is-txl");
        $("#b-container").toggleClass("is-z200");
    }
    // toggle event listeners to sqi
    let toggleForm = (e) => {
        for (var i = 0; i < switchBtn.length; i++)
            switchBtn[i].addEventListener("click", changeForm)
    }
    toggleForm();
    validateRegistrationForm();
    checkUserMailAvailability();
    validateLoginForm();

}

//Function to check asynchronously email availability status from MySQL Database before registration
function checkUserMailAvailability() {
    $(document).ready(function () {
        $("#mail").focusout(function (e) {
            if ($("#mail").val()) {
                const url = '//' + location.host + '/controller/check_user.php?email=' + $("#mail").val();
                $.getJSON(url, function (data) {
                    $("#cover-spin").show().delay(300).fadeOut();
                    if (data.status === 0) {
                       
                        $("#user-availability-status").html(
                            "<span style='color:red; font-weight:bold'>" + sanitizeHTML(data.message) + "</span>"
                        );
                        $("#mail").addClass("error");
                        reg_email_err = true;
                    }
                    else if (data.status === 1) {
                        $("#user-availability-status").html(
                            "<span class='w3-text-green w3-center' style='font-weight:bold'>" + sanitizeHTML(data.message) + "</span>"
                        );
                        $("#mail").removeClass("error");
                        reg_email_err = false;
                    }
                });
            }
            else {
                $("#user-availability-status").html('');
            }
        });
    });

}

//Function to validate registration form on submiting/onchange using jQuery Validation library methods.
function validateRegistrationForm() {
    $(document).ready(function () {
        $("#registration-form").validate({
            rules:
            {
                fullname: {
                    required: true,
                    minlength: 3
                },
                mail: {
                    required: true,
                    email: true,
                    pattern: /^\w+([\.-]?\w+)*@\w+(\.\w{1,})+$/,
                },
                pass: {
                    required: true,
                    minlength: 8,
                    maxlength: 15,
                    pattern: /^^(?=.*[0-9])(?=.*[#?!@$%^&*_])(?=.*[a-zA-Z])[a-zA-Z0-9#?!@$%^&*_]{8,15}$/
                },
                retype_pass: {
                    required: true,
                    equalTo: '#pass'
                },
            },
            messages:
            {
                fullname: {
                    required: "Full name is required.",
                },
                mail: {
                    required: "Email is required.",
                    email: "Enter a valid email address.",
                    pattern: "Enter a valid email address.",
                },
                pass: {
                    required: "Password is required",
                    pattern: "Must contain atleast 1 aphabet, 1 digit & 1 spl. character"
                },
                retype_pass: {
                    required: "Please retype your password...",
                    equalTo: "Password doesn't match!"
                }
            },
            submitHandler: function (form) {
                if (!reg_email_err) {
                    $("#cover-spin").show();
                    form.submit();
                }
                else {
                    $("#mail").addClass("error");
                    $("#cover-spin").show().delay(300),fadeOut();
                }
            }
        });
    });
}

//Function to validate login form on submiting/onchange using jQuery Validation library methods.
function validateLoginForm() {
    $(document).ready(function () {
        $("#login-form").validate({
            rules:
            {
                email: {
                    required: true,
                },
                passwd: {
                    required: true,
                },
            },
            messages:
            {
                email: {
                    required: "Email is required.",
                },
                passwd: {
                    required: "Password is required",
                },
            },
            submitHandler: function (form) {
                $("#cover-spin").show();
                form.submit();
            }
        });
    });
}
