<?php

namespace App\Middleware;

class Spam
{
    /**
     * @param $body
     * @return bool
     */
    public function check($body)
    {
        $this->checkForFobidenWords($body);

        return false;
    }

    /**
     * @return array
     */
    private function getForbidenWords()
    {
        return [
            'Yahoo customer support'
        ];
    }

    /**
     * @param $body
     * @throws \Exception
     */
    private function checkForFobidenWords($body)
    {
        foreach ($this->getForbidenWords() as $words) {
            if (stripos($body, $words) !== false)
                throw new \Exception('Your message contains spam');
        }
    }
}