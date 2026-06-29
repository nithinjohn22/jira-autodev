<?php

declare(strict_types=1);

namespace App\Tests\Utils;

use App\Utils\StringHelper;
use PHPUnit\Framework\TestCase;

class StringHelperTest extends TestCase
{
    public function testTruncateReturnsSameWhenWithinLimit(): void
    {
        $this->assertSame('hello', StringHelper::truncate('hello', 10));
    }

    public function testTruncateReturnsSameWhenExactlyAtLimit(): void
    {
        $this->assertSame('hello', StringHelper::truncate('hello', 5));
    }

    public function testTruncateCutsAndAppendsSuffix(): void
    {
        $this->assertSame('hel...', StringHelper::truncate('hello world', 3));
    }

    public function testTruncateUsesCustomSuffix(): void
    {
        $this->assertSame('hel—', StringHelper::truncate('hello world', 3, '—'));
    }

    public function testSlugifyConvertsToLowercase(): void
    {
        $this->assertSame('hello-world', StringHelper::slugify('Hello World'));
    }

    public function testSlugifyReplacesSpacesWithHyphens(): void
    {
        $this->assertSame('foo-bar', StringHelper::slugify('foo bar'));
    }

    public function testSlugifyRemovesSpecialCharacters(): void
    {
        $this->assertSame('hello-world', StringHelper::slugify('Hello, World!'));
    }

    public function testSlugifyRemovesConsecutiveHyphens(): void
    {
        $this->assertSame('foo-bar', StringHelper::slugify('foo   bar'));
    }

    public function testSlugifyTrimsLeadingAndTrailingHyphens(): void
    {
        $this->assertSame('foo', StringHelper::slugify('!foo!'));
    }
}
