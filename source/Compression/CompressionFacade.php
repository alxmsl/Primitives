<?php
/*
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace alxmsl\Primitives\Compression;
use alxmsl\Primitives\Compression\Exception\CompressionNotFoundException;

/**
 * Compression facade class
 * @author alxmsl
 */
final class CompressionFacade {
    /**
     * Get compression encoding method
     * @param AbstractCompression $Compression compression instance
     * @return string compression method identifier
     * @throws CompressionNotFoundException when compression encoding not found for instance
     */
    public static function getEncoding(AbstractCompression $Compression) {
        switch (get_class($Compression)) {
            case ZlibCompression::class:
                return AbstractCompression::ZLIB_DEFLATE;
            default:
                throw new CompressionNotFoundException();
        }
    }

    /**
     * Create compression instance
     * @param string $encoding compression method identifier
     * @return AbstractCompression compression instance
     * @throws CompressionNotFoundException when compression class not found for encoding
     */
    public static function create($encoding) {
        switch ($encoding) {
            case AbstractCompression::ZLIB_DEFLATE:
                return new ZlibCompression();
            default:
                throw new CompressionNotFoundException();
        }
    }
}
