<?php

declare(strict_types=1);

namespace Vecnavium\FormsUI;

use Closure;
use pocketmine\form\Form;
use pocketmine\player\Player;

final class CustomForm implements Form {
	public function __construct(Closure $handler) {}

	public function jsonSerialize() : mixed { return []; }

	public function handleResponse(Player $player, $data) : void {}

	public function setTitle(string $title) : void {}

	public function addInput(string $text, string $placeholder = "", string $default = "") : void {}
}
