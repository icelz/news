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

    /**
     * Generate a HTML card for a grid layout
     * @return string
     */
    function generateGridCard(bool $lazyload = false): string {
        $category = $this->getCategory()->toString();
        $url = $this->getURL();
        $headline = htmlentities($this->getHeadline());
        $source = $this->getSource();
        $imghtml = "";
        if (!empty($this->getImage())) {
            $imghtml = '<a href="' . $this->getURL() . '" target="_BLANK">';
            if (strpos($this->getImage(), "preview.redd.it") !== false) {
                $imgurl = $this->getImage();
            } else {
                $imgurl = Thumbnail::getThumbnailCacheURL($this->getImage(), 500);
            }
            if ($lazyload) {
                $imghtml .= '<img src="./static/img/news-placeholder.svg" data-src="' . $imgurl . '" class="card-img-top newscard-img d-none" alt="">';
                $imghtml .= '<noscript><img src="' . $imgurl . '" class="card-img-top newscard-img" alt=""></noscript>';
            } else {
                $imghtml .= '<img src="' . $imgurl . '" class="card-img-top newscard-img" alt="">';
            }
            $imghtml .= '</a>';
        }
        $html = <<<END
            <div class="col-12 col-sm-6 col-md-6 col-lg-4 px-1 m-0 grid__brick" data-groups='["$category"]'>
                <div class="card mb-2">
                    $imghtml
                    <div class="card-body">
                        <a class="text-dark" href="$url" target="_BLANK">
                            <h4 class="card-title">
                                $headline
                            </h4>
                        </a>
                        <p class="small">$source</p>
                    </div>
                </div>
            </div>
END;
        return $html;
    }

}
