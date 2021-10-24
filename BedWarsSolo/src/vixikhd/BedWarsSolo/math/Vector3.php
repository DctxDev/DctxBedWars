<?php

/**
 * Copyright 2020 - 2021 THE DCTX TEAM
 */

declare(strict_types=1);

namespace vixikhd\BedWarsSolo\math;

/**
 * Class Vector3
 * @package BedWars\math
 */
class Vector3 extends \pocketmine\math\Vector3 {

    /**
     * @return string
     */
    public function __toString() {
        $pos = "$this->x,$this->y,$this->z";
        return "$pos";
    }

    /**
     * @param string $string
     * @return Vector3
     */
    public static function fromString($string) {
        return new Vector3((int)explode(",", $string)[0], (int)explode(",", $string)[1], (int)explode(",", $string)[2]);
    }
}