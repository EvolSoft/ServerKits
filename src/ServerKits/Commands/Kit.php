<?php

/*
 * ServerKits (v1.3) by EvolSoft
 * Developer: EvolSoft (Flavius12)
 * Website: http://www.evolsoft.tk
 * Date: 29/12/2014 09:43 AM (UTC)
 * Copyright & License: (C) 2014-2015 EvolSoft
 * Licensed under MIT (https://github.com/EvolSoft/ServerKits/blob/master/LICENSE)
 */

namespace ServerKits\Commands;

use pocketmine\plugin\PluginBase;
use pocketmine\permission\Permission;
use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

use ServerKits\Main;
use ServerKits\EventListener;

class Kit extends PluginBase implements CommandExecutor{

	public function __construct(Main $plugin){
        $this->plugin = $plugin;
    }
    
    public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) {
    	$fcmd = strtolower($cmd->getName());
    	switch($fcmd){
    		case "kit":
    			//Player Sender
    			if($sender instanceof Player){
    				if($sender->hasPermission("serverkits.commands.kit")){
    					//Initialize kit permissions
    					$this->plugin->initializeKitsPermissions();
    					//Check if use-permissions is enabled
    					if($this->plugin->getUsePermissions()){
    						if(isset($args[0])){
    							//Check if kit exists
    							if($this->plugin->KitExists($args[0])){
    								//Check if player has kit permissions
    								if($sender->hasPermission("serverkits.kit." . strtolower($args[0]))){
    									$status = $this->plugin->giveKit($sender, $args[0]);
    									if($status == 0){
    										$sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . $this->plugin->getKitReceivedMessage($sender, $args[0])));
    									}elseif($status == 1){
    										$sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&cKit not found."));
    									}elseif($status == 2){
    										$sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&cYou can't get the kit because you are in creative"));
    									}elseif($status == 3){
    										$sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&cYou don't have enough money to get the kit"));
    									}
    								}else{
    									$sender->sendMessage($this->plugin->translateColors("&", "&cYou don't have permissions to get this kit"));
    								}
    							}else{
    								$sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&cKit not found."));
    							}
    						}else{
    							$sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&4Available Kits:"));
    							$kits = $this->plugin->getAllKits();
    							$result = "";
    							if($sender->hasPermission("serverkits.kit." . strtolower($kits[0]))){
    								$result = $kits[0];
    							}
    							//Count all kits
    							for($i = 1; $i < count($kits); $i++){
    								if($sender->hasPermission("serverkits.kit." . strtolower($kits[$i]))){
    									$result = $result . ", " . $kits[$i];
    								}
    							}
    							if(isset($result{0})){
    								if($result{0} == ","){
    									$result = substr($result, 2);
    								}
    							}
    							$sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . $result));
    						}
    					}else{
    						if(isset($args[0])){
    							//Check if kit exists
    							if($this->plugin->KitExists($args[0])){
    								$status = $this->plugin->giveKit($sender, $args[0]);
    								if($status == 0){
    									$sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . $this->plugin->getKitReceivedMessage($sender, $args[0])));
    								}elseif($status == 1){
    									$sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&cKit not found."));
    								}elseif($status == 2){
    									$sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&cYou can't get the kit because you are in creative"));
    								}elseif($status == 3){
    									$sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&cYou don't have enough money to get the kit"));
    								}
    							}else{
    								$sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&cKit not found."));
    							}
    						}else{
    							$sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&4Available Kits:"));
    							$kits = $this->plugin->getAllKits();
    							$result = $kits[0];
    							//Count all kits
    							for($i = 1; $i < count($kits); $i++){
    								$result = $result . ", " . $kits[$i];
    							}
    							$sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . $result));
    						}
    					}
    					break;
    				}else{
    					$sender->sendMessage($this->plugin->translateColors("&", "&cYou don't have permissions to use this command"));
    					break;
    				}
    			}else{
    				$sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&cYou can only perform this command as a player"));
    				break;
    			}
    	}
    }
}
