<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

class NewsCategory {

    private $category;

    const NONE_ALL = -1;
    const BUSINESS = 1;
    const ENTERTAINMENT = 2;
    const GENERAL = 4;
    const HEALTH = 8;
    const SCIENCE = 16;
    const SPORTS = 32;
    const TECHNOLOGY = 64;

    const CATEGORIES = [
        self::BUSINESS => "Business",
        self::ENTERTAINMENT => "Entertainment",
        self::GENERAL => "General",
        self::HEALTH => "Health",
        self::SCIENCE => "Science",
        self::SPORTS => "Sports",
        self::TECHNOLOGY => "Technology"
    ];

    public function __construct(int $category) {
        $this->category = $category;
    }

    /**
     * Get the category as an int corresponding to one of the constants.
     * @return int
     */
    public function get(): int {
        return $this->category;
    }

    /**
     * Get a string representation of the category.
     * @return string
     */
    public function toString(): string {
        switch ($this->category) {
            case self::BUSINESS:
                return "business";
            case self::ENTERTAINMENT:
                return "entertainment";
            case self::GENERAL:
                return "general";
            case self::HEALTH:
                return "health";
            case self::SCIENCE:
                return "science";
            case self::SPORTS:
                return "sports";
            case self::TECHNOLOGY:
                return "technology";
            default:
                return "";
        }
    }

    public static function fromString(string $category): NewsCategory {
        $cat = self::NONE_ALL;
        switch (strtolower($category)) {
            case "business":
                $cat = self::BUSINESS;
                break;
            case "entertainment":
                $cat = self::ENTERTAINMENT;
                break;
            case "general":
                $cat = self::GENERAL;
                break;
            case "health":
                $cat = self::HEALTH;
                break;
            case "science":
                $cat = self::SCIENCE;
                break;
            case "sports":
                $cat = self::SPORTS;
                break;
            case "technology":
            case "tech":
                $cat = self::TECHNOLOGY;
                break;
        }
        return new NewsCategory($cat);
    }

    /**
     * Get a suitable FontAwesome 5 icon for the category.
     * @return string CSS classes, such as "fas fa-icon".
     */
    public function getIcon(): string {
        switch ($this->category) {
            case self::BUSINESS:
                return "fas fa-briefcase";
            case self::ENTERTAINMENT:
                return "fas fa-tv";
            case self::GENERAL:
                return "fas fa-info-circle";
            case self::HEALTH:
                return "fas fa-heartbeat";
            case self::SCIENCE:
                return "fas fa-atom";
            case self::SPORTS:
                return "fas fa-futbol";
            case self::TECHNOLOGY:
                return "fas fa-laptop";
            default:
                return "fas fa-newspaper";
        }
    }

}
