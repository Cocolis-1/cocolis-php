<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use Cocolis\Api\Version;

final class VersionTest extends TestCase
{
    public function testCanBeUsedAsString(): void
    {
        $this->assertEquals(
            '1.0.0',
            (string) new Version()
        );
    }
}
