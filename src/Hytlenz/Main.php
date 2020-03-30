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
use Hytlenz\forms\{ModalForm, SimpleForm};

class Main extends PluginBase implements Listener{

	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->saveResource("greetings.yml");
	    $this->greet = new Config($this->getDataFolder() . "greetings.yml", Config::YAML);
    }

	public function onJoin(PlayerJoinEvent $event){
		$player = $event->getPlayer();
		
        if($this->greet->getNested("form-message.enable", true)){
        	if($this->greet->getNested("form-message.form-type") == "Modal"){
        	    $this->modalForm($player);
            } else if ($this->greet->getNested("form-message.form-type") == "Simple"){
            	$this->simpleForm($player);
            }
        } else {
        	$this->greetings($player);
        }   
        
	}
	public function modalForm($player){
        $form = new ModalForm(function (Player $player, $data) {
            if ($data == 0) {
                $this->greetings($player);
            }
            if ($data == 1) {
                $this->greetings($player);
            }
        });
        $form->setTitle($this->tags($player, $this->greet->getNested("form-message.form-title")));
        $form->setContent($this->tags($player, $this->greet->getNested("form-message.content")));
        $form->setButton1($this->tags($player, $this->greet->getNested("form-message.modal.button1")));
        $form->setButton2($this->tags($player, $this->greet->getNested("form-message.modal.button2")));
        $form->sendToPlayer($player);
	}
	public function simpleForm($player){
		$form = new SimpleForm(function (Player $player, $data) {
            if ($data == 0) {
                $this->greetings($player);
            }
            if ($data == 1) {
            	$this->greetings($player);
            }
        });
        $form->setTitle($this->tags($player, $this->greet->getNested("form-message.form-title")));
        $form->setContent($this->tags($player, $this->greet->getNested("form-message.content")));
        $form->addButton($this->tags($player, $this->greet->getNested("form-message.simple.button")), $this->greet->getNested("form-message.simple.image-type"), $this->greet->getNested("form-message.simple.image-path"));
        $form->sendToPlayer($player);
	}
	public function greetings($player){
		if($this->greet->getNested("welcome-message.enable", true)){
			$player->sendMessage($this->tags($player, $this->greet->getNested("welcome-message.message")));
		}
		if($this->greet->getNested("header-message.enable", true)){
			$player->addTitle($this->tags($player, $this->greet->getNested("header-message.title")), $this->tags($player, $this->greet->getNested("header-message.subtitle")));
		}
		if($this->greet->getNested("effect.enable", true)){
				$effectID = Effect::getEffect((int) $this->greet->getNested("effect.side-effect"));
				$duration = (int) $this->greet->getNested("effect.duration");
				$amplifier = (int) $this->greet->getNested("effect.amplifier");
				$visible = $this->greet->getNested("effect.visible");
				$player->addEffect(new EffectInstance($effectID, $duration * 20, $amplifier, $visible));
        }
        if($this->greet->getNested("event-packet.enable", true)){
        	    $pk = new LevelEventPacket();
                $pk->evid = LevelEventPacket::EVENT_GUARDIAN_CURSE;
                $pk->data = 0;
                $pk->position = $player->asVector3();
                $player->dataPacket($pk);
        }      
    }
	public function tags(Player $player, string $string) : string {
		$string = str_replace("@player", $player->getName(), $string);
		$string = str_replace("&", "§", $string);
		$string = str_replace("#", "\n", $string);
		return $string;
	}
}