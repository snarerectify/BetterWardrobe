<?php

declare(strict_types = 1);

namespace snare\BetterWardrobe\menu\inventory;

use muqsit\invmenu\InvMenu;
use muqsit\invmenu\type\InvMenuTypeIds;
use pocketmine\player\Player;
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
    }

    private function loadItems() : void
    {
        $session = BetterWardrobe::getBetterWardrobe()->getSessionManager()->getSession($this->player->getName());
        $wardrobe = $session->getUsableWardrobe();

        
    }
}