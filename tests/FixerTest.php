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
            'A solution of A (15mL, 2 mmol, 1.0equiv) was added to 3g something else...'
        );

        $expectedCorrections = [
            '15mL'  => '15 mL',
            '1.0equiv' => '1.0 equiv',
            '3g'    => '3 g',
        ];

        $this->assertCount(3, $fixer->errors);
        $this->assertEquals('15mL', $fixer->errors[0]);
        $this->assertEquals('1.0equiv', $fixer->errors[1]);
        $this->assertEquals('3g', $fixer->errors[2]);
        $this->assertEquals($expectedCorrections, $fixer->corrections);
    }

    /** @test **/
    public function units_with_a_degree_sign_are_separated_from_the_quantity()
    {
        $fixer = new Fixer('The reaction mixture was stirred at 20°C');

        $this->assertCount(1, $fixer->errors);
        $this->assertEquals('20°C', $fixer->errors[0]);
        $this->assertEquals('20 °C', $fixer->corrections['20°C']);
    }

    /** @test **/
    public function negative_values_have_the_correct_minus_symbol()
    {
        $text = 'The reaction mixture was stirred at -78 °C';
        $fixer = new Fixer($text);

        $this->assertCount(1, $fixer->errors);
        $this->assertEquals('-78 °C', $fixer->errors[0]);
        $this->assertEquals('−78 °C', $fixer->corrections['-78 °C']);
    }

    /** @test **/
    public function spaces_between_minus_and_negative_values_are_removed()
    {
        $fixer = new Fixer('The reaction mixture was stirred at − 10 °C');

        $this->assertCount(1, $fixer->errors);
        $this->assertEquals('− 10 °C', $fixer->errors[0]);
        $this->assertEquals('−10 °C', $fixer->corrections['− 10 °C']);

        // Test with the 'wrong' minus symbol
        $fixer = new Fixer('The reaction mixture was stirred at - 10 °C');

        $this->assertCount(1, $fixer->errors);
        $this->assertEquals('- 10 °C', $fixer->errors[0]);
        $this->assertEquals('−10 °C', $fixer->corrections['- 10 °C']);
    }
}
