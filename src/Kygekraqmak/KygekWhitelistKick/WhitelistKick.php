<?php

/**
 *     _    __                  _                                     _
 *    | |  / /                 | |                                   | |
 *    | | / /                  | |                                   | |
 *    | |/ / _   _  ____   ____| | ______ ____   _____ ______   ____ | | __
 *    | |\ \| | | |/ __ \ / __ \ |/ /  __/ __ \ / __  | _  _ \ / __ \| |/ /
 *    | | \ \ \_| | <__> |  ___/   <| / | <__> | <__| | |\ |\ | <__> |   <
 * By |_|  \_\__  |\___  |\____|_|\_\_|  \____^_\___  |_||_||_|\____^_\|\_\
 *              | |    | |                          | |
 *           ___/ | ___/ |                          | |
 *          |____/ |____/                           |_|
 *
 * Kicks not whitelisted players when server whitelist is enabled
 * Copyright (C) 2020 Kygekraqmak
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 */

declare(strict_types=1);

namespace Kygekraqmak\KygekWhitelistKick;

use pocketmine\Player;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\event\server\CommandEvent;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as Color;

class WhitelistKick extends PluginBase implements Listener {

    const PREFIX = Color::YELLOW."[".Color::AQUA."KygekWhitelistKick".Color::YELLOW."] ".Color::RESET;

    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->saveResource("config.yml");
        $this->checkConfig();
        $this->getServer()->getCommandMap()->register("whitelistkick", new Commands($this));
    }

    public function checkConfig() {
        if ($this->getConfig()->get("config-version") !== "1.0") {
            $this->getLogger()->notice("Your configuration file is outdated, updating the config.yml...");
            $this->getLogger()->notice("The old configuration file can be found at config_old.yml");
            rename($this->getDataFolder()."config.yml", $this->getDataFolder()."config_old.yml");
            $this->saveResource("config.yml");
            return;
        }
        if ($this->getConfig()->get("reset") === true) {
            $this->getLogger()->notice("Successfully reset the configuration file");
            unlink($this->getDataFolder()."config.yml");
            $this->saveResource("config.yml");
            return;
        }
    }

    public function onWhitelistEnabled(CommandEvent $event) {
        $command = $event->getCommand();
        $sender = $event->getSender();
        if ($command == "whitelist on") {
            if ($this->getConfig()->get("enabled") === true) {
                $this->getConfig()->reload();
                foreach ($this->getServer()->getOnlinePlayers() as $player) {
                    if ($this->isWhitelisted($player)) continue;
                    $reason = str_replace("&", "§", $this->getConfig()->get("reason"));
                    $reason = ($reason != null) ? $reason : self::PREFIX.Color::RED."Whitelist have been enabled and you are not whitelisted!";
                    $player->kick($reason);
                }
            }
        }
    }

    public function enableWhitelistKick(CommandSender $sender) {
        if ($this->getConfig()->get("enabled") === true) {
            $sender->sendMessage(self::PREFIX.Color::RED."KygekWhitelistKick have been enabled");
        } else {
            $this->setConfig("enabled", true);
            $sender->sendMessage(self::PREFIX.Color::GREEN."Successfully enabled KygekWhitelistKick");
        }
    }

    public function disableWhitelistKick(CommandSender $sender) {
        if ($this->getConfig()->get("enabled") === false) {
            $sender->sendMessage(self::PREFIX.Color::RED."KygekWhitelistKick have been disabled");
        } else {
            $this->setConfig("enabled", false);
            $sender->sendMessage(self::PREFIX.Color::GREEN."Successfully disabled KygekWhitelistKick");
        }
    }

    public function setKickReason($reason, CommandSender $sender) {
        $this->setConfig("reason", $reason);
        $sender->sendMessage(self::PREFIX.Color::GREEN."Successfully changed kick reason");
    }

    public function getHelp(CommandSender $sender) {
        $sender->sendMessage(Color::YELLOW."===== ".Color::GREEN."KygekWhitelistKick Commands".Color::YELLOW." =====");
        $sender->sendMessage(Color::AQUA."> help: ".Color::GRAY."/wlkick help");
        $sender->sendMessage(Color::AQUA."> off: ".Color::GRAY."/wlkick off");
        $sender->sendMessage(Color::AQUA."> on: ".Color::GRAY."/wlkick on");
        $sender->sendMessage(Color::AQUA."> set: ".Color::GRAY."/wlkick set <reason>");
    }

    public function getSubcommandUsage(CommandSender $sender) {
        $sender->sendMessage(self::PREFIX.Color::GRAY."Usage: /wlkick set <reason>");
    }

    public function setConfig($key, $value) {
        $this->getConfig()->set($key, $value);
        $this->getConfig()->save();
        $this->getConfig()->reload();
    }

    public function isWhitelisted(Player $player) : bool {
        if ($player->isOp()) return true;
        $file = $this->getServer()->getDataPath()."\\white-list.txt";
        if (filesize($file) == 0) return false;
        $fopen = fopen($file, "r");
        $whitelist = explode("\n", fread($fopen, filesize($file)));
        fclose($fopen);
        return in_array(strtolower($player->getName()), $whitelist);
    }

}
