<?php
/*
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace alxmsl\Primitives\Compression;

/**
 * Compression interface
 * @author alxmsl
 */
interface CompressionInterface {
    /**
     * Compression method
     * @param string $data compress data
     * @return string compressed data
     */
    public function compress($data);

    /**
     * Decompression method
     * @param string $compressed compressed data
     * @return string uncompressed data
     */
    public function decompress($compressed);
}
