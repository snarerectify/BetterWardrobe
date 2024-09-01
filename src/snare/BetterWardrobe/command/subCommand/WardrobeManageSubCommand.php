<?php

declare(strict_types = 1);

namespace snare\BetterWardrobe\command\subCommand;

use CortexPE\Commando\BaseSubCommand;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use snare\BetterWardrobe\BetterWardrobe;
use snare\BetterWardrobe\menu\inventory\WardrobeInventory;

class WardrobeManageSubCommand extends BaseSubCommand
{
    public function __construct()
    {
        parent::__construct(BetterWardrobe::getBetterWardrobe(), "manage", "Access your wardrobe management menu.");
        $this->setPermission("betterwardrobe.manage.command");
    }

    protected function prepare(): void
    {

    }

    /**
     * @param CommandSender $sender
     * @param string $aliasUsed
     * @param array $args
     */
    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if(!$sender instanceof Player) return;

        new WardrobeInventory($sender);
    }
}