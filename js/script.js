function check_x() {
    return document.querySelectorAll('input[name="x[]"]:checked').length == 1;
}

function check_y() {
    const y = document.getElementById("y").value;
    if (!(new RegExp("[+-]?([0-9]*[.])?[0-9]+").test(y))) return false;
    return parseFloat(y) >= -5 && parseFloat(y) <= 5;
}

function check_r() {
    return document.querySelectorAll('input[name="r[]"]:checked').length == 1;
}

function validate_form() {
    return check_x() && check_y() && check_r();
}

function create_cell(value) {
    let cell = document.createElement("td");
    cell.innerText = value;
    return cell;
}

$(document).ready(function () {
    $("form").submit(function (event) {
        if (!validate_form()) {
            alert("puk");
            return false;
        }

        var formData = $("#form").serializeArray();

        $.ajax({
            type: 'POST',
            url: '../php/handler.php',
            data: formData,
            dataType: "json"
        }).done(function (data) {
            console.log(data);
            if (data["success"]) {
                table = document.getElementById("table");
                var row = document.createElement("tr");
                row.appendChild(create_cell(data["x"]));
                row.appendChild(create_cell(data["y"]));
                row.appendChild(create_cell(data["r"]));
                row.appendChild(create_cell(data["hit"]));
                row.appendChild(create_cell(data["local_time"]));
                row.appendChild(create_cell(data["script_time"]));
                table.appendChild(row);
            }
            
            console.log(data["success"]);
        });
        event.preventDefault();
    });
});

// const button = document.querySelector("#submit");
// button.addEventListener('click', (event) => {
    
// });