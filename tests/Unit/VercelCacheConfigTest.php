<?php

namespace Tests\Unit;

use Tests\TestCase;

class VercelCacheConfigTest extends TestCase
{
    public function test_vercel_uses_file_cache_when_database_cache_is_requested(): void
    {
        putenv('VERCEL=1');
        putenv('CACHE_STORE=database');
        putenv('APP_NAME=Venajon Microfinance');
        putenv('CACHE_PREFIX=');

        $config = require base_path('config/cache.php');

        $this->assertSame('file', $config['default']);
    }
}
