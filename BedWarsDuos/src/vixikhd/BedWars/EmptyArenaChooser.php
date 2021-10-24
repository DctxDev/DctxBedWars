<?php

/**
 * Copyright 2020 - 2021 THE DCTX TEAM
 */

declare(strict_types=1);

namespace vixikhd\BedWars;

use vixikhd\BedWars\arena\Arena;

/**
 * Class EmptyArenaChooser
 * @package vixikhd\onevsone
 */
class EmptyArenaChooser {

    /** @var BedWars $plugin */
    public $plugin;

    /**
     * EmptyArenaQueue constructor.
     * @param OneVsOne $plugin
     */
    public function __construct(BedWars $plugin) {
        $this->plugin = $plugin;
    }



    /**
     * @return null|Arena
     *
     * 1. Choose all arenas
     * 2. Remove in-game arenas
     * 3. Sort arenas by players
     * 4. Sort arenas by rand()
     */
    public function getRandomArena(): ?Arena {
        //1.

        /** @var Arena[] $availableArenas */
        $availableArenas = [];
        foreach ($this->plugin->arenas as $index => $arena) {
            $availableArenas[$index] = $arena;
        }

        //2.
        foreach ($availableArenas as $index => $arena) {
            if($arena->phase !== 0 || $arena->setup) {
                unset($availableArenas[$index]);
            }
        }

        //3.
        $arenasByPlayers = [];
        foreach ($availableArenas as $index => $arena) {
            $arenasByPlayers[$index] = count($arena->players);
        }

        arsort($arenasByPlayers);
        $top = -1;
        $availableArenas = [];

        foreach ($arenasByPlayers as $index => $players) {
            if($top == -1) {
                $top = $players;
                $availableArenas[] = $index;
            }
            else {
                if($top == $players) {
                    $availableArenas[] = $index;
                }
            }
        }

        if(empty($availableArenas)) {
            return null;
        }

        return $this->plugin->arenas[$availableArenas[array_rand($availableArenas, 1)]];
    }
}