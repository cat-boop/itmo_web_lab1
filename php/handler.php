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

function validate_form() {
    return check_x() && check_y() && check_r();
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

$data = [];
$errors = [];

if (!validate_form()) {
    if (!check_x()) {
        $errors["x"] = "Only 1 checkbox should be checked";
    }
    if (!check_y()) {
        $errors["y"] = "Please, enter valid number";
    }
    if (!check_r()) {
        $errors["r"] = "Only 1 checkbox should be checked";
    }
    $data["success"] = false;
    $data["errors"] = $errors;
} else {
    $data["success"] = true;
    $x = (float)$_POST["x"][0];
    $y = (float)$_POST["y"];
    $r = (float)$_POST["r"][0];

    $data["x"] = $x;
    $data["y"] = $y;
    $data["r"] = $r;
    $data["hit"] = check_hit($x, $y, $r) ? "true" : "false";
    $data["local_time"] = idate("H") . ":" . idate("i") . ":" . idate("s") . " " . idate("d") . "." . idate("m") . "." . idate("Y");    
    $data["script_time"] = (microtime(true) - $time_start) * 1000 . " ms";
}

echo json_encode($data);
?>