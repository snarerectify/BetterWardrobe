<?php

declare(strict_types = 1);

namespace snare\BetterWardrobe\command\subCommand;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\exception\ArgumentOrderException;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use snare\BetterWardrobe\BetterWardrobe;
use snare\BetterWardrobe\menu\inventory\WardrobeViewInventory;

class WardrobeViewSubCommand extends BaseSubCommand
{
    public function __construct()
    {
        parent::__construct(BetterWardrobe::getBetterWardrobe(), "view", "View another players' wardrobe.");
    }

    /**
     * @throws ArgumentOrderException
     */
    public function prepare(): void
    {
        $this->registerArgument(0, new RawStringArgument("player"));
    }

    /**
     * @param CommandSender $sender
     * @param string $aliasUsed
     * @param array $args
     */
    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if(!$sender instanceof Player) return;

        if(!isset($args["player"]) || ($targetSession = BetterWardrobe::getBetterWardrobe()->getSessionManager()->getSession($args["player"])) === null) {
            $sender->sendMessage(BetterWardrobe::getBetterWardrobe()->getConfig()->get("specify-valid-player"));
            return;
        }

        new WardrobeViewInventory($sender, $targetSession);
    }
}