<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

class NewsItem {

    private $img = "";
    private $headline = "";
    private $source = "";
    private $via = "";
    private $url = "";
    private $timestamp = 0;
    private $category;

    function __construct(string $headline = "", string $img = "", string $url = "", string $source = "", string $via = "", int $timestamp = 0, $category = null) {
        $this->headline = $headline;
        $this->img = $img;
        $this->url = $url;
        $this->source = $source;
        $this->via = $via;
        $this->timestamp = $timestamp;
        if (is_null($category)) {
            $this->category = new NewsCategory(NewsCategory::GENERAL);
        } else {
            $this->category = $category;
        }
    }

    function getImage(): string {
        return $this->img;
    }

    function getURL(): string {
        return $this->url;
    }

    function getHeadline(): string {
        return $this->headline;
    }

    function getSource(): string {
        return $this->source;
    }

    function getVia(): string {
        return $this->via;
    }

    function getTimestamp(): int {
        return $this->timestamp;
    }

    function getCategory(): \NewsCategory {
        return $this->category;
    }

}
