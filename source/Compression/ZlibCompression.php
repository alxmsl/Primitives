<?php
/*
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace alxmsl\Primitives\Compression;
use InvalidArgumentException;

/**
 * ZLIB-DEFLATE compression class
 * @author alxmsl
 */
final class ZlibCompression extends AbstractCompression {
    /**
     * @inheritdoc
     * @throws InvalidArgumentException when data has not string type
     */
    public function compress($data) {
        if (is_string($data)) {
            return gzcompress($data);
        } else {
            throw new InvalidArgumentException('string data expected');
        }
    }

    /**
     * @inheritdoc
     * @throws InvalidArgumentException when compressed data has not string type
     */
    public function decompress($compressed) {
        if (is_string($compressed)) {
            return gzuncompress($compressed);
        } else {
            throw new InvalidArgumentException('compressed data must be string');
        }
    }
}
