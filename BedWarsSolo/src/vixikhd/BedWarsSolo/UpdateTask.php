<?php

namespace vixikhd\BedWarsSolo;

use vixikhd\BedWarsSolo\BedWars;
use pocketmine\scheduler\Task;

class UpdateTask extends Task {
	
	public function __construct(BedWars $plugin) {
        $this->plugin = $plugin;
    }

    public function onRun(int $currentTick) {
		$this->plugin->updateTopWin();
		$this->plugin->updateTopKills();
		$this->plugin->updateLeaderboard();
    }
	
}
