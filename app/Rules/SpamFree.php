<?php
namespace App\Rules;

use App\Inspections\Spam;

/**
* Spam custom validation
*/
class SpamFree
{
    public function passes($attribute, $value)
    {
        try {
            return !resolve(Spam::class)->check($value);
        } catch (Exception $e) {
            return false;
        }
    }
}
