# BetterWardrobe

Advanced wardrobe plugin for Pocketmine-MP.

## Features
 - Ability to store up to 9 full or partial armor sets.
 - Ability to manipulate and equip sets via a GUI or command.
 - Option to view the wardrobe of other players.
 - Customisable messages and permissions for each armor slot.

## Installation
 1. Download plugin phar from [here](https://poggit.pmmp.io/ci/snarerectify/BetterWardrobe/~)
 2. Add to your servers' plugin folder.
 3. Restart server.

## Commands
| Command         | Description                   | Permission                   |
|-----------------|-------------------------------|------------------------------|
|/wardrobe manage | Access your wardrobe.         | betterwardrobe.manage.command|
|/wardrobe equip  | Equip a set.                  | betterwardrobe.equip.command |
|/wardrobe view   | View another players wardrobe.| betterwardrobe.view.command  |

## Set permissions
There are 9 sets, use betterwardrobe.slot.{setnumber}, so if I wanted a player to have access to the 5th slot, I'd 
grant the player the betterwardrobe.slot.5 permission, all players defaultly have access to the first slot.

## API
```php
use snare\BetterWardrobe\BetterWardrobe;

$instance = BetterWardrobe::getInstance();
```

Various methods can be found below:
```php
$manager = $instance->getSessionManager();

$session = $manager->getSession(string $name);

$wardrobe = $session->getUsableWardrobe(); // returns a complex array, see code for details.

$wardrobe->addItem(int $setId, int $slot, Item $item, bool $swap = false); // again, confusing function, check session class.
```

## Support
Reach out on discord `snare_gale` if having any issues or if you need help with configuration.
