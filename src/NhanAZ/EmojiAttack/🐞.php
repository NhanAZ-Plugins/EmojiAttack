<?php

declare(strict_types=1);

namespace NhanAZ\EmojiAttack;

use pocketmine\utils\Config as 📝;
use pocketmine\event\Listener as 👂;
use pocketmine\utils\TextFormat as 🌈;
use pocketmine\plugin\PluginBase as 🏠;
use pocketmine\event\server\DataPacketSendEvent as 🛳️;
use pocketmine\network\mcpe\protocol\TextPacket as 💬;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket as 📦;
use pocketmine\network\mcpe\protocol\types\command\raw\CommandRawData as 📋;
use function array_rand as 🎰;
use function preg_replace as 🔁;

define("✔️", true);
define("❌", false);

class 🐞 extends 🏠 implements 👂 {

	protected const ✈️ = 🌈::ESCAPE . "\u{3000}";
	protected 📝 $🛒;

	protected function onEnable(): void {
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		/** Mom, Look! It works! 😱 */
		$this->saveDefaultConfig();
		$this->saveResource("🛒.txt");
		$this->🛒 = new 📝($this->getDataFolder() . "🛒.txt", 📝::ENUM);
	}

	private function 🚰(): string {
		$🏪 = [];
		foreach ($this->🛒->getAll(✔️) as $emoji) {
			if (is_string($emoji) && $emoji !== "") {
				$🏪[] = $emoji;
			}
		}
		if ($🏪 === []) {
			return "";
		}
		return " " . $🏪[🎰($🏪)] . self::✈️;
	}

	private function 🎨(): string {
		if ($this->getConfig()->get("randomColor") !== ✔️) {
			return "";
		}
		$colors = $this->getConfig()->get("arrColor", ["§e", "§a", "§d", "§c", "§b"]);
		if (!is_array($colors)) {
			return "";
		}
		$valid = [];
		foreach ($colors as $color) {
			if (is_string($color) && $color !== "") {
				$valid[] = $color;
			}
		}
		return $valid === [] ? "" : $valid[🎰($valid)];
	}

	private function suffix(): string {
		$emoji = $this->🚰();
		return $emoji === "" ? "" : $this->🎨() . $emoji;
	}

	public function 🤔(string $📃): string {
		$🔥 = "/%*(([a-z0-9_]+\.)+[a-z0-9_]+)/i";
		$💧 = "%$1";
		return (🔁($🔥, $💧, $📃) ?? $📃) . $this->suffix();
	}

	private function decorateCommand(📦 $packet, int $index, object $command): void {
		if ($command instanceof 📋) {
			array_splice($packet->commandData, $index, 1, [new 📋(
				$command->getName(),
				$this->🤔($command->getDescription()),
				$command->getFlags(),
				$command->getPermission(),
				$command->getAliasEnumIndex(),
				$command->getChainedSubCommandDataIndexes(),
				$command->getOverloads()
			)]);
			return;
		}
		$publicFields = get_object_vars($command);
		$description = $publicFields['description'] ?? null;
		if (is_string($description)) {
			(new \ReflectionProperty($command, 'description'))->setValue($command, $this->🤔($description));
		}
	}

	public function 🚀(🛳️ $🎉): void {
		foreach ($🎉->getPackets() as $✨) {
			if ($✨ instanceof 💬) {
				switch ($✨->type) {
					case 💬::TYPE_POPUP:
					case 💬::TYPE_JUKEBOX_POPUP:
					case 💬::TYPE_TIP:
						break;
					case 💬::TYPE_TRANSLATION:
						$✨->message = $this->🤔($✨->message);
						break;
					default:
						$✨->message .= $this->suffix();
						break;
				}
			} elseif ($✨ instanceof 📦) {
				foreach ($✨->commandData as $📛 => $📅) {
					$this->decorateCommand($✨, $📛, $📅);
				}
			}
		}
	}
}
