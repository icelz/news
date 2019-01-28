<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

class NewsSource_Reddit extends NewsSource {

    private $items = [];

    function getItems(): array {
        return $this->items;
    }

    function loadItems() {
        $this->loadSubreddit("news+worldnews+UpliftingNews", new NewsCategory(NewsCategory::GENERAL));
        //$this->loadSubreddit("technology", new NewsCategory(NewsCategory::TECHNOLOGY));
    }

    private function loadSubreddit(string $subreddit, NewsCategory $category) {
        $items = [];
        $json = ApiFetcher::get("https://www.reddit.com/r/$subreddit.json", ["limit" => "50"]);
        $news = json_decode($json, TRUE)['data']['children'];
        foreach ($news as $d) {
            $n = $d['data'];

            // Ignore non-linky or NSFW posts
            if ($n['is_self']) {
                continue;
            }
            if ($n['is_video']) {
                continue;
            }
            if ($n['over_18']) {
                continue;
            }
            if (empty($n['url'])) {
                continue;
            }

            // Thumbnail image
            $image = "";
            if (isset($n['thumbnail']) && $n['thumbnail'] != "default") {
                $image = $n['thumbnail'];
            }
            if (isset($n['preview']['images'][0]['resolutions'])) {
                if (isset($n['preview']['images'][0]['resolutions'][2]['url'])) {
                    $image = $n['preview']['images'][0]['resolutions'][2]['url'];
                } else if (isset($n['preview']['images'][0]['resolutions'][1]['url'])) {
                    $image = $n['preview']['images'][0]['resolutions'][1]['url'];
                }
            }

            $source = "reddit.com";
            if (!empty($n['domain'])) {
                $source = $n['domain'];
            }

            $timestamp = time();
            if (!empty($n['created'])) {
                $timestamp = $n['created'];
            }

            $items[] = new NewsItem($n['title'], $image, $n['url'], $source, "reddit", $timestamp, $category);
        }

        $this->items = array_merge($this->items, $items);
    }

}
