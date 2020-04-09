<?php

/*
 *
 *              _                             _        ______             _
 *     /\      | |                           | |      |  ____|           (_)
 *    /  \     | | __   _ __ ___      __ _   | |      | |__       __ _    _    _ __    _   _    ____
 *   / /\ \    | |/ /  | '_ ` _ \    / _` |  | |      |  __|     / _` |  | |  | '__|  | | | |  |_  /
 *  / ____ \   |   <   | | | | | |  | (_| |  | |      | |       | (_| |  | |  | |     | |_| |   / /
 * /_/    \_\  |_|\_\  |_| |_| |_|   \__,_|  |_|      |_|        \__,_|  |_|  |_|      \__,_|  /___|
 *
 * Discord: akmal#7191
 * GitHub: https://github.com/AkmalFairuz
 *
 */

namespace AkmalFairuz\McMMO\entity;

use AkmalFairuz\McMMO\Main;
use pocketmine\entity\Human;
use pocketmine\network\mcpe\protocol\SetActorDataPacket;
use pocketmine\Player;

class FloatingText extends Human
{
    public $updateTick = 0;

    public $type = 0;

    public function initEntity(): void
    {
        parent::initEntity(); // TODO: Change the autogenerated stub
        $this->setScale(0.0001);
        $this->updateTick = 0;
        $this->type = $this->namedtag->getInt("type");
    }

    public function updateMovement(bool $teleport = false): void
    {
    }

    public function onUpdate(int $currentTick): bool
    {
        parent::onUpdate($currentTick);
        $this->updateTick++;
        if($this->updateTick == 20) {
            $this->updateTick = 0;
            $a = ["Lumberjack", "Farmer", "Excavation", "Miner", "Killer", "Combat", "Builder", "Consumer", "Archer", "Lawn Mower"];
            $l = "";
            $i = 0;
            foreach(Main::getInstance()->getAll($this->type) as $k => $o) {
                if($i == 20) break;
                $i++;
                $l .= $i . ") ".$k." : ".$o;
            }
            $this->setNameTag("MCMMO Leaderboard\n".$a[$this->type] . "\n\n".$l);
            foreach ($this->getViewers() as $player) {
                $this->sendNameTag($player);
            }
        }
        return true;
    }

    public function sendNameTag(Player $player): void {
        $pk = new SetActorDataPacket();
        $pk->entityRuntimeId = $this->getId();
        $pk->metadata = [self::DATA_NAMETAG => [self::DATA_TYPE_STRING, $this->getNameTag()]];
        $player->dataPacket($pk);
    }
}