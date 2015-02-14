<?php

/*
 * ServerKits (v1.3) by EvolSoft
 * Developer: EvolSoft (Flavius12)
 * Website: http://www.evolsoft.tk
 * Date: 14/02/2015 12:19 AM (UTC)
 * Copyright & License: (C) 2014-2015 EvolSoft
 * Licensed under MIT (https://github.com/EvolSoft/ServerKits/blob/master/LICENSE)
 */

namespace ServerKits;

use pocketmine\Player;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginBase;
use pocketmine\permission\Permission;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use pocketmine\item\Item;
use pocketmine\command\ConsoleCommandSender;
//MassiveEconomy plugin API
use MassiveEconomy\MassiveEconomyAPI;

class Main extends PluginBase{
    
	//About Plugin Const
	const PRODUCER = "EvolSoft";
	const VERSION = "1.3";
	const MAIN_WEBSITE = "http://www.evolsoft.tk";
	//Other Const
	//Prefix
	const PREFIX = "&7[&cServer&4Kits&7] ";
	
	public $cfg;
	
	public $economy;
	
	public function translateColors($symbol, $message){
	
		$message = str_replace($symbol."0", TextFormat::BLACK, $message);
		$message = str_replace($symbol."1", TextFormat::DARK_BLUE, $message);
		$message = str_replace($symbol."2", TextFormat::DARK_GREEN, $message);
		$message = str_replace($symbol."3", TextFormat::DARK_AQUA, $message);
		$message = str_replace($symbol."4", TextFormat::DARK_RED, $message);
		$message = str_replace($symbol."5", TextFormat::DARK_PURPLE, $message);
		$message = str_replace($symbol."6", TextFormat::GOLD, $message);
		$message = str_replace($symbol."7", TextFormat::GRAY, $message);
		$message = str_replace($symbol."8", TextFormat::DARK_GRAY, $message);
		$message = str_replace($symbol."9", TextFormat::BLUE, $message);
		$message = str_replace($symbol."a", TextFormat::GREEN, $message);
		$message = str_replace($symbol."b", TextFormat::AQUA, $message);
		$message = str_replace($symbol."c", TextFormat::RED, $message);
		$message = str_replace($symbol."d", TextFormat::LIGHT_PURPLE, $message);
		$message = str_replace($symbol."e", TextFormat::YELLOW, $message);
		$message = str_replace($symbol."f", TextFormat::WHITE, $message);
	
		$message = str_replace($symbol."k", TextFormat::OBFUSCATED, $message);
		$message = str_replace($symbol."l", TextFormat::BOLD, $message);
		$message = str_replace($symbol."m", TextFormat::STRIKETHROUGH, $message);
		$message = str_replace($symbol."n", TextFormat::UNDERLINE, $message);
		$message = str_replace($symbol."o", TextFormat::ITALIC, $message);
		$message = str_replace($symbol."r", TextFormat::RESET, $message);
	
		return $message;
	}
	
	public function registerFirstJoin(Player $player){
		@mkdir($this->getDataFolder() . "data/");
		$tmp = new Config($this->getDataFolder() . "data/" . strtolower($player->getName() . ".dat"));
		$tmp->save();
	}
	
	public function hasJoinedFirstTime(Player $player){
		if(file_exists($this->getDataFolder() . "data/" . strtolower($player->getName() . ".dat"))){
			return false;
		}else{
			return true;
		}
	}
	
    public function onEnable(){
    	@mkdir($this->getDataFolder());
    	$this->saveDefaultConfig();
    	$this->saveResource("kits.yml");
    	$this->cfg = $this->getConfig()->getAll();
    	$this->getCommand("serverkits")->setExecutor(new Commands\Commands($this));
    	$this->getCommand("kit")->setExecutor(new Commands\Kit($this));
    	$this->initializeKitsPermissions();
    	$this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
    	//Check if MassiveEconomy is installed
    	if($this->getServer()->getPluginManager()->getPlugin("MassiveEconomy") != false){
    		//Checking if MassiveEconomyAPI version is compatible
    		if(MassiveEconomyAPI::getInstance()->getAPIVersion() == "0.90"){
    			$this->economy = true;
    			Server::getInstance()->getLogger()->info($this->translateColors("&", Main::PREFIX . "&aEconomy support enabled!"));
    		}else{
    			$this->economy = false;
    			Server::getInstance()->getLogger()->info($this->translateColors("&", Main::PREFIX . "&cEconomy support not available. Please use MassiveEconomy (API v0.90)"));
    		}
    	}else{
    		$this->economy = false;
    		Server::getInstance()->getLogger()->info($this->translateColors("&", Main::PREFIX . "&cEconomy support not available. Please install MassiveEconomy to enable Economy support"));
    	}
    }
    //Config Functions   
    public function isDefaultKitEnabled(){
    	$tmp = $this->getConfig()->getAll();
    	return $tmp["enable-default-kit"];
    }
    
    public function getDefaultKit(){
    	$tmp = $this->getConfig()->getAll();
    	return $tmp["default-kit"];
    }
    
    public function getUsePermissions(){
    	$tmp = $this->getConfig()->getAll();
    	return $tmp["use-permissions"];
    }
    
    public function getEnableSigns(){
    	$tmp = $this->getConfig()->getAll();
    	return $tmp["enable-signs"];
    }
    
