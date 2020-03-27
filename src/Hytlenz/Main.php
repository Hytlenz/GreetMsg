<?php

 
namespace Hytlenz;

// Code by LentoKun.

use pocketmine\{Player, Server};
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\utils\Config;
use pocketmine\level\{Level, Position};
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\entity\{Effect, EffectInstance};

class Main extends PluginBase implements Listener{


	public function onEnable() {
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->saveDefaultConfig();
        }

	public function onJoin(PlayerJoinEvent $event){
                //Todo on config
                // settings:
                //   enable-voice-message: true
                //.  enable-effects: true
                //.  enable-title-and-subtitle: true
                //.  enable-greet-form: false
                //.  enable-welcome-message: true
                //.  enable-event-packet: true
                //. messages:
                //.   voice-message: ""
                //.   effects: BLINDNESS
                //.   title: ""
                //.   subtitle: ""
                //.   welcome-message: ""
                //.   event-packet: 1 [1 - Guardian Curse : 2 - Totem]
                //. forms:
                //.   form-type: Modal [Modal and Custom]
                //.   form-title: "Test Form"
                //.   form-content:
                //.      - "Welcome To Server"
                //.      - "Have a Nice Day!"
                //.   form-button-modal:
                //.      button1: ""
                //.      button2: ""
                $player = $event->getPlayer();  
	        $title = $this->getConfig()->getNested("welcome.title");
        	$title = str_replace("{player}", $this->player->getName(), $title);
        
        	$subtitle = $this->getConfig()->getNested("welcome.subtitle");
        	$subtitle = str_replace("{player}", $this->player->getName(), $subtitle);
        
        	$voice = $this->getConfig()->getNested("welcome.voice_msg");
        	$voice = str_replace("{player}", $this->player->getName(), $voice);
        
        	$welcome = $this->getConfig()->getNested("welcome.msg");
        	$welcome = str_replace("{player}", $this->player->getName(), $welcome);
        
        	$player->addTitle($title, $subtitle);
        	$player->sendTranslation($voice);
        	$player->sendMessage($welcome);
		
                $pk = new LevelEventPacket();
		$pk->evid = LevelEventPacket::EVENT_GUARDIAN_CURSE;
		$pk->data = 0;
		$pk->position = $this->player->asVector3();
		$this->player->dataPacket($pk);

		$effect = new EffectInstance(Effect::getEffect(Effect::BLINDNESS), 100, 0, false);
                $this->player->addEffect($effect);
	}
}
