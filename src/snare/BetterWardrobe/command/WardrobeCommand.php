<?php

declare(strict_types = 1);

namespace snare\BetterWardrobe\command;

use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use snare\BetterWardrobe\BetterWardrobe;
use snare\BetterWardrobe\command\subCommand\WardrobeEquipSubCommand;
use snare\BetterWardrobe\command\subCommand\WardrobeManageSubCommand;
use snare\BetterWardrobe\command\subCommand\WardrobeViewSubCommand;

class WardrobeCommand extends BaseCommand implements PluginOwned
{
    public function __construct()
    {
        parent::__construct(BetterWardrobe::getBetterWardrobe(), "wardrobe");
        $this->setPermission("betterwardrobe.wardrobe.command");
    }

    protected function prepare() : void
    {
        $this->registerSubCommand(new WardrobeManageSubCommand());
        $this->registerSubCommand(new WardrobeEquipSubCommand());
        $this->registerSubCommand(new WardrobeViewSubCommand());
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void
    {

    }

    /**
     * @return Plugin
     */
    public function getOwningPlugin() : Plugin
    {
        return BetterWardrobe::getBetterWardrobe();
    }
}