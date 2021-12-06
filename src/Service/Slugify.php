<?php

namespace App\Service;

class Slugify {

    public function generate(string $input) : string
    {
        // replace non letter or digits by -
        $input = preg_replace('~[^\pL\d]+~u', '-', $input);

        // trim
        $input = trim($input, '-');

        // lowercase
        $input = strtolower($input);

        if (empty($input)) {
        return 'n-a';
        }

        return $input;
    }

}
