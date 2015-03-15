<?php

namespace alxmsl\Primitives\Test;
use alxmsl\Primitives\Compression\AbstractCompression;
use alxmsl\Primitives\Compression\CompressionFacade;
use alxmsl\Primitives\Compression\Exception\CompressionNotFoundException;
use alxmsl\Primitives\Compression\Exception\IncorrectPackageFormatException;
use alxmsl\Primitives\Compression\Packager\Packager;
use alxmsl\Primitives\Compression\ZlibCompression;
use InvalidArgumentException;
use PHPUnit_Framework_TestCase;

/**
 * Class with tests for compression
 * @author alxmsl
 */
final class CompressionTest extends PHPUnit_Framework_TestCase {
    public function testPackager() {
        $C1 = new ZlibCompression();

        $data1 = 'some data for compression';
        $encoded1 = sprintf("Content-Encoding: zlib\n\n%s", gzcompress($data1));
        $this->assertEquals($encoded1, $package = Packager::pack($C1, $data1));
        $this->assertEquals($data1, Packager::unpack($package));

        $data2 = ["some", "not", "string", "data"];
        try {
            Packager::pack($C1, $data2);
            $this->assertTrue(false);
        } catch (InvalidArgumentException $Ex) {
            $this->assertTrue(true);
        }

        $C2 = $this->getMockForAbstractClass(AbstractCompression::class);
        try {
            Packager::pack($C2, $data1);
            $this->assertTrue(false);
        } catch (CompressionNotFoundException $Ex) {
            $this->assertTrue(true);
        }

        $wrong1 = sprintf("Content-Encoding: unknown\n\n%s", gzcompress($data1));
        try {
            Packager::unpack($wrong1);
            $this->assertTrue(false);
        } catch (CompressionNotFoundException $Ex) {
            $this->assertTrue(true);
        }

        $wrong2 = sprintf("Content-Encoding:unknown\n\n%s", gzcompress($data1));
        try {
            Packager::unpack($wrong2);
            $this->assertTrue(false);
        } catch (IncorrectPackageFormatException $Ex) {
            $this->assertTrue(true);
        }

        $wrong3 = sprintf("Content-BroKEnunknown\n\t\t\t%s", gzcompress($data1));
        try {
            Packager::unpack($wrong3);
            $this->assertTrue(false);
        } catch (IncorrectPackageFormatException $Ex) {
            $this->assertTrue(true);
        }

        $wrong4 = ["some", "not", "string", "compressed", "data"];
        try {
            Packager::unpack($wrong4);
            $this->assertTrue(false);
        } catch (InvalidArgumentException $Ex) {
            $this->assertTrue(true);
        }
    }

    public function testCompressionFacadeGetEncoding() {
        $C1 = new ZlibCompression();
        $this->assertEquals(AbstractCompression::ZLIB_DEFLATE, CompressionFacade::getEncoding($C1));

        $C2 = $this->getMockForAbstractClass(AbstractCompression::class);
        try {
            CompressionFacade::getEncoding($C2);
            $this->assertTrue(false);
        } catch (CompressionNotFoundException $Ex) {
            $this->assertTrue(true);
        }
    }

    public function testCompressionFacadeCreate() {
        $this->assertInstanceOf(ZlibCompression::class, CompressionFacade::create(AbstractCompression::ZLIB_DEFLATE));
        try {
            CompressionFacade::create('unknown encoding');
            $this->assertTrue(false);
        } catch (CompressionNotFoundException $Ex) {
            $this->assertTrue(true);
        }
    }

    public function testZlibCompression() {
        $C = new ZlibCompression();

        $data1 = 'some data for compression';
        $this->assertEquals($compressed = gzcompress($data1), $C->compress($data1));
        $this->assertEquals(gzuncompress($compressed), $C->decompress($compressed));

        $data2 = ["some", "not", "string", "data"];
        try {
            $C->compress($data2);
            $this->assertTrue(false);
        } catch (InvalidArgumentException $Ex) {
            $this->assertTrue(true);
        }

        $data3 = ["some", "not", "string", "compressed", "data"];
        try {
            $C->compress($data3);
            $this->assertTrue(false);
        } catch (InvalidArgumentException $Ex) {
            $this->assertTrue(true);
        }
    }
}