    public function getKitReceivedMessage(Player $player, $kit){
    	$tmp = $this->getConfig()->getAll();
    	$format = $tmp["kit-received-message"];
    	//Check if the kit exists
    	if($this->KitExists($kit)){
    		$format = str_replace("{KIT}", $kit, $format);
    	}else{
    		$format = str_replace("{KIT}", "", $format);
    	}
    	$format = str_replace("{KIT_NAME}", $this->getKitName($kit), $format);
		$format = str_replace("{PLAYER}", $player->getName(), $format);
		$format = str_replace("{PRICE}", $this->getKitPrice($kit), $format);
		return $format;
    }
    //Kits Config functions
    public function initializeKitsPermissions(){
    	$tmp = new Config($this->getDataFolder() . "kits.yml");
    	$tmp = $tmp->getAll();
    	$kits = $this->getAllKits();
    	for($i = 0; $i < count($kits); $i++){
    		$permission = new Permission("serverkits.kit." . strtolower($kits[$i]), "ServerKits " . $kits[$i] . " kit permission.");
    		Server::getInstance()->getPluginManager()->addPermission($permission);
    	}
    }
    
    public function KitExists($kit){
    	$tmp = new Config($this->getDataFolder() . "kits.yml");
    	$tmp = $tmp->getAll();
    	return isset($tmp[$kit]);
    }
    
    public function getAllKits(){
    	$tmp = new Config($this->getDataFolder() . "kits.yml");
    	$tmp = $tmp->getAll();
    	return array_keys($tmp);
    }
    
    public function getKitName($kit){
    	$tmp = new Config($this->getDataFolder() . "kits.yml");
    	$tmp = $tmp->getAll();
    	if($this->KitExists($kit) && isset($tmp[$kit]["name"])){
    		return $tmp[$kit]["name"];
    	}else{
    		return false;
    	}
    }
    
    public function getKitPrice($kit){
    	$tmp = new Config($this->getDataFolder() . "kits.yml");
    	$tmp = $tmp->getAll();
    	if($this->KitExists($kit) && isset($tmp[$kit]["price"])){
    		return $tmp[$kit]["price"];
    	}else{
    		return false;
    	}
    }

    public function parseKitItems(Player $player, $kit){
    	$tmp = new Config($this->getDataFolder() . "kits.yml");
    	$tmp = $tmp->getAll();
    	//Check if kit exists
    	if($this->KitExists($kit)){
    		//Check if items are set
    		if(isset($tmp[$kit]["items"])){
    			for($i = 0; $i < count($tmp[$kit]["items"]); $i++){
    				$item_parse = explode(" ", $tmp[$kit]["items"][$i]);
    				$item = $item_parse[0];
    				$amount = $item_parse[1];
    				for($a = 0; $a < $amount; $a++){
    					$player->getInventory()->addItem(Item::fromString($item));
    				}
    			}
    			return 2;
    		}else{
    			return 1;
    		}
    	}else{
    		return 0;
    	}
    }
    
    public function parseKitCommands(Player $player, $kit){
    	$tmp = new Config($this->getDataFolder() . "kits.yml");
    	$tmp = $tmp->getAll();
        //Check if kit exists
    	if($this->KitExists($kit)){
    		//Check if commands are set
    		if(isset($tmp[$kit]["commands"])){
    			for($i = 0; $i < count($tmp[$kit]["commands"]); $i++){
    				$console = new ConsoleCommandSender();
    				$this->getServer()->dispatchCommand($console, $this->translateColors("&", $this->getFormattedCommand($tmp[$kit]["commands"][$i], $player, $kit)));
    			}
    			return 2;
    		}else{
    			return 1;
    		}
    	}else{
    		return 0;
    	}
    }
    
    public function getFormattedCommand($command, Player $player, $kit){
    	//Check if the kit exists
    	if($this->KitExists($kit)){
    		$command = str_replace("{KIT}", $kit, $command);
    	}else{
    		$command = str_replace("{KIT}", "", $command);
    	}
    	$command = str_replace("{KIT_NAME}", $this->getKitName($kit), $command);
    	$command = str_replace("{PLAYER}", $player->getName(), $command);
    	$command = str_replace("{PRICE}", $this->getKitPrice($kit), $command);
    	return $command;
    }
    
    public function giveKit(Player $player, $kit){
    	$tmp = new Config($this->getDataFolder() . "kits.yml");
    	$tmp = $tmp->getAll();
    	//Check if kit exists
    	if($this->KitExists($kit)){
    		//Check if player is in creative
    		if($player->isCreative()){
    			return 2;
    		}else{
    			//Checking Price & Economy support
    			if($this->economy == true && $this->getKitPrice($kit) != false && $this->getKitPrice($kit) > 0){
    				$result = MassiveEconomyAPI::getInstance()->takeMoney($player->getName(), $this->getKitPrice($kit));
    				if($result == 2){
    					//Parse Items
    					$this->parseKitItems($player, $kit);
    					//Parse Commands
    					$this->parseKitCommands($player, $kit);
    					return 0; //Success!
    				}elseif($result == 1){
    					return 3; //Failed: Not enough money
    				}
    			}else{
    				//Parse Items
    				$this->parseKitItems($player, $kit);
    				//Parse Commands
    				$this->parseKitCommands($player, $kit);
    				return 0;
    			}
    		}
    	}else{
    		return 1;
    	}
    }
    
}
?>
