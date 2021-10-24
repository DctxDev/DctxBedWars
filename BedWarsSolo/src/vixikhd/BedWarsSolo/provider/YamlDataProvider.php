<?php

/**
 * Copyright 2020 - 2021 THE DCTX TEAM
 */

declare(strict_types=1);

namespace vixikhd\BedWarsSolo\provider;

use pocketmine\level\Level;
use pocketmine\utils\Config;
use vixikhd\BedWarsSolo\arena\Arena;
use vixikhd\BedWarsSolo\BedWars;

/**
 * Class YamlDataProvider
 * @package BedWars\provider
 */
class YamlDataProvider {

    /** @var BedWars $plugin */
    private $plugin;

    /**
     * YamlDataProvider constructor.
     * @param BedWars $plugin
     */
    public function __construct(BedWars $plugin) {
        $this->plugin = $plugin;
        $this->init();
        $this->loadArenas();
    }

    public function init() {
        if(!is_dir($this->getDataFolder())) {
            @mkdir($this->getDataFolder());
        }
        if(!is_dir($this->getDataFolder() . "arenas")) {
            @mkdir($this->getDataFolder() . "arenas");
        }
        if(!is_dir($this->getDataFolder() . "saves")) {
            @mkdir($this->getDataFolder() . "saves");
        }
    }

    public function loadArenas() {
        foreach (glob($this->getDataFolder() . "arenas" . DIRECTORY_SEPARATOR . "*.yml") as $arenaFile) {
            $config = new Config($arenaFile, Config::YAML);
            $this->plugin->arenas[basename($arenaFile, ".yml")] = new Arena($this->plugin, $config->getAll(\false));
        }
    }

    public function saveArenas() {
        foreach ($this->plugin->arenas as $fileName => $arena) {
            if($arena->level instanceof Level) {
                foreach ($arena->players as $player) {
                    $player->teleport($player->getServer()->getDefaultLevel()->getSpawnLocation());
                }
                // must be reseted
                $arena->mapReset->loadMap($arena->level->getFolderName(), true);
            }
            $config = new Config($this->getDataFolder() . "arenas" . DIRECTORY_SEPARATOR . $fileName . ".yml", Config::YAML);
            $config->setAll($arena->data);
            $config->save(false);
        }
    }

    /**
     * @return string $dataFolder
     */
    private function getDataFolder(): string {
        return $this->plugin->getDataFolder();
    }
}
