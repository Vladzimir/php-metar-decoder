<?php

namespace MetarDecoder\Test\ChunkDecoder;

use MetarDecoder\ChunkDecoder\WindShearChunkDecoder;

class WindShearChunkDecoderTest extends \PHPUnit_Framework_TestCase
{
    private $decoder;

    protected function setup()
    {
        $this->decoder = new WindShearChunkDecoder();
    }

    /**
     * Test parsing of valid windshear chunks
     * @param string $chunk
     * @param string $runway
     * @param string $remaining
     * @dataProvider getChunk
     */
    public function testParse($chunk, $runway, $remaining)
    {
        $decoded = $this->decoder->parse($chunk);
        $this->assertEquals($runway, $decoded['result']['windshearRunway']);
        $this->assertEquals($remaining, $decoded['remaining_metar']);
    }

    /**
     * Test parsing of invalid wind shear chunks (bad format)
     * @param string $chunk
     * @dataProvider getBadFormatChunk
     */
    public function testParseBadFormatChunk($chunk)
    {
        $decoded = $this->decoder->parse($chunk);
        $this->assertNull($decoded['result']);
        $this->assertEquals($chunk, $decoded['remaining_metar']);
    }

    /**
     * Test parsing of invalid wind shear chunks (invalid QFU)
     * @param string $chunk
      * @expectedException \MetarDecoder\Exception\ChunkDecoderException
     * @dataProvider getInvalidChunk
     */
    public function testInvalidChunk($chunk)
    {
        $decoded = $this->decoder->parse($chunk);
        $this->assertNull($decoded['result']);
        $this->assertEquals($chunk, $decoded['remaining_metar']);
    }

    public function getChunk()
    {
        return array(
            array(
                "input" => "WS R03 AAA",
                "runway" => "03",
                "remaining" => "AAA",
            ),
            array(
                "input" => "WS R18C BBB",
                "runway" => "18C",
                "remaining" => "BBB",
            ),
            array(
                "input" => "WS ALL RWY CCC",
                "runway" => "all",
                "remaining" => "CCC",
            ),
            array(
                "input" => "WS RWY22 DDD",
                "runway" => "22",
                "remaining" => "DDD",
            ),
        );
    }

    public function getBadFormatChunk()
    {
        return array(
            array("chunk" => "W RWY AAA"),
            array("chunk" => "WS ALL BBB"),
            array("chunk" => "WS R12P CCC"),
        );
    }

    public function getInvalidChunk()
    {
        return array(
            array("chunk" => "WS RWY00 AAA"),
            array("chunk" => "WS R40 BBB"),
            array("chunk" => "WS R50C CCC"),
        );
    }
}
