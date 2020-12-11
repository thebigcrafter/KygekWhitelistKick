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

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\utils\TextFormat as TF;

class Commands extends PluginCommand {

    private const NO_PERM = TF::RED . "You do not have permission to use this command";

    private $main;

    public function __construct(WhitelistKick $main) {
        $this->main = $main;
        parent::__construct("whitelistkick", $main);
        $this->setAliases(["wlkick"]);
        $this->setUsage("/wlkick <help|off|on|set>");
        $this->setDescription("KygekWhitelistKick commands");
    }

    public function main() : WhitelistKick {
        return $this->main;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) : bool {
        if (count($args) < 1) {
            if ($sender->hasPermission("kygekwhitelistkick.cmd.help"))
                $this->main()->getHelp($sender);
            else $sender->sendMessage(self::NO_PERM);
        } elseif (isset($args[0])) {
            switch ($args[0]) {
                case "help":
                    if ($sender->hasPermission("kygekwhitelistkick.cmd.help"))
                        $this->main()->getHelp($sender);
                    else $sender->sendMessage(self::NO_PERM);
                    break;
                case "off":
                    if ($sender->hasPermission("kygekwhitelistkick.cmd.off"))
                        $this->main()->disableWhitelistKick($sender);
                    else $sender->sendMessage(self::NO_PERM);
                    break;
                case "on":
                    if ($sender->hasPermission("kygekwhitelistkick.cmd.on"))
                        $this->main()->enableWhitelistKick($sender);
                    else $sender->sendMessage(self::NO_PERM);
                    break;
                case "set":
                    if ($sender->hasPermission("kygekwhitelistkick.cmd.set")) {
                        if (empty($args[1])) $this->main()->getSubcommandUsage($sender);
                        else {
                            unset($args[0]);
                            $this->main()->setKickReason(implode(" ", $args), $sender);
                        }
                    } else $sender->sendMessage(self::NO_PERM);
                    break;
                default:
                    if ($sender->hasPermission("kygekwhitelistkick.cmd.help"))
                        $this->main()->getHelp($sender);
                    else $sender->sendMessage(self::NO_PERM);
            }
        }
        return true;
    }

}
