<?php

namespace Hytlenz;

//Code by LentoKun.

use pocketmine\{Player, Server};
use pocketmine\scheduler\Task;
use pocketmine\event\Listener;

use pocketmine\math\Vector3;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\network\mcpe\protocol\LevelEventPacket;

use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;

use Hytlenz\Main;

class SendTask extends Task implements Listener{

	public function __construct(Main $main, Player $player){
		$this->main = $main;
		$this->player = $player;
	}

	public function onRun($currentTick){
		$title = $this->main->getConfig()->get("welcome.title");
        	$title = str_replace("{player}", $this->player->getName(), $title);
        
        	$subtitle = $this->main->getConfig()->get("welcome.subtitle");
        	$subtitle = str_replace("{player}", $this->player->getName(), $subtitle);
        
        	$voice = $this->main->getConfig()->get("welcome.voice_msg");
        	$voice = str_replace("{player}", $this->player->getName(), $voice);
        
        	$welcome = $this->main->getConfig()->get("welcome.msg");
        	$welcome = str_replace("{player}", $this->player->getName(), $welcome);
        
        	$this->player->addTitle($title, $subtitle);
        	$this->player->sendTranslation($voice);
        	$this->player->sendMessage($welcome);
		
                $pk = new LevelEventPacket();
		$pk->evid = LevelEventPacket::EVENT_GUARDIAN_CURSE;
		$pk->data = 0;
		$pk->position = $this->player->asVector3();
		$this->player->dataPacket($pk);

		$effect = new EffectInstance(Effect::getEffect(Effect::BLINDNESS), 100, 0, false);
                $this->player->addEffect($effect);
            
	}
} 
