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

declare(strict_types=1);


namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>


use pocketmine\network\mcpe\handler\SessionHandler;

class SetTitlePacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::SET_TITLE_PACKET;

	public const TYPE_CLEAR_TITLE = 0;
	public const TYPE_RESET_TITLE = 1;
	public const TYPE_SET_TITLE = 2;
	public const TYPE_SET_SUBTITLE = 3;
	public const TYPE_SET_ACTIONBAR_MESSAGE = 4;
	public const TYPE_SET_ANIMATION_TIMES = 5;

	/** @var int */
	public $type;
	/** @var string */
	public $text = "";
	/** @var int */
	public $fadeInTime = 0;
	/** @var int */
	public $stayTime = 0;
	/** @var int */
	public $fadeOutTime = 0;

	protected function decodePayload() : void{
		$this->type = $this->getVarInt();
		$this->text = $this->getString();
		$this->fadeInTime = $this->getVarInt();
		$this->stayTime = $this->getVarInt();
		$this->fadeOutTime = $this->getVarInt();
	}

	protected function encodePayload() : void{
		$this->putVarInt($this->type);
		$this->putString($this->text);
		$this->putVarInt($this->fadeInTime);
		$this->putVarInt($this->stayTime);
		$this->putVarInt($this->fadeOutTime);
	}

	public function handle(SessionHandler $handler) : bool{
		return $handler->handleSetTitle($this);
	}

	private static function type(int $type) : self{
		$result = new self;
		$result->type = $type;
		return $result;
	}

	private static function text(int $type, string $text) : self{
		$result = self::type($type);
		$result->text = $text;
		return $result;
	}

	public static function title(string $text) : self{
		return self::text(self::TYPE_SET_TITLE, $text);
	}

	public static function subtitle(string $text) : self{
		return self::text(self::TYPE_SET_SUBTITLE, $text);
	}

	public static function actionBarMessage(string $text) : self{
		return self::text(self::TYPE_SET_ACTIONBAR_MESSAGE, $text);
	}

	public static function clearTitle() : self{
		return self::type(self::TYPE_CLEAR_TITLE);
	}

	public static function resetTitleOptions() : self{
		return self::type(self::TYPE_RESET_TITLE);
	}

	public static function setAnimationTimes(int $fadeIn, int $stay, int $fadeOut) : self{
		$result = self::type(self::TYPE_SET_ANIMATION_TIMES);
		$result->fadeInTime = $fadeIn;
		$result->stayTime = $stay;
		$result->fadeOutTime = $fadeOut;
		return $result;
	}
}
