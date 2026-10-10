<?php

declare(strict_types=1);

use NhanAZ\EmojiAttack\Main;
use NhanAZ\EmojiAttack\🐞;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\TextPacket;
use pocketmine\network\mcpe\protocol\types\command\raw\CommandRawData;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

$serverSource = $argv[1] ?? null;
if ($serverSource === null || !is_file($serverSource . '/vendor/autoload.php')) {
	throw new RuntimeException('Pass the path to an installed Axolotl-PM source checkout');
}
require $serverSource . '/vendor/autoload.php';
require_once __DIR__ . '/../src/NhanAZ/EmojiAttack/🐞.php';
require_once __DIR__ . '/../src/NhanAZ/EmojiAttack/Main.php';

$plugin = (new ReflectionClass(Main::class))->newInstanceWithoutConstructor();
if (!$plugin instanceof 🐞) {
	throw new RuntimeException('The manifest adapter must retain the original plugin class');
}

$scratch = sys_get_temp_dir() . '/emojiattack-packet-' . bin2hex(random_bytes(6));
if (!mkdir($scratch) && !is_dir($scratch)) {
	throw new RuntimeException('Cannot create test directory');
}
$configPath = $scratch . '/config.yml';
$emojiPath = $scratch . '/emoji.txt';
file_put_contents($configPath, "randomColor: false\narrColor: []\n");
file_put_contents($emojiPath, "🙂\n");

$configProperty = new ReflectionProperty(PluginBase::class, 'config');
$resourceProperty = new ReflectionProperty(🐞::class, '🛒');
$configProperty->setValue($plugin, new Config($configPath, Config::YAML));
$resourceProperty->setValue($plugin, new Config($emojiPath, Config::ENUM));

$text = TextPacket::raw('Hello');
$translation = TextPacket::translation('chat.type.text');
$popup = TextPacket::popup('Keep popup');
if (class_exists(CommandRawData::class)) {
	$command = new CommandRawData('test', 'Command description', 0, '', -1, [], []);
} else {
	$legacyClass = 'pocketmine\\network\\mcpe\\protocol\\types\\command\\CommandData';
	$command = new $legacyClass('test', 'Command description', 0, 0, null, []);
}
$commands = new AvailableCommandsPacket();
$commands->commandData = [$command];
$plugin->🚀(new DataPacketSendEvent([], [$text, $translation, $popup, $commands]));

if (!str_contains($text->message, 'Hello 🙂') || !str_contains($translation->message, '%chat.type.text 🙂')) {
	throw new RuntimeException('Text and translation suffixes were not preserved');
}
if ($popup->message !== 'Keep popup') {
	throw new RuntimeException('Popup text should remain untouched');
}
if (!str_contains($commands->commandData[0]->getDescription(), 'Command description 🙂')
	|| $commands->commandData[0]->getName() !== 'test') {
	throw new RuntimeException('Command description was not rebuilt correctly');
}

file_put_contents($configPath, "randomColor: true\narrColor: ['§e']\n");
$configProperty->setValue($plugin, new Config($configPath, Config::YAML));
$colored = TextPacket::raw('Color');
$plugin->🚀(new DataPacketSendEvent([], [$colored]));
if (!str_contains($colored->message, "Color§e 🙂")) {
	throw new RuntimeException('Configured color was not used');
}

file_put_contents($emojiPath, '');
$resourceProperty->setValue($plugin, new Config($emojiPath, Config::ENUM));
$empty = TextPacket::raw('Empty resources');
$plugin->🚀(new DataPacketSendEvent([], [$empty]));
if ($empty->message !== 'Empty resources') {
	throw new RuntimeException('Empty color and emoji lists should leave text unchanged');
}

echo "PASS: text, translation, popup, command and empty-resource packets\n";
