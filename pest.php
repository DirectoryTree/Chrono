<?php

use Pest\Expectation;

expect()->extend('toBeCarbonDateTime', function (string $dateTime): Expectation {
    return $this->toBe($dateTime);
});
