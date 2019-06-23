<?php
/**
 * Yasmin
 * Copyright 2017-2019 Charlotte Dunois, All Rights Reserved
 *
 * Website: https://charuru.moe
 * License: https://github.com/CharlotteDunois/Yasmin/blob/master/LICENSE
*/

namespace CharlotteDunois\Yasmin\Utils;

/**
 * Image Helper methods
 */
class ImageHelpers {
    /**
     * Decides the default extension for an image
     *
     * @param  string $image Image hash
     * @return string        Depending on if animated or not, 'gif' or 'png'
     */
    static function getImageExtension(string $image) : string {
        return (\strpos($image, 'a_') === 0 ? 'gif' : 'png');
    }

    /**
     * Decides whether the input number is the power of 2
     *
     * @param  int|null $size The size we want to check
     * @return boolean        Whether it's a power of two
     */
    static function isPowerOfTwo(?int $size) : bool {
        return $size === null || !($size & ($size - 1));
    }
}
