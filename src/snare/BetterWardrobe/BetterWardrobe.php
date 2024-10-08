<?php

declare(strict_types = 1);

namespace snare\BetterWardrobe;

use CortexPE\Commando\exception\HookAlreadyRegistered;
use CortexPE\Commando\PacketHooker;
use muqsit\invmenu\InvMenuHandler;
use pocketmine\plugin\PluginBase;
use snare\BetterWardrobe\command\WardrobeCommand;
use snare\BetterWardrobe\listener\EventListener;
use snare\BetterWardrobe\session\SessionManager;

class BetterWardrobe extends PluginBase
{
    /** @var BetterWardrobe */
    private static BetterWardrobe $instance;

    /** @var SessionManager */
    private SessionManager $sessionManager;

    public function onLoad(): void
    {
        self::$instance = $this;
    }

    /**
     * @throws HookAlreadyRegistered
     */
    public function onEnable(): void
    {
        $this->saveDefaultConfig();
        if(!InvMenuHandler::isRegistered()) InvMenuHandler::register($this);
        if(!PacketHooker::isRegistered()) PacketHooker::register($this);
        $this->getServer()->getCommandMap()->register("BetterWardrobe", new WardrobeCommand());
        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);

        $this->sessionManager = new SessionManager();
    }

    public function onDisable(): void
    {
        $this->sessionManager->unload();
    }

    /**
     * @return BetterWardrobe
     */
    public static function getBetterWardrobe() : BetterWardrobe
    {
        return self::$instance;
    }

    /**
     * @return SessionManager
     */
    public function getSessionManager() : SessionManager
    {
        return $this->sessionManager;
    }
}