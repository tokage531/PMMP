<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

namespace pocketmine\command\defaults;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class GamemodeCommand extends VanillaCommand{

	public function __construct($name){
		parent::__construct(
			$name,
			"指定プレイヤーのゲームモードを変更します",
			"/gamemode モード プレイヤー"
		);
		$this->setPermission("pocketmine.command.gamemode");
	}

	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(!$this->testPermission($sender)){
			return \true;
		}

		if(\count($args) === 0){
			$sender->sendMessage(TextFormat::RED . "使い方: " . $this->usageMessage);

			return \false;
		}

		$gameMode = Server::getGamemodeFromString($args[0]);

		if($gameMode === -1){
			$sender->sendMessage("正しいゲームモードを指定してください");

			return \true;
		}

		$target = $sender;
		if(isset($args[1])){
			$target = $sender->getServer()->getPlayer($args[1]);
			if($target === \null){
				$sender->sendMessage("プレイヤー(" . $args[1] . ")が見つかりませんでした");

				return \true;
			}
		}elseif(!($sender instanceof Player)){
			$sender->sendMessage(TextFormat::RED . "使い方: " . $this->usageMessage);

			return \true;
		}

		if($gameMode !== $target->getGamemode()){
			$target->setGamemode($gameMode);
			if($gameMode !== $target->getGamemode()){
				$sender->sendMessage("プレイヤー(" . $target->getName() . ")のゲームモードが変更出来ませんでした");
			}else{
				if($target === $sender){
					$sender->sendMessage("ゲームモードを " . \strtolower(Server::getGamemodeString($gameMode)) . " に変更しました");
				}else{
					$sender->sendMessage($target->getName() . " のゲームモードを " . \strtolower(Server::getGamemodeString($gameMode)) . " に変更しました");
				}
			}
		}else{
			$sender->sendMessage($target->getName() . " は既に " . \strtolower(Server::getGamemodeString($gameMode)) . " です");

			return \true;
		}

		return \true;
	}
}