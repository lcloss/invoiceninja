<?php
/**
 * Invoice Ninja (https://invoiceninja.com).
 *
 * @link https://github.com/invoiceninja/invoiceninja source repository
 *
 * @copyright Copyright (c) 2020. Invoice Ninja LLC (https://invoiceninja.com)
 *
 * @license https://opensource.org/licenses/AAL
 */
namespace Tests\Integration;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Cache;
use Tests\MockAccountData;
use Tests\TestCase;

/**
 * @test
 */
class CheckCacheTest extends TestCase
{
    use MockAccountData;
    use DatabaseTransactions;

    public function setUp() :void
    {
        parent::setUp();

        $this->makeTestData();
    }

    public function testWarmedUpCache()
    {
        $date_formats = Cache::get('date_formats');

        $this->assertNotNull($date_formats);
    }

    public function testCacheCount()
    {
        $date_formats = Cache::get('date_formats');

        $this->assertEquals(14, count($date_formats));
    }
}
