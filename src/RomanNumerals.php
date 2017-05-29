<?php

/**
* @author David Ashby <delta.mu.alpha@gmail.com>
*/
class RomanNumerals
{
    // biggest really roman number this supports is 'MMMMCMXCIX', or 4999.
    // larger than that and we just keep stacking on 'M's.
    private $mappings = array(
        'M' => 1000,
        'D' => 500,
        'C' => 100,
        'L' => 50,
        'X' => 10,
        'V' => 5,
        'I' => 1,
        'm' => 1000,
        'd' => 500,
        'c' => 100,
        'l' => 50,
        'x' => 10,
        'v' => 5,
        'i' => 1
    );

    private $value;

    public function __construct($roman = null)
    {
        switch ($this->validate($roman)) {
            case 'int':
                $this->value = $roman;
                break;
            case 'roman':
                $this->value = $this->parse($roman);
                break;
            case 'null':
                $this->value = 0;
                break;
         }
    }

    private function validate($input)
    {
        if (is_int($input)) {
            return 'int';
        } elseif (is_string($input) && preg_match("/^[" . implode('', array_keys($this->mappings)) . "]+$/i", $input)) {
            return 'roman';
        } elseif ($input === null) {
            return 'null';
        } else {
            throw new Exception("Invalid roman numeral provided"); 
        }
    }

    public function __toString()
    {
        try { 
            return (string) $this->latinize($this->value);
        } catch (Exception $e) {
            return '';
        }
    }

    public function parse($roman)
    {
        $tmp = 0;
        for ($i = 0; $i < strlen($roman); $i++) {
            $toggle = false;
            // poor man's reduce
            foreach (str_split(substr($roman, $i)) as $char) {
                if ($this->mappings[$char] > $this->mappings[$roman[$i]]) {
                    $toggle = true;
                    break;
                }
            }
            $toggle ? $tmp -= $this->mappings[$roman[$i]] : $tmp += $this->mappings[$roman[$i]];
        }
        return $tmp;
    }

    private function latinize($int)
    {
        if ($this->value < 1) {
            throw new Exception("Can't output a non-positive roman numeral"); 
        }

        $tmp = '';
        $arabicToRoman = array_flip($this->mappings);

        foreach ($arabicToRoman as $arabic => $roman) {
            $count = floor($int / $arabic);
            if ($count == 4 && isset($arabicToRoman[$arabic * 10])) {
                $tmp = substr($tmp, 0, -1) . $roman . $arabicToRoman[$arabic * 10];
            } else {
                $tmp .= str_repeat($roman, $count);
            }
            $int -= ($arabic * $count);
        }
        return strtoupper($tmp);
    }

    public function toInt()
    {
        return $this->value;
    }

    public function toRoman()
    {
        return $this->latinize($this->value);
    }

    public function add($value)
    {
        switch ($this->validate($value)) {
            case 'int':
                $this->value += $value;
                break;
            case 'roman':
                $this->value += $this->parse($value);
                break;
        }
        return $this;
    }

    public function subtract($value)
    {
        switch ($this->validate($value)) {
            case 'int':
                $this->value -= $value;
                break;
            case 'roman':
                $this->value -= $this->parse($value);
                break;
        }
        return $this;
    }
}
