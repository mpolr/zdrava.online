<?php
namespace Tests;
use Illuminate\Support\Facades\Artisan;
trait MigrateFreshSeedOnce
{
    /**
     * If true, setup has run at least once.
     */
    protected static bool $setUpHasRunOnce = false;
    /**
     * After the first run of setUp "migrate:fresh --seed"
     */
    public function setUp(): void
    {
        parent::setUp();
        if (!static::$setUpHasRunOnce) {
            Artisan::call('migrate:fresh');
            Artisan::call(
                'db:seed', ['--class' => 'DatabaseSeeder']
            );
            static::$setUpHasRunOnce = true;
        }
    }
}
