<?php

declare(strict_types = 1);

namespace snare\BetterWardrobe\menu\inventory;

use muqsit\invmenu\InvMenu;
use muqsit\invmenu\transaction\InvMenuTransaction;
use muqsit\invmenu\transaction\InvMenuTransactionResult;
use muqsit\invmenu\type\InvMenuTypeIds;
use pocketmine\block\utils\DyeColor;
use pocketmine\block\VanillaBlocks;
use pocketmine\item\Armor;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use snare\BetterWardrobe\BetterWardrobe;

class WardrobeInventory
{
    /** @var Player */
    private Player $player;

    /** @var InvMenu */
    private InvMenu $menu;

    public function __construct(Player $player)
    {
        $this->player = $player;
        $this->menu = InvMenu::create(InvMenuTypeIds::TYPE_DOUBLE_CHEST);

        $this->loadItems();
        $this->menu->setListener(function (InvMenuTransaction $transaction) : InvMenuTransactionResult {
            $action = $transaction->getAction();
            $session = BetterWardrobe::getBetterWardrobe()->getSessionManager()->getSession($transaction->getPlayer()->getName());

            if(!in_array($action->getSlot(), range("36", 44))) return $transaction->discard();

            if(!$transaction->getPlayer()->hasPermission("betterwardrobe.slot." . $action->getSlot() - 36)) {
                $transaction->getPlayer()->sendMessage(TextFormat::colorize(BetterWardrobe::getBetterWardrobe()->getConfig()->get("no-permission")));
                $transaction->getPlayer()->removeCurrentWindow();
                return $transaction->discard();
            }

            $setId = $action->getSlot() - 36;

            foreach ($transaction->getPlayer()->getArmorInventory()->getContents(true) as $slot => $item) {
                if($item->getTypeId() === VanillaItems::AIR()->getTypeId() && (!isset($session->getUsableWardrobe()[$setId][$slot]) || $session->getUsableWardrobe()[$setId][$slot]->getTypeId() === VanillaItems::AIR()->getTypeId())) {
                    continue;
                }

                if($item->getTypeId() === VanillaItems::AIR()->getTypeId() && isset($session->getUsableWardrobe()[$setId][$slot])) {
                    $session->addItem($setId, $slot, VanillaItems::AIR(), true);
                } elseif($item->getTypeId() !== VanillaItems::AIR()->getTypeId() && (!isset($session->getUsableWardrobe()[$setId][$slot]) || $session->getUsableWardrobe()[$setId][$slot]->getTypeId() === VanillaItems::AIR()->getTypeId())) {
                    $session->addItem($setId, $slot, $item);
                }
            }

            $transaction->getPlayer()->removeCurrentWindow();
            $transaction->getPlayer()->sendMessage(str_replace("{SET}", (string)($setId + 1), TextFormat::colorize(BetterWardrobe::getBetterWardrobe()->getConfig()->get("equipped-set"))));

            return $transaction->discard();
        });

        $this->menu->send($player);
    }

    private function loadItems() : void
    {
        $session = BetterWardrobe::getBetterWardrobe()->getSessionManager()->getSession($this->player->getName());
        $wardrobe = $session->getUsableWardrobe();

        $idMap = [
            0 => [
                0 => 0,
                1 => 9,
                2 => 18,
                3 => 27
            ],
            1 => [
                0 => 1,
                1 => 10,
                2 => 19,
                3 => 28
            ],
            2 => [
                0 => 2,
                1 => 11,
                2 => 20,
                3 => 29
            ],
            3 => [
                0 => 3,
                1 => 12,
                2 => 21,
                3 => 30
            ],
            4 => [
                0 => 4,
                1 => 13,
                2 => 22,
                3 => 31
            ],
            5 => [
                0 => 5,
                1 => 14,
                2 => 23,
                3 => 32
            ],
            6 => [
                0 => 6,
                1 => 15,
                2 => 24,
                3 => 33
            ],
            7 => [
                0 => 7,
                1 => 16,
                2 => 25,
                3 => 34
            ],
            8 => [
                0 => 8,
                1 => 17,
                2 => 26,
                3 => 35
            ]
        ];

        $colorMap = [
            0 => DyeColor::RED(),
            1 => DyeColor::ORANGE(),
            2 => DyeColor::YELLOW(),
            3 => DyeColor::LIME(),
            4 => DyeColor::GREEN(),
            5 => DyeColor::CYAN(),
            6 => DyeColor::BLUE(),
            7 => DyeColor::PINK(),
            8 => DyeColor::PURPLE()
        ];

        $wardrobeIds = range(0, 8);

        foreach ($wardrobeIds as $id) {
            if($this->player->hasPermission("betterwardrobe.slot." . $id)) {
                $this->menu->getInventory()->setItem($id + 36, VanillaItems::DYE()->setColor(DyeColor::GRAY())->setCustomName(TextFormat::RESET . TextFormat::GRAY . "Slot " . $id + 1 . ": " . TextFormat::GREEN . "Unlocked")->setLore([
                    "",
                    TextFormat::RESET . TextFormat::GRAY . "Right click to equip this set or",
                    TextFormat::RESET . TextFormat::GRAY . "swap it out for your currently equipped set."
                ]));
            } else {
                $this->menu->getInventory()->setItem($id + 36, VanillaItems::DYE()->setColor(DyeColor::RED())->setCustomName(TextFormat::RESET . TextFormat::GRAY . "Slot " . $id + 1 . ": " . TextFormat::RED . "Locked"));
            }

            foreach ($idMap[$id] as $itemSlot) {
                if($this->menu->getInventory()->getItem($itemSlot)->getTypeId() === VanillaItems::AIR()->getTypeId() && $this->player->hasPermission("betterwardrobe.slot." . $id)) {
                    $this->menu->getInventory()->setItem($itemSlot, VanillaBlocks::STAINED_GLASS_PANE()->setColor($colorMap[$id])->asItem());
                }
            }
        }

        foreach ($wardrobe as $wardrobeId => $set) {
           /** @var Item $item */
            foreach ($set as $slot => $item) {
                if($this->player->hasPermission("betterwardrobe.slot." . $wardrobeId)) {
                    if($item instanceof Armor) {
                        $this->menu->getInventory()->setItem($idMap[$wardrobeId][$slot], $item);
                    }
                }
            }
        }

        foreach ($this->menu->getInventory()->getContents(true) as $slot => $item) {
            if($item->getTypeId() === VanillaItems::AIR()->getTypeId()) $this->menu->getInventory()->setItem($slot, VanillaBlocks::STAINED_GLASS_PANE()->setColor(DyeColor::BLACK())->asItem());
        }
    }
}