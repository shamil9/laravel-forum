<?php

namespace App\Inspections;


class KeyRepetition
{
    public function detect($body)
    {
        if (preg_match('/(.)\\1{4,}/', $body)) {
            throw new \Exception('Your message contains spam');
        }
    }
}