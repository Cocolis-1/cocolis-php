<?php declare(strict_types=1);
use Cocolis\Api\Version;

final class VersionTest extends \Tests\Api\CocolisTest
{
  public function testCanBeUsedAsString(): void
  {
    $this->assertEquals(
      '2.0.1',
      (string) new Version()
    );
  }
}
