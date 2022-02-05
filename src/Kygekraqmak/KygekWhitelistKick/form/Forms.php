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
 * Copyright (C) 2020-2022 Kygekraqmak, KygekTeam
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 */

declare(strict_types=1);

namespace Kygekraqmak\KygekWhitelistKick\form;

use Kygekraqmak\KygekWhitelistKick\WhitelistKick;
use pocketmine\player\Player;
use Vecnavium\FormsUI\CustomForm;
use Vecnavium\FormsUI\SimpleForm;

class Forms {

    /*
     * Returns whether KygekWhitelistKick is enabled
     */
    public static function isEnabled() : bool {
        return (bool) WhitelistKick::getInstance()->getConfig()->get("enabled");
    }

    public static function mainForm(Player $player) {
        $form = new SimpleForm(function (Player $player, $data = null) {
            if ($data === null) return;
            switch ($data) {
                case 0:
                    self::enableDisableForm($player);
                    break;
                case 1:
                    self::setReasonForm($player);
                    break;
            }
        });

        $form->setTitle("KygekWhitelistKick");
        $form->setContent("Select options:");
        if ($player->hasPermission("kygekwhitelistkick.cmd." . (self::isEnabled() ? "off" : "on")) || $player->hasPermission("kygekwhitelistkick.cmd"))
            $form->addButton((self::isEnabled() ? "Disable" : "Enable") . " KygekWhitelistKick");
        if ($player->hasPermission("kygekwhitelistkick.cmd.set") || $player->hasPermission("kygekwhitelistkick.cmd"))
            $form->addButton("Set kick reason");
        $form->addButton("Exit");
        
        $player->sendForm($form);
    }

    private static function enableDisableForm(Player $player) {
        $form = new SimpleForm(function (Player $player, ?int $data = null) {
            if ($data === 0) {
                WhitelistKick::getInstance()->setConfig("enabled", !self::isEnabled());
                $msg = self::isEnabled() ? "enabled" : "disabled";
                self::resultForm($player, "Success", "Successfully " . $msg . " KygekWhitelistKick");
                return;
            }
            self::mainForm($player);
        });

        $form->setTitle((self::isEnabled() ? "Disable" : "Enable") . " KygekWhitelistKick");
        $form->setContent("Are you sure you want to " . (self::isEnabled() ? "disable" : "enable") . " KygekWhitelistKick?");
        $form->addButton("Yes");
        $form->addButton("Back");
        
        $player->sendForm($form);
    }

    private static function setReasonForm(Player $player) {
        $form = new CustomForm(function (Player $player, ?array $data = null) {
            if ($data === null) {
                self::mainForm($player);
                return;
            }
            if (empty($data[0])) {
                self::resultForm($player, "Error", "Please enter kick reason", true, "setReasonForm");
                return;
            }
            WhitelistKick::getInstance()->setConfig("reason", (string) $data[0]);
            self::resultForm($player, "Success", "Successfully changed kick reason");
        });

        $form->setTitle("Set kick reason");
        $form->addInput("Enter the kick reason you want to change:", "Enter here");
        
        $player->sendForm($form);
    }

    private static function resultForm(Player $player, string $title, string $content, bool $return = false, ?string $returnedMtd = null) {
        $form = new SimpleForm(function (Player $player, ?int $data = null) use ($return, $returnedMtd) {
            if ($return) call_user_func("self::$returnedMtd", $player);
        });

        $form->setTitle($title);
        $form->setContent($content);
        $form->addButton($return ? "Back" : "Ok");
        
        $player->sendForm($form);
    }

}
