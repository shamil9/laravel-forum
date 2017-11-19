<?php

namespace App\Inspections;

class Spam
{
    private $inspections = [
        ForbiddenKeyWords::class,
        KeyRepetition::class,
    ];
    /**
     * @param $body
     * @return bool
     */
    public function check($body)
    {
        foreach ($this->inspections as $inspection) {
            app($inspection)->detect($body);
        }

        return false;
    }
}