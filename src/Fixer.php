<?php

namespace JhnBrn90\SIfixer;

class Fixer
{
    protected $text;

    public $errors = [];

    public function __construct($text)
    {
        $this->text = $text;
        $this->separateUnitsFromQuantity();
        $this->useTheCorrectMinusSymbol();
        $this->removeSpacesBetweenMinusAndQuantity();
    }

    protected function separateUnitsFromQuantity()
    {
        $pattern = '/(\d+\.?\d*)(°?[a-zA-Z]+\/?[a-zA-Z]*)/u';

        preg_match_all($pattern, $this->text, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $this->errors[] = $match[0];
            $this->corrections[$match[0]] = "{$match[1]} {$match[2]}";
        }
    }

    protected function useTheCorrectMinusSymbol()
    {
        $correctMinus = '−';
        $pattern = '/((-\s*(\d+\.?\d*))\s*(°?[a-zA-Z]+\/?[a-zA-Z]*))/u';

        preg_match_all($pattern, $this->text, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $this->errors[] = $match[0];
            $this->corrections[$match[0]] = "$correctMinus{$match[3]} {$match[4]}";
        }
    }

    protected function removeSpacesBetweenMinusAndQuantity()
    {
        $pattern = '/((−\s+(\d+\.?\d*))\s*(°?[a-zA-Z]+\/?[a-zA-Z]*))/u';
        preg_match_all($pattern, $this->text, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $this->errors[] = $match[0];
            $this->corrections[$match[0]] = "−{$match[3]} {$match[4]}";
        }
    }
}
