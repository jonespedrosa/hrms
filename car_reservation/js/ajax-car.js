$(document).ready(function (){
    $("#login-form").submit(function(event) {
        event.preventDefault();
        let empno = document.getElementById("employee-id");
        let password = document.getElementById("password-id");

        // loading
        let spinner = $("#login-id").find("span")[0];
        let spinner_text = $("#login-id").find("span")[1];

        // spinner toggle
        $(spinner).removeClass("d-none");
        $("#login-id").attr("disabled", "");
        $(spinner_text).html("Logging in");

        $.ajax({
            type: 'GET',
            url: "function/car_functions.php?action=login",
            data: {
                empno: empno.value,
                password: password.value
            },
            
            success: function(response) {
                // spinner toggle
                $(spinner).addClass("d-none");
                $("#login-id").removeAttr("disabled", "");
                $(spinner_text).html("Login");

                let jsonResponse = JSON.parse(response);  
                if(jsonResponse.status == "error"){
                    console.log($("#error-message"));
                    $("#error-message").removeClass("d-none");
                    $("#employee-id").addClass("border-danger");
                    $("#password-id").addClass("border-danger");
                    $("#error-message").html(jsonResponse.title);
                }else{
                    $("#error-message").toggleClass("d-none");
                    $("#employee-id").removeClass("border-danger");
                    $("#password-id").removeClass("border-danger");
                    $("#employee-id").addClass("border-success");
                    $("#password-id").addClass("border-success");

                    window.location.href = "dashboard.php";
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR, textStatus, errorThrown);
            }
        });
    });
});

function LimitToFour(){ 
    let empIdLength = document.getElementById('employee-id').value.length;
    if(empIdLength > 4){
        document.getElementById('employee-id').value =  document.getElementById('employee-id').value.substr(0, 4);
    }
}