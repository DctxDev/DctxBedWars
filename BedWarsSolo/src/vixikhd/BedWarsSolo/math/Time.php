<?php

/**
 * Copyright 2020 - 2021 THE DCTX TEAM
 */

declare(strict_types=1);

namespace vixikhd\BedWarsSolo\math;

/**
 * Class Time
 * @package BedWars\math
 */
class Time {

    /**
     * @param int $time
     * @return string
     */
    public static function calculateTime(int $time): string {
        return gmdate("i:s", $time); 
    }
}
