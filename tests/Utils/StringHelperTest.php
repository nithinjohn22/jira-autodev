<?php

declare(strict_types=1);

namespace App\Tests\Utils;

use App\Utils\StringHelper;
use PHPUnit\Framework\TestCase;

class StringHelperTest extends TestCase
{
    public function testTruncateShortStringUnchanged(): void
    {
        $this->assertSame('hello', StringHelper::truncate('hello', 10));
    }

    public function testTruncateExactLimitUnchanged(): void
    {
        $this->assertSame('hello', StringHelper::truncate('hello', 5));
    }

    public function testTruncateLongStringWithDefaultSuffix(): void
    {
        $this->assertSame('hel...', StringHelper::truncate('hello world', 3));
    }

    public function testTruncateLongStringWithCustomSuffix(): void
    {
        $this->assertSame('hel!!', StringHelper::truncate('hello world', 3, '!!'));
    }

    public function testTruncateEmptyString(): void
    {
        $this->assertSame('', StringHelper::truncate('', 5));
    }

    public function testSlugifyLowercasesText(): void
    {
        $this->assertSame('hello-world', StringHelper::slugify('Hello World'));
    }

    public function testSlugifyReplacesSpacesWithHyphens(): void
    {
        $this->assertSame('foo-bar', StringHelper::slugify('foo bar'));
    }

    public function testSlugifyReplacesSpecialCharacters(): void
    {
        $this->assertSame('foo-bar', StringHelper::slugify('foo@bar!'));
    }

    public function testSlugifyRemovesConsecutiveHyphens(): void
    {
        $this->assertSame('foo-bar', StringHelper::slugify('foo  --  bar'));
    }

    public function testSlugifyTrimsLeadingAndTrailingHyphens(): void
    {
        $this->assertSame('foo', StringHelper::slugify('--foo--'));
    }
}
