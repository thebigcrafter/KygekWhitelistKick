<?php

/*
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
 * Copyright (C) 2020-2023 Kygekraqmak, KygekTeam
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);

namespace Kygekraqmak\KygekWhitelistKick;

use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\server\CommandEvent;
use pocketmine\permission\DefaultPermissions;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat as TF;
use thebigcrafter\Hydrogen\HConfig;
use thebigcrafter\Hydrogen\Hydrogen;
use function array_keys;
use function file_exists;
use function in_array;
use function mb_strtolower;

class WhitelistKick extends PluginBase implements Listener {

	const PREFIX = TF::YELLOW . "[" . TF::AQUA . "KygekWhitelistKick" . TF::YELLOW . "] " . TF::RESET;

	public static WhitelistKick $instance;

	public static function getInstance() : self {
		return self::$instance;
	}

	protected function onEnable() : void {
		self::$instance = $this;
		$this->saveDefaultConfig();

		Hydrogen::checkForUpdates($this);
		HConfig::verifyConfigVersion($this->getConfig(), "2.2");

		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getServer()->getCommandMap()->register("KygekWhitelistKick", new Commands($this));
	}

	public function onWhitelistEnabled(CommandEvent $event) {
		$command = $event->getCommand();
		if ($command == "whitelist on") {
			if ($this->getConfig()->get("enabled") === true) {
				$this->getConfig()->reload();
				foreach ($this->getServer()->getOnlinePlayers() as $player) {
					if ($this->isWhitelisted($player)) continue;
					$reason = TF::colorize($this->getConfig()->get("reason"));
					$reason = ($reason != null) ? $reason : self::PREFIX . TF::RED . "Whitelist have been enabled and you are not whitelisted!";
					$player->kick($reason);
				}
			}
		}
	}

	public function enableWhitelistKick(CommandSender $sender) {
		if ($this->getConfig()->get("enabled") === true) {
			$sender->sendMessage(self::PREFIX . TF::RED . "KygekWhitelistKick have been enabled");
		} else {
			$this->setConfig("enabled", true);
			$sender->sendMessage(self::PREFIX . TF::GREEN . "Successfully enabled KygekWhitelistKick");
		}
	}

	public function disableWhitelistKick(CommandSender $sender) {
		if ($this->getConfig()->get("enabled") === false) {
			$sender->sendMessage(self::PREFIX . TF::RED . "KygekWhitelistKick have been disabled");
		} else {
			$this->setConfig("enabled", false);
			$sender->sendMessage(self::PREFIX . TF::GREEN . "Successfully disabled KygekWhitelistKick");
		}
	}

	public function setKickReason($reason, CommandSender $sender) {
		$this->setConfig("reason", $reason);
		$sender->sendMessage(self::PREFIX . TF::GREEN . "Successfully changed kick reason");
	}

	public function getHelp(CommandSender $sender) {
		$sender->sendMessage(TF::YELLOW . "===== " . TF::GREEN . "KygekWhitelistKick Commands" . TF::YELLOW . " =====");
		if ($sender->hasPermission("kygekwhitelistkick.cmd.help"))
			$sender->sendMessage(TF::AQUA . "> help: " . TF::GRAY . "/wlkick help");
		if ($sender->hasPermission("kygekwhitelistkick.cmd.off"))
			$sender->sendMessage(TF::AQUA . "> off: " . TF::GRAY . "/wlkick off");
		if ($sender->hasPermission("kygekwhitelistkick.cmd.on"))
			$sender->sendMessage(TF::AQUA . "> on: " . TF::GRAY . "/wlkick on");
		if ($sender->hasPermission("kygekwhitelistkick.cmd.set"))
			$sender->sendMessage(TF::AQUA . "> set: " . TF::GRAY . "/wlkick set <reason>");
	}

	public function getSubcommandUsage(CommandSender $sender) {
		$sender->sendMessage(self::PREFIX . TF::GRAY . "Usage: /wlkick set <reason>");
	}

	public function setConfig($key, $value) {
		$this->getConfig()->set($key, $value);
		$this->getConfig()->save();
		$this->getConfig()->reload();
	}

	public function isWhitelisted(Player $player) : bool {
		if ($player->hasPermission(DefaultPermissions::ROOT_OPERATOR)) return true;
		$this->getServer()->getWhitelisted()->reload();
		return in_array(mb_strtolower($player->getName()), array_keys($this->getServer()->getWhitelisted()->getAll()), true);
	}

	public function configExists() : bool {
		return file_exists($this->getDataFolder() . "config.yml");
	}

}
