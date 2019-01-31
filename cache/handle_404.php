<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

require_once __DIR__ . "/../required.php";

$urlparts = explode("/", $_GET['file']);
$fileparts = explode(".", end($urlparts));

if (count($fileparts) != 3 || !preg_match("/[0-9]+/", $fileparts[1]) || $fileparts[2] != "jpg") {
    http_response_code(403);
    die("Invalid filename.");
}

if (!preg_match("/^[A-Za-z0-9\-!_]+$/", $fileparts[0])) {
    http_response_code(403);
    die("Encoded image URL invalid, refusing to parse.");
}

$url = Base64::decode($fileparts[0]);

if (!filter_var($url, FILTER_VALIDATE_URL)) {
    http_response_code(403);
    die("Invalid URL.");
}

//header("Content-Type: image/jpeg");
echo Thumbnail::addThumbnailToCache($url, (int) $fileparts[1]);