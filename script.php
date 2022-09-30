<?php

error_reporting(0);
@session_start();
date_default_timezone_set("Europe/Moscow");
if (!isset($_SESSION["tableRows"])) {
    $_SESSION["tableRows"] = array();
}
$x=0;
$start = microtime(true);

function checkData($x, $y, $r): bool
{
    return in_array($x, array(-3, -2, -1, 0, 1, 2, 3, 4, 5)) &&
        is_numeric($y) && ($y > -3 && $y < 3) &&
        in_array($r, array(1, 1.5, 2, 2.5, 3));
}

function atRectangle($x, $y, $r): bool
{
    return (($x >= -$r / 2) && ($x <= 0) && ($y >= -$r) && ($y <= 0));
}

function atTriangle($x, $y, $r): bool
{
    return (($y <= -$x + $r / 2) && ($x >= 0) && ($x <= $r / 2) && ($y >= 0) && ($y <= $r / 2));
}

function atQuarterCircle($x, $y, $r): bool
{
    return (($x <= 0) && ($y >= 0) && (($x * $x + $y * $y) <= $r * $r));
}

function checkCoordinates($x, $y, $r): string
{
    if (atRectangle($x, $y, $r) || atTriangle($x, $y, $r) || atQuarterCircle($x, $y, $r)) {
        return "<span 
    style='border-radius: 0.2rem;
    background-color: red;
    padding: 0.2rem 1rem;
    text-align: center;
    background-color: #c8e6c9;
    color: #388e3c;'>YES</span>";
    } else return "<span 
    style='border-radius: 0.2rem;
    background-color: red;
    padding: 0.2rem 1rem;
    text-align: center;
    background-color: #ffcdd2;
    color: #c62828;'>NO</span>";
}


$x = (float)$_GET["x"];
$y = (float)$_GET["y"];
$r = (float)$_GET["r"];

if (checkData($x, $y, $r)) {
    $status = checkCoordinates($x, $y, $r);
    $currentTime = date("H : i : s");
    $benchmarkTime = round((microtime(true) - $start) * 1000, 3);

    array_unshift($_SESSION["tableRows"], "<tr>
    <td>$x</td>
    <td>$y</td>
    <td>$r</td>
    <td>$status</td>
    <td>$currentTime</td>
    <td>$benchmarkTime</td>
    </tr>");
    echo "<table id='outputTable'>
    <thead>
    <th>X</th>
    <th>Y</th>
    <th>R</th>
    <th>RAV</th>
    <th>Current time</th>
    <th>Execution time</th>
    </thead>";
    $counter = 0;
    foreach ($_SESSION["tableRows"] as $tableRow) {
        $counter++;
        if ($counter <= 10)
        echo $tableRow;
    }
    echo "</table>";
} else {
    http_response_code(400);
    return;
}