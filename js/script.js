// function check_x() {
//     return document.querySelectorAll('input[name="x[]"]:checked').length == 1;
// }

// function check_y() {
//     const y = document.getElementById("y").value;
//     if (!(new RegExp("[+-]?([0-9]*[.])?[0-9]+").test(y))) return false;
//     return parseFloat(y) >= -5 && parseFloat(y) <= 5;
// }

// function check_r() {
//     return document.querySelectorAll('input[name="r[]"]:checked').length == 1;
// }

// function validate_form() {
//     return check_x() && check_y() && check_r();
// }

$(document).ready(function () {
    $.ajax({
        type: 'GET',
        url: 'php/handler.php',
        success: (data) => $("#result_table tbody").html(data)
    });

    $("#form #submit").click(function (event) {
        if (!validate_form()) {
            return false;
        }

        var formData = $("#form").serializeArray();
        formData.push({"name" : "type", "value" : "update"});
        formData.push({"name": "local_time", "value" : new Date().toLocaleString()});
        // console.log(formData);

        $.ajax({
            type: 'POST',
            url: 'php/handler.php',
            data: formData,
            success: (data) => $("#result_table tbody").html(data)
        });
        event.preventDefault();
    });

    $("#form #clear").click(function (event) {
        var formData = new Array();
        formData.push({"name" : "type", "value" : "clear"});
        console.log(formData);

        $.ajax({
            type: 'POST',
            url: 'php/handler.php',
            data: formData,
            success: (data) => $("#result_table tbody").html(data)
        });
        event.preventDefault();
    });
});
