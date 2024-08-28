<?php

namespace snare\BetterWardrobe\session;

use poggit\libasynql\DataConnector;
use poggit\libasynql\libasynql;
use snare\BetterWardrobe\BetterWardrobe;

class SessionManager
{
    /** @var Session[] */
    private array $sessions = [];

    /** @var DataConnector */
    private DataConnector $dataConnector;

    public function __construct()
    {
        $this->dataConnector = libasynql::create(BetterWardrobe::getBetterWardrobe(), BetterWardrobe::getBetterWardrobe()->getConfig()->get("database"), [
            "mysql" => "mysql.sql",
            "sqlite" => "sqlite.sql"
        ]);

        $this->dataConnector->executeGeneric("table.users");
        $this->dataConnector->waitAll();
        $this->loadData();
    }

    private function loadData() : void
    {
        $this->dataConnector->executeSelect("data.users.getAll", [], function (array $rows) : void {
            foreach ($rows as $row) {
                $this->sessions[strtolower($row["name"])] = new Session($row["name"], $row["wardrobe"]);
            }
        });
    }

    /**
     * @return Session[]
     */
    public function getSessions() : array
    {
        return $this->sessions;
    }

    /**
     * @param string $name
     * @return Session|null
     */
    public function getSession(string $name) : ?Session
    {
        return $this->sessions[strtolower($name)] ?? null;
    }

    /**
     * @param string $name
     */
    public function createSession(string $name) : void
    {
        $this->dataConnector->executeInsert("data.users.add", [
            "name" => $name,
            "wardrobe" => json_encode("")
        ]);

        $this->sessions[strtolower($name)] = new Session($name, json_encode([]));
    }

    public function unload() : void
    {
        $this->dataConnector->close();
    }

    /**
     * @return DataConnector
     */
    public function getDatabase() : DataConnector
    {
        return $this->dataConnector;
    }
}