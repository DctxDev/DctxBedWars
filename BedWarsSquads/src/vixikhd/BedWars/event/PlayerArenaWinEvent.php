<?php

/**
 * Copyright 2020 - 2021 THE DCTX TEAM
 */

declare(strict_types=1);

namespace vixikhd\BedWars\event;

use pocketmine\event\plugin\PluginEvent;
use pocketmine\Player;
use vixikhd\BedWars\arena\Arena;
use vixikhd\BedWars\BedWars;

/**
 * Class PlayerArenaWinEvent
 * @package BedWars\event
 */
class PlayerArenaWinEvent extends PluginEvent {

    /** @var null $handlerList */
    public static $handlerList = \null;

    /** @var Player $player */
    protected $player;

    /** @var Arena $arena */
    protected $arena;

    /**
     * PlayerArenaWinEvent constructor.
     * @param BedWars $plugin
     * @param Player $player
     * @param Arena $arena
     */
    public function __construct(BedWars $plugin, Player $player, Arena $arena) {
        $this->player = $player;
        $this->arena = $arena;
        parent::__construct($plugin);
    }

    /**
     * @return Player $arena
     */
    public function getPlayer(): Player {
        return $this->player;
    }

    /**
     * @return Arena $arena
     */
    public function getArena(): Arena {
        return $this->arena;
    }
}