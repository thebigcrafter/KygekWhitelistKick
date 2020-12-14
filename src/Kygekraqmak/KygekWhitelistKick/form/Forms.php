<?php

declare(strict_types=1);

namespace Kygekraqmak\KygekWhitelistKick\form;

use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\SimpleForm;
use Kygekraqmak\KygekWhitelistKick\WhitelistKick;
use pocketmine\Player;

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
        if ($player->hasPermission("kygekwhitelistkick.cmd." . (self::isEnabled() ? "off" : "on")))
            $form->addButton((self::isEnabled() ? "Disable" : "Enable") . " KygekWhitelistKick");
        if ($player->hasPermission("kygekwhitelistkick.cmd.set"))
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