<?php

declare(strict_types=1);

namespace App\Tests\Utils;

use App\Utils\ErrorHandler;
use PHPUnit\Framework\TestCase;

class ErrorHandlerTest extends TestCase
{
    public function testWrapReturnsValueOnSuccess(): void
    {
        $result = ErrorHandler::wrap(fn () => 42, 'test');
        $this->assertSame(42, $result);
    }

    public function testWrapRethrowsWithContext(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('[payment] something broke');

        ErrorHandler::wrap(fn () => throw new \Exception('something broke'), 'payment');
    }

    public function testSafeReturnsDefaultOnException(): void
    {
        $result = ErrorHandler::safe(fn () => throw new \Exception('boom'), 'fallback');
        $this->assertSame('fallback', $result);
    }

    public function testSafeReturnsValueOnSuccess(): void
    {
        $result = ErrorHandler::safe(fn () => 'ok');
        $this->assertSame('ok', $result);
    }
}
