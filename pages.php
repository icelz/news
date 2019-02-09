<?php

/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

// List of pages and metadata
define("PAGES", [
    "home" => [
        "title" => "Overview",
        "navbar" => true,
        "icon" => "fas fa-home",
        "styles" => [
            "static/weather-icons/css/weather-icons.min.css"
        ],
        "scripts" => [
            "static/Shuffle/dist/shuffle.min.js",
            "static/js/newsgrid.js",
            "static/js/home.js"
        ]
    ],
    "news" => [
        "title" => "News",
        "navbar" => true,
        "icon" => "fas fa-newspaper",
        "scripts" => [
            "static/Shuffle/dist/shuffle.min.js",
            "static/js/newsgrid.js",
            "static/js/news.js"
        ]
    ],
    "404" => [
        "title" => "404 error"
    ]
]);
