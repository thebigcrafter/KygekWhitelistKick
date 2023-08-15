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

use Kygekraqmak\KygekWhitelistKick\form\Forms;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\PluginOwned;
use pocketmine\utils\TextFormat as TF;
use function count;
use function implode;
use function mb_strtolower;

class Commands extends Command implements PluginOwned {

	private const NO_PERM = TF::RED . "You do not have permission to use this command";
	private const CONFIG_NOT_EXISTS = WhitelistKick::PREFIX . TF::RED . "Configuration file is missing, please restart the server!";

	private WhitelistKick $main;

	public function __construct(WhitelistKick $main) {
		$this->main = $main;
		parent::__construct("whitelistkick", "KygekWhitelistKick commands", "/wlkick [help|off|on|set]", ["wlkick"]);
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args) : bool {
		if ($this->getOwningPlugin()->configExists()) $this->getOwningPlugin()->reloadConfig();

		if (mb_strtolower($this->getOwningPlugin()->getConfig()->get("mode")) === "form") {
			if (!$sender instanceof Player) {
				$sender->sendMessage(WhitelistKick::PREFIX . TF::RED . "Form mode can only be executed in-game!");
				return true;
			}

			if (
				!$sender->hasPermission("kygekwhitelistkick.cmd." . (Forms::isEnabled() ? "off" : "on")) &&
				!$sender->hasPermission("kygekwhitelistkick.cmd.set") && !$sender->hasPermission("kygekwhitelistkick.cmd")
			) {
				$sender->sendMessage(WhitelistKick::PREFIX . TF::RED . "You do not have permission to open KygekWhitelistKick form!");
				return true;
			}

			if (!$this->getOwningPlugin()->configExists()) {
				$sender->sendMessage(self::CONFIG_NOT_EXISTS);
				return true;
			}

			Forms::mainForm($sender);
			return true;
		}

		if (count($args) < 1) {
			if ($sender->hasPermission("kygekwhitelistkick.cmd.help") || $sender->hasPermission("kygekwhitelistkick.cmd"))
				$this->getOwningPlugin()->getHelp($sender);
			else $sender->sendMessage(self::NO_PERM);
		} elseif (isset($args[0])) {
			switch ($args[0]) {
				case "help":
					if ($sender->hasPermission("kygekwhitelistkick.cmd.help") || $sender->hasPermission("kygekwhitelistkick.cmd")) {
						$this->getOwningPlugin()->getHelp($sender);
					}
					else $sender->sendMessage(self::NO_PERM);
					break;
				case "off":
					if ($sender->hasPermission("kygekwhitelistkick.cmd.off") || $sender->hasPermission("kygekwhitelistkick.cmd")) {
						if (!$this->getOwningPlugin()->configExists()) {
							$sender->sendMessage(self::CONFIG_NOT_EXISTS);
							return true;
						}
						$this->getOwningPlugin()->disableWhitelistKick($sender);
					}
					else $sender->sendMessage(self::NO_PERM);
					break;
				case "on":
					if ($sender->hasPermission("kygekwhitelistkick.cmd.on") || $sender->hasPermission("kygekwhitelistkick.cmd")) {
						if (!$this->getOwningPlugin()->configExists()) {
							$sender->sendMessage(self::CONFIG_NOT_EXISTS);
							return true;
						}
						$this->getOwningPlugin()->enableWhitelistKick($sender);
					}
					else $sender->sendMessage(self::NO_PERM);
					break;
				case "set":
					if ($sender->hasPermission("kygekwhitelistkick.cmd.set") || $sender->hasPermission("kygekwhitelistkick.cmd")) {
						if (empty($args[1])) $this->getOwningPlugin()->getSubcommandUsage($sender);
						else {
							if (!$this->getOwningPlugin()->configExists()) {
								$sender->sendMessage(self::CONFIG_NOT_EXISTS);
								return true;
							}
							unset($args[0]);
							$this->getOwningPlugin()->setKickReason(implode(" ", $args), $sender);
						}
					} else $sender->sendMessage(self::NO_PERM);
					break;
				default:
					if ($sender->hasPermission("kygekwhitelistkick.cmd.help") || $sender->hasPermission("kygekwhitelistkick.cmd"))
						$this->getOwningPlugin()->getHelp($sender);
					else $sender->sendMessage(self::NO_PERM);
			}
		}
		return true;
	}

	public function getOwningPlugin() : WhitelistKick {
		return $this->main;
	}

}
