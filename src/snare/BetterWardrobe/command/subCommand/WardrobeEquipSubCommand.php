<?php

declare(strict_types = 1);

namespace snare\BetterWardrobe\command\subCommand;

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\exception\ArgumentOrderException;
use pocketmine\command\CommandSender;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use snare\BetterWardrobe\BetterWardrobe;

class WardrobeEquipSubCommand extends BaseSubCommand
{
    public function __construct()
    {
        parent::__construct(BetterWardrobe::getBetterWardrobe(), "equip", "Equip a set.");
        $this->setPermission("betterwardrobe.equip.command");
    }

    /**
     * @throws ArgumentOrderException
     */
    public function prepare(): void
    {
        $this->registerArgument(0, new IntegerArgument("set"));
    }

    /**
     * @param CommandSender $sender
     * @param string $aliasUsed
     * @param array $args
     */
    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if(!$sender instanceof Player) return;

        if(!isset($args["set"]) || !is_numeric($args["set"]) || (int)$args["set"] < 1 || (int)$args["set"] > 9) {
            $sender->sendMessage(TextFormat::colorize(BetterWardrobe::getBetterWardrobe()->getConfig()->get("invalid-set")));
            return;
        }

        if(!$sender->hasPermission("betterwardrobe.set." . (int)$args["set"] - 1)) {
            $sender->sendMessage(TextFormat::colorize(BetterWardrobe::getBetterWardrobe()->getConfig()->get("no-permission")));
            return;
        }

        $setId = (int)$args["set"] - 1;
        $session = BetterWardrobe::getBetterWardrobe()->getSessionManager()->getSession($sender->getName());

        foreach ($sender->getArmorInventory()->getContents(true) as $slot => $item) {
            if($item->getTypeId() === VanillaItems::AIR()->getTypeId() && (!isset($session->getUsableWardrobe()[$setId][$slot]) || $session->getUsableWardrobe()[$setId][$slot]->getTypeId() === VanillaItems::AIR()->getTypeId())) {
                continue;
            }

            if($item->getTypeId() === VanillaItems::AIR()->getTypeId() && isset($session->getUsableWardrobe()[$setId][$slot])) {
                $session->addItem($setId, $slot, VanillaItems::AIR(), true);
            } elseif($item->getTypeId() !== VanillaItems::AIR()->getTypeId() && (!isset($session->getUsableWardrobe()[$setId][$slot]) || $session->getUsableWardrobe()[$setId][$slot]->getTypeId() === VanillaItems::AIR()->getTypeId())) {
                $session->addItem($setId, $slot, $item);
                $sender->getArmorInventory()->setItem($slot, VanillaItems::AIR());
            }
        }

        $sender->sendMessage(str_replace("{SET}", (string)($setId + 1), TextFormat::colorize(BetterWardrobe::getBetterWardrobe()->getConfig()->get("equipped-set"))));
    }

}