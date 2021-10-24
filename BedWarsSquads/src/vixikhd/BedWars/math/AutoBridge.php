<?php

namespace vixikhd\BedWars\math;

use pocketmine\event\player\PlayerInteractEvent;

use pocketmine\math\Vector3;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\level\Level;


use pocketmine\item\Item;
use pocketmine\block\Block;

use pocketmine\event\Listener;

class AutoBridge extends Build {

    function onEnable(): void{
        Server::getInstance()->getPluginManager()->registerEvents($this, $this);
    }

    function onTap(PlayerInteractEvent $event){
        if($event->getItem()->getId() == 344){
        $player = $event->getPlayer();
        $block = $event->getBlock();
        $direction = $player->getDirectionVector();
        for($i = 0; $i < 25; ++$i){
            $x = $i * $direction->x + $block->x;
            $y = $block->y;
            $z = $i * $direction->z + $block->z;
            if($player->getLevel()->getBlock(new Vector3($x, $y, $z))->getId() == 0) {
                $player->getLevel()->setBlock(new Vector3($x, $y, $z), Block::get(24, 0));
            }
        }
        $pr = Item::get(344, 0, 1);
        $player->getInventory()->removeItem($pr);
      }
    }
}