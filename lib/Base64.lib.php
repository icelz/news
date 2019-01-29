<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

/**
 * Alternative Base64 encoding/decoding that's safe for filenames and URLs.
 */
class Base64 {

    const STANDARD_CHARS = ['+', '/', '='];
    const ALTERNATE_CHARS = ['-', '!', '_'];

    /**
     * Encode $data into "alternate" Base64.
     * @param mixed $data
     * @return string
     */
    public static function encode($data): string {
        return self::toAlternate(base64_encode($data));
    }

    /**
     * Decode "alternate" Base64 into the original data.
     * @param string $base64
     * @return mixed
     */
    public static function decode(string $base64) {
        return base64_decode(self::toStandard($base64));
    }

    /**
     * Convert "alternate" Base64 into standard Base64.
     * @param string $base64
     * @return string
     */
    public static function toStandard(string $base64): string {
        return str_replace(self::ALTERNATE_CHARS, self::STANDARD_CHARS, $base64);
    }

    /**
     * Convert standard Base64 into URL and filename safe "alternate" Base64.
     * @param string $base64
     * @return string
     */
    public static function toAlternate(string $base64): string {
        return str_replace(self::STANDARD_CHARS, self::ALTERNATE_CHARS, $base64);
    }

}