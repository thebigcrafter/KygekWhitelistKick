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
 * A PocketMine-MP plugin that shows information about ranks in the server
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
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\utils\TextFormat as Color;

use Kygekraqmak\KygekWhitelistKick\WhitelistKick;

class Commands extends PluginCommand {

  const PREFIX = Color::YELLOW."[".Color::AQUA."KygekWhitelistKick".Color::YELLOW."] ".Color::RESET;
  const PERMALL = "kygekwhitelistkick.cmd";
  const PERMHELP = "kygekwhitelistkick.cmd.help";
  const PERMOFF = "kygekwhitelistkick.cmd.off";
  const PERMON = "kygekwhitelistkick.cmd.on";
  const PERMSET = "kygekwhitelistkick.cmd.set";
  const NOPERM = Color::RED."You do not have permission to use this command";

  private $main;

  public function __construct(WhitelistKick $main) {
    $this->main = $main;
    parent::__construct("whitelistkick", $main);
    $this->setAliases(["wlkick"]);
    $this->setUsage("/wlkick <help|off|on|set>");
    $this->setDescription("KygekWhitelistKick commands");
  }

  public function main() {
    return $this->main;
  }

  public function execute(CommandSender $sender, string $alias, array $args) : bool {
    if (count($args) < 1) {
      if ($sender->hasPermission(self::PERMALL) || $sender->hasPermission(self::PERMHELP))
        $this->main()->getHelp($sender);
      else $sender->sendMessage(self::NOPERM);
    } elseif (isset($args[0])) {
      switch ($args[0]) {
        case "help":
          if ($sender->hasPermission(self::PERMALL) || $sender->hasPermission(self::PERMHELP))
            $this->main()->getHelp($sender);
          else $sender->sendMessage(self::NOPERM);
          break;
        case "off":
          if ($sender->hasPermission(self::PERMALL) || $sender->hasPermission(self::PERMOFF))
            $this->main()->disableWhitelistKick($sender);
          else $sender->sendMessage(self::NOPERM);
          break;
        case "on":
          if ($sender->hasPermission(self::PERMALL) || $sender->hasPermission(self::PERMON))
            $this->main()->enableWhitelistKick($sender);
          else $sender->sendMessage(self::NOPERM);
          break;
        case "set":
          if ($sender->hasPermission(self::PERMALL) || $sender->hasPermission(self::PERMSET)) {
            if (empty($args[1])) $this->main()->getSubcommandUsage($sender);
            else {
              unset($args[0]);
              $this->main()->setKickReason(implode(" ", $args), $sender);
            }
          } else $sender->sendMessage(self::NOPERM);
          break;
        default:
          if ($sender->hasPermission(self::PERMALL) || $sender->hasPermission(self::PERMHELP))
            $this->main()->getHelp($sender);
          else $sender->sendMessage(self::NOPERM);
      }
    }
    return true;
  }

}
