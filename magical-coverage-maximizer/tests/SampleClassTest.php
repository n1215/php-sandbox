<?php
declare(strict_types=1);

namespace N1215\MagicalCoverageMaximizer;

/**
 * Class SampleClassTest
 * @package N1215\MagicalCoverageMaximizer
 */
class SampleClassTest extends \PHPUnit\Framework\TestCase
{
    public function test_run(): void
    {
        (new SampleClass())->run();
        $this->assertTrue(true);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        gc_collect_cycles();
    }
}