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
use snare\BetterWardrobe\session\Session;

class WardrobeViewInventory
{
    /** @var InvMenu */
    private InvMenu $menu;

    /** @var Session */
    private Session $target;

    public function __construct(Player $player, Session $target)
    {
        $this->target = $target;

        $this->menu = InvMenu::create(InvMenuTypeIds::TYPE_DOUBLE_CHEST);
        $this->menu->setListener(InvMenu::readonly());
        $this->loadItems();
        $this->menu->setName($target->getName() . "'s Wardrobe (1/1");

        $this->menu->send($player);
    }

    private function loadItems() : void
    {
        $wardrobe = $this->target->getUsableWardrobe();

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
            foreach ($idMap[$id] as $itemSlot) {
                if($this->menu->getInventory()->getItem($itemSlot)->getTypeId() === VanillaItems::AIR()->getTypeId()) {
                    $this->menu->getInventory()->setItem($itemSlot, VanillaBlocks::STAINED_GLASS_PANE()->setColor($colorMap[$id])->asItem());
                }
            }
        }

        foreach ($wardrobe as $wardrobeId => $set) {
            /** @var Item $item */
            foreach ($set as $slot => $item) {
                if($item instanceof Armor) {
                    $this->menu->getInventory()->setItem($idMap[$wardrobeId][$slot], $item);
                }
            }
        }

        foreach ($this->menu->getInventory()->getContents(true) as $slot => $item) {
            if($item->getTypeId() === VanillaItems::AIR()->getTypeId()) $this->menu->getInventory()->setItem($slot, VanillaBlocks::STAINED_GLASS_PANE()->setColor(DyeColor::BLACK())->asItem());
        }
    }
}