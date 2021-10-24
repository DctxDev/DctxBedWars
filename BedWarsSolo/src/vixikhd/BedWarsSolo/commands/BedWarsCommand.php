<?php
/**
 * Copyright 2020 - 2021 THE DCTX TEAM
 */

declare(strict_types=1);

namespace vixikhd\BedWarsSolo\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginBase;
use vixikhd\BedWarsSolo\arena\Arena;
use vixikhd\BedWarsSolo\BedWars;

/**
 * Class BedWarsCommand
 * @package BedWars\commands
 */
class BedWarsCommand extends Command implements PluginIdentifiableCommand {

    /** @var BedWars $plugin */
    protected $plugin;

    /**
     * BedWarsCommand constructor.
     * @param BedWars $plugin
     */
    public function __construct(BedWars $plugin) {
        $this->plugin = $plugin;
        parent::__construct("bedwarssolo", "BedWarsSolo commands", \null, ["bws"]);
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return mixed|void
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if(!isset($args[0])) {
            $sender->sendMessage("§cUsage: §7/bws help");
            return;
        }
        switch ($args[0]) {
            case "help":
                if(!$sender->hasPermission("bws.cmd.help")) {
                    $sender->sendMessage("§cYou have not permissions to use this command!");
                    break;
                }
                $sender->sendMessage("§a> BedWars commands:\n" .
                    "§7/bws help : Displays list of BedWars commands\n".
                    "§7/bws create : Create BedWars arena\n".
                    "§7/bws remove : Remove BedWars arena\n".
                    "§7/bws set : Set BedWars arena\n".
                    "§7/bws stats : See BedWars stats\n".
                    "§7/bws random : Join random arena BedWars\n".
                    "§7/bws test : Test BedWars\n".
                    "§7/bws stoptest : Stop test BedWars\n".
                    "§7/bws arenas : Displays list of arenas");

                break;
            case "create":
                if(!$sender->hasPermission("bws.cmd.create")) {
                    $sender->sendMessage("§cYou have not permissions to use this command!");
                    break;
                }
                if(!isset($args[1])) {
                    $sender->sendMessage("§cUsage: §7/bws create <arenaName>");
                    break;
                }
                if(isset($this->plugin->arenas[$args[1]])) {
                    $sender->sendMessage("§c> Arena $args[1] already exists!");
                    break;
                }
                $this->plugin->arenas[$args[1]] = new Arena($this->plugin, []);
                $sender->sendMessage("§a> Arena $args[1] created!");
                break;
            case "remove":
                if(!$sender->hasPermission("bws.cmd.remove")) {
                    $sender->sendMessage("§cYou have not permissions to use this command!");
                    break;
                }
                if(!isset($args[1])) {
                    $sender->sendMessage("§cUsage: §7/bws remove <arenaName>");
                    break;
                }
                if(!isset($this->plugin->arenas[$args[1]])) {
                    $sender->sendMessage("§c> Arena $args[1] was not found!");
                    break;
                }

                /** @var Arena $arena */
                $arena = $this->plugin->arenas[$args[1]];

                foreach ($arena->players as $player) {
                    $player->teleport($this->plugin->getServer()->getDefaultLevel()->getSpawnLocation());
                }

                if(is_file($file = $this->plugin->getDataFolder() . "arenas" . DIRECTORY_SEPARATOR . $args[1] . ".yml")) unlink($file);
                unset($this->plugin->arenas[$args[1]]);

                $sender->sendMessage("§a> Arena removed!");
                break;
            case "set":
                if(!$sender->hasPermission("bws.cmd.set")) {
                    $sender->sendMessage("§cYou have not permissions to use this command!");
                    break;
                }
                if(!$sender instanceof Player) {
                    $sender->sendMessage("§c> This command can be used only in-game!");
                    break;
                }
                if(!isset($args[1])) {
                    $sender->sendMessage("§cUsage: §7/bws set <arenaName>");
                    break;
                }
                if(isset($this->plugin->setters[$sender->getName()])) {
                    $sender->sendMessage("§c> You are already in setup mode!");
                    break;
                }
                if(!isset($this->plugin->arenas[$args[1]])) {
                    $sender->sendMessage("§c> Arena $args[1] does not found!");
                    break;
                }
                $sender->sendMessage("§a> You are joined setup mode.\n".
                    "§7- use §lhelp §r§7to display available commands\n"  .
                    "§7- or §ldone §r§7to leave setup mode");
                $this->plugin->setters[$sender->getName()] = $this->plugin->arenas[$args[1]];
                break;
            case "random":
                $this->plugin->joinToRandomArena($sender);
                break;
            case "stats":
                $this->plugin->StatsForm($sender);
                break;
            case "test":
                if(!$sender->hasPermission("bws.cmd.test")) {
                    $sender->sendMessage("§cYou have not permissions to use this command!");
                    break;
                }
                if(!$sender instanceof Player) {
                    $sender->sendMessage("§c> This command can be used only in-game!");
                    break;
                }
                $this->plugin->spawnSpectre($sender);
                $this->plugin->spectreJoin($sender);
                $sender->sendMessage("§a> Succes setup Spectre");
                break;
            case "stoptest":
                if(!$sender->hasPermission("bws.cmd.stoptest")) {
                    $sender->sendMessage("§cYou have not permissions to use this command!");
                    break;
                }
                if(!$sender instanceof Player) {
                    $sender->sendMessage("§c> This command can be used only in-game!");
                    break;
                }
                $this->plugin->spectreLeave($sender);
                $sender->sendMessage("§a> Succes setup Spectre");
                break;
            case "arenas":
                if(!$sender->hasPermission("bws.cmd.arenas")) {
                    $sender->sendMessage("§cYou have not permissions to use this command!");
                    break;
                }
                if(count($this->plugin->arenas) === 0) {
                    $sender->sendMessage("§6> There are 0 arenas.");
                    break;
                }
                $list = "§7> Arenas:\n";
                foreach ($this->plugin->arenas as $name => $arena) {
                    if($arena->setup) {
                        $list .= "§7- $name : §cdisabled\n";
                    }
                    else {
                        $list .= "§7- $name : §aenabled\n";
                    }
                }
                $sender->sendMessage($list);
                break;
            default:
                if(!$sender->hasPermission("bws.cmd.help")) {
                    $sender->sendMessage("§cYou have not permissions to use this command!");
                    break;
                }
                $sender->sendMessage("§cUsage: §7/bws help");
                break;
        }

    }

    /**
     * @return BedWars|Plugin $plugin
     */
    public function getPlugin(): Plugin {
        return $this->plugin;
    }

}
