<?php

declare(strict_types = 1);

namespace snare\BetterWardrobe\session;

use pocketmine\item\Item;
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
     * @return Item[]
     */
    public function getUsableWardrobe() : array
    {
        $wardrobe = [];

        foreach (json_decode($this->wardrobe, true) as $set) {
            foreach ($set as $slot => $item) {
                $wardrobe[$slot] = Utils::parseItem($item);
            }
        }

        return $wardrobe;
    }

    /**
     * @param Item[] $items
     */
    public function setWardrobe(array $items) : void
    {
        $wardrobe = [];

        foreach ($items as $set) {
            foreach ($set as $slot => $item) {
                $wardrobe[$slot] = Utils::serializeItem($item);
            }
        }

        BetterWardrobe::getBetterWardrobe()->getSessionManager()->getDatabase()->executeChange("data.users.set",[
            "name" => $this->name,
            "wardrobe" => json_encode($wardrobe)
        ]);

        $this->wardrobe = json_encode($wardrobe);
    }
}