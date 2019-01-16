<?php

 
namespace Hytlenz;

// Code by LentoKun.

use pocketmine\{Player, Server};
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\utils\{Config, TextFormat as TF};

use Hytlenz\SendTask;

class Main extends PluginBase implements Listener{

private $prefix = "[GreetMsg]";

	public function onEnable() {
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getServer()->getLogger()->info(TF::YELLOW . $this->prefix . TF::GREEN . " GreetMsg Enabled by Hytlenz");
		$this->saveDefaultConfig();
  }

	public function onDisable() {
		$this->getServer()->getLogger()->info(TF::YELLOW . $this->prefix . TF::RED . " GreetMsg Enabled by Hytlenz");
	}
	
	public function onJoin(PlayerJoinEvent $event){
		$joinTask = new SendTask($this, $event->getPlayer());
		$this->getScheduler()->scheduleDelayedTask($joinTask, 20);
	}
}
