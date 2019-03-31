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
    }

    protected function separateUnitsFromQuantity()
    {
        $pattern = '/(\d+\.?\d*)([a-zA-Z]+\/?[a-zA-Z]*)/';

        preg_match_all($pattern, $this->text, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $this->errors[] = $match[0];
            $this->corrections[$match[0]] = "{$match[1]} {$match[2]}";
        }
    }
}
