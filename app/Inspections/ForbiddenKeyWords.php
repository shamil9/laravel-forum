<?php

namespace App\Inspections;


class ForbiddenKeyWords
{
    protected $keywords = [
        'Yahoo customer support',
    ];

    public function detect($body)
    {
        foreach ($this->keywords as $words) {
            if (stripos($body, $words) !== false)
                throw new \Exception('Your message contains spam');
        }
    }
}