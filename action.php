<?php

/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

/**
 * Make things happen when buttons are pressed and forms submitted.
 */
require_once __DIR__ . "/required.php";

if ($VARS['action'] !== "signout") {
    dieifnotloggedin();
}

/**
 * Redirects back to the page ID in $_POST/$_GET['source'] with the given message ID.
 * The message will be displayed by the app.
 * @param string $msg message ID (see lang/messages.php)
 * @param string $arg If set, replaces "{arg}" in the message string when displayed to the user.
 */
function returnToSender($msg, $arg = "") {
    global $VARS;
    if ($arg == "") {
        header("Location: app.php?page=" . urlencode($VARS['source']) . "&msg=" . $msg);
    } else {
        header("Location: app.php?page=" . urlencode($VARS['source']) . "&msg=$msg&arg=$arg");
    }
    die();
}

switch ($VARS['action']) {
    case "signout":
        session_destroy();
        header('Location: index.php?logout=1');
        die("Logged out.");
    case "settempunits":
        $unit = "C";
        if (!empty($VARS['unit'])) {
            switch (strtoupper($VARS['unit'])) {
                case "F":
                    $unit = "F";
                    break;
                case "C":
                    $unit = "C";
                    break;
                case "K":
                    $unit = "K";
                    break;
            }
        }
        setcookie("TemperatureUnitsPref", $unit, time() + 60 * 60 * 24 * 90);
        returnToSender("");
        break;
    case "setspeedunits":
        $unit = "K";
        if (!empty($VARS['unit'])) {
            switch (strtoupper($VARS['unit'])) {
                case "KPH":
                    $unit = "KPH";
                    break;
                case "MPH":
                    $unit = "MPH";
                    break;
            }
        }
        setcookie("SpeedUnitsPref", $unit, time() + 60 * 60 * 24 * 90);
        returnToSender("");
        break;
}