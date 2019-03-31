<?php

namespace JhnBrn90\SIfixer\Tests;

use JhnBrn90\SIfixer\Fixer;
use PHPUnit\Framework\TestCase;

class FixerTest extends TestCase
{
    /** @test **/
    public function separates_quantity_from_unit()
    {
        $fixer = new Fixer(
            'A solution of A (15mL) was added to 3g something else...'
        );

        $expectedCorrections = [
            '15mL'  => '15 mL',
            '3g'    => '3 g',
        ];

        $this->assertCount(2, $fixer->errors);
        $this->assertEquals('15mL', $fixer->errors[0]);
        $this->assertEquals('3g', $fixer->errors[1]);
        $this->assertEquals($expectedCorrections, $fixer->corrections);
    }
}
