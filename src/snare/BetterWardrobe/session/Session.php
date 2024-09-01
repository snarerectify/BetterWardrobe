<?php

declare(strict_types = 1);

namespace snare\BetterWardrobe\session;

use pocketmine\item\Item;
use pocketmine\Server;
use snare\BetterWardrobe\BetterWardrobe;
use snare\BetterWardrobe\utils\Utils;

class Session
{
    /**
     * @param string $name
     * @param string $wardrobe
     */
    public function __construct(private readonly string $name, private string $wardrobe) {}

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getRawWardrobe() : string
    {
        return $this->wardrobe;
    }

    /**
     * @return array
     */
    public function getUsableWardrobe() : array
    {
        $wardrobe = [];

        foreach (json_decode($this->wardrobe, true) as $id => $set) {
            foreach ($set as $slot => $item) {
                $wardrobe[$id][$slot] = Utils::parseItem($item);
            }
        }

        return $wardrobe;
    }

    /**
     * @param int $setId
     * @param int $slot
     * @param Item $item
     * @param bool $swap
     */
    public function addItem(int $setId, int $slot, Item $item, bool $swap = false) : void
    {
        $wardrobe = json_decode($this->wardrobe, true);

        if($swap) {
            if(($p = Server::getInstance()->getPlayerExact($this->name)) !== null && isset($this->getUsableWardrobe()[$setId][$slot])) {
                $p->getArmorInventory()->setItem($slot, $this->getUsableWardrobe()[$setId][$slot]);
            }
        }

        $wardrobe[$setId][$slot] = Utils::serializeItem($item);

        BetterWardrobe::getBetterWardrobe()->getSessionManager()->getDatabase()->executeChange("data.users.set",[
            "name" => $this->name,
            "wardrobe" => json_encode($wardrobe)
        ]);

        $this->wardrobe = json_encode($wardrobe);
    }
}