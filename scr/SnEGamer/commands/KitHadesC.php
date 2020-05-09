<?php

declare(strict_types=1);

namespace SnEGamer\commands;

use SnEGamer\Main;
use SnEGamer\libs\CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use SnEGamer\libs\CortexPE\Commando\args\RawStringArgument;
use pocketmine\Server;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;


class KitHadesC extends BaseCommand
{
    /** @var Charms */
    private $plugin;

    public function __construct(Main $plugin, string $name, string $description = "", array $aliases = [])
    {
        $this->plugin = $plugin;
        parent::__construct($name, $description, $aliases);
    }

    protected function prepare(): void
    {
        $this->setPermission("charms.command.use");
        $this->registerArgument(0, new RawStringArgument("player", true));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {

        if ($sender->hasPermission("charms.command.use")) {
            if (count($args) === 1) {
                $player = $args["player"];
                $target = \pocketmine\Server::getInstance()->getPlayer($player);
                if ($target instanceof Player) {
                    $item = Item::get(Item::ENDER_EYE);
                    $item->setCustomName(TextFormat::BOLD . TextFormat::RED . "Hades Kit" . TextFormat::BOLD . TextFormat::WHITE . " Charm");
                    $lore = [
                        TextFormat::GRAY . "Right-Click to redeem the " . TextFormat::AQUA . "hades kit" . TextFormat::GRAY . ",",
                        TextFormat::GRAY . "and gain all the kit's items!"
                    ];
                    $item->setLore($lore);
                    $item->getNamedTag()->setInt("hadesc", 1);
                    $item->setNamedTagEntry(new ListTag(Item::TAG_ENCH));
                    $inventory = $target->getInventory();
                    if ($inventory->canAddItem($item)) {
                        $inventory->addItem($item);
                        return;
                    }
                } else {
                    $sender->sendMessage("Sorry, " . $args["player"] . " is not online!");
                }
            } else {
                $sender->sendMessage("Usage: /hadesc <player>");
            }
        } else {
            $sender->sendMessage("You don't have permission to use this command.");
        }
    }
}
