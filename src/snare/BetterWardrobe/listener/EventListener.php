<?php

declare(strict_types = 1);

namespace snare\BetterWardrobe\listener;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerLoginEvent;
use snare\BetterWardrobe\BetterWardrobe;

class EventListener implements Listener
{
    public function onLogin(PlayerLoginEvent $event) : void
    {
        if(BetterWardrobe::getBetterWardrobe()->getSessionManager()->getSession($event->getPlayer()->getName()) === null) {
            BetterWardrobe::getBetterWardrobe()->getSessionManager()->createSession($event->getPlayer()->getName());
        }
    }
}