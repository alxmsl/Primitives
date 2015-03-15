<?php
/*
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace alxmsl\Primitives\Compression\Packager;
use alxmsl\Primitives\Compression\AbstractCompression;
use alxmsl\Primitives\Compression\CompressionFacade;
use alxmsl\Primitives\Compression\Exception\CompressionNotFoundException;
use alxmsl\Primitives\Compression\Exception\IncorrectPackageFormatException;
use InvalidArgumentException;

/**
 * Compressed data packager class
 * @author alxmsl
 */
final class Packager {
    /**
     * Method for pack data
     * @param AbstractCompression $Compression compression instance
     * @param string $data data for packaging
     * @return string packaged data
     * @throws CompressionNotFoundException when compression encoding not found for instance
     * @throws InvalidArgumentException when data is not string type
     */
    public static function pack(AbstractCompression $Compression, $data) {
        $encoding = CompressionFacade::getEncoding($Compression);
        return implode("\n\n", [
            sprintf('Content-Encoding: %s', $encoding),
            $Compression->compress($data),
        ]);
    }

    /**
     * Method for unpack data
     * @param string $package packaged data
     * @return string unpackaging data
     * @throws CompressionNotFoundException when compression class not found for encoding
     * @throws IncorrectPackageFormatException when package has invalid format
     * @throws InvalidArgumentException when data is not string type
     */
    public static function unpack($package) {
        if (is_string($package)) {
            $packageParts = explode("\n\n", $package, 2);
            if (count($packageParts) != 2) {
                throw new IncorrectPackageFormatException('package header not found');
            }
            list($header, $compressed) = $packageParts;

            $headerParts = explode(' ', $header);
            if (count($headerParts) != 2) {
                throw new IncorrectPackageFormatException('package header invalid');
            }
            list($headerName, $encoding) = $headerParts;

            $Compression = CompressionFacade::create($encoding);
            return $Compression->decompress($compressed);
        } else {
            throw new InvalidArgumentException('package must be a string');
        }
    }
}
