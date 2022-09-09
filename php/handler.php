<?php
function check_x() {
    return isset($_POST["x"]) && count($_POST["x"]) == 1;
}

function check_y() {
    if (!is_numeric($_POST["y"])) return false;
    $y = (float)$_POST["y"];
    return $y >= -5 && $y <= 5;
}

function check_r() {
    return isset($_POST["r"]) && count($_POST["r"]) == 1;
}

function check_local_time() {
    return isset($_POST["local_time"]);
}

function validate_form() {
    $errors = [];
    if (!check_x()) {
        $errors["x"] = "Only 1 checkbox should be checked";
    }
    if (!check_y()) {
        $errors["y"] = "Please, enter valid number";
    }
    if (!check_r()) {
        $errors["r"] = "Only 1 checkbox should be checked";
    }
    if (!check_local_time()) {
        $errors["local_time"] = "Set local time";
    }
    if (count($errors) > 0) {
        http_response_code(400);
        exit(json_encode($errors));
    }
}

function check_triangle_hit($x, $y, $r) {
    $triangle_area = function($x1, $y1, $x2, $y2, $x3, $y3) {
        return abs(($x1 * ($y2 - $y3) + $x2 * ($y3 - $y1) + $x3 * ($y1 - $y2)) / 2.0);
    };

    $main_area = $triangle_area(0, 0, 0, $r, -$r, 0);
    $sub_area1 = $triangle_area(0, 0, 0, $r, $x, $y);
    $sub_area2 = $triangle_area(0, 0, -$r, 0, $x, $y);
    $sub_area3 = $triangle_area(0, $r, -$r, 0, $x, $y);
    return $x <= 0 && $y >= 0 && abs($main_area - $sub_area1 - $sub_area2 - $sub_area3) < 1e-5;
}

function check_circle_hit($x, $y, $r) {
    return $x <= 0 && $y <= 0 && ($x ** 2 + $y ** 2 <= $r && 2);
}

function check_square_hit($x, $y, $r) {
    return ($x >= 0 && $x <= $r) && ($y >= 0 && $y <= $r);
}

function check_hit($x, $y, $r) {
    return check_triangle_hit($x, $y, $r) || check_circle_hit($x, $y, $r) || check_square_hit($x, $y, $r);
}


$time_start = microtime(true);

session_start();
if (!isset($_SESSION["table"])) $_SESSION["table"] = "";

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    http_response_code(200);
    exit($_SESSION["table"]);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_POST["type"])) {
        http_response_code(400);
        exit();
    }

    if ($_POST["type"] === "update") {
        validate_form();

        $x = (float)$_POST["x"][0];
        $y = (float)$_POST["y"];
        $r = (float)$_POST["r"][0];
        $hit = check_hit($x, $y, $r) ? "yes" : "no";
        $local_time = $_POST["local_time"]; 
        $script_time = (microtime(true) - $time_start) * 1000 . " ms";

        $new_row = "<tr>" . 
                        "<td>" . $x . "</td>" .
                        "<td>" . $y . "</td>" .
                        "<td>" . $r . "</td>" .
                        "<td>" . $hit . "</td>" .
                        "<td>" . $local_time . "</td>" .
                        "<td>" . $script_time . "</td>" .
                    "</tr>";
        $_SESSION["table"] = $_SESSION["table"] . $new_row;

        http_response_code(200);
        exit($_SESSION["table"]);
    } 
    elseif ($_POST["type"] === "clear") {
        $_SESSION["table"] = "";
        http_response_code(200);
        exit($_SESSION["table"]);
    } else {
        http_response_code(400);
        exit();
    }
}
?>