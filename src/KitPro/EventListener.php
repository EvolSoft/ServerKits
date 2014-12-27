<?php

/*
 * KitPro (v1.1) by EvolSoft
 * Developer: EvolSoft (Flavius12)
 * Website: http://www.evolsoft.tk
 * Date: 27/12/2014 04:00 PM (UTC)
 * Copyright & License: (C) 2014 EvolSoft
 * Licensed under MIT (https://github.com/EvolSoft/KitPro/blob/master/LICENSE)
 */

namespace KitPro;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\event\Listener;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\tile\Sign;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\permission\Permission;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
//MassiveEconomy plugin API
use MassiveEconomy\MassiveEconomyAPI;

class EventListener extends PluginBase implements Listener{
	
	public function __construct(Main $plugin){
        $this->plugin = $plugin;
    }
    
    public function onPlayerJoin(PlayerJoinEvent $event){
    	$player = $event->getPlayer();
    	//Initialize kit permissions
    	$this->plugin->initializeKitsPermissions();
    	$kit = $this->plugin->getDefaultKit();
    	//Get if player has joined for first time
    	if($this->plugin->hasJoinedFirstTime($player)){
    		$this->plugin->registerFirstJoin($player);
    		//Check use-permissions
    		if($this->plugin->getUsePermissions()){
    			if($player->hasPermission("kitpro.kit." . strtolower($kit))){
    				$status = $this->plugin->giveKit($player, $kit);
    				if($status == 0){
    					$player->sendMessage($this->plugin->translateColors("&", Main::PREFIX . $this->plugin->getKitReceivedMessage($player, $kit)));
    				}elseif($status == 1){
    					$player->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&cKit not found."));
    				}elseif($status == 2){
    					$player->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&cYou can't get the kit because you are in creative"));
    				}elseif($status == 3){
    					$player->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&cYou don't have enough money to get the kit"));
    				}
    			}
    		}else{
    			$status = $this->plugin->giveKit($player, $kit);
    			if($status == 0){
    				$player->sendMessage($this->plugin->translateColors("&", Main::PREFIX . $this->plugin->getKitReceivedMessage($player, $kit)));
    			}elseif($status == 1){
    				$player->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&cKit not found."));
    			}elseif($status == 2){
    				$player->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&cYou can't get the kit because you are in creative"));
    			}elseif($status == 3){
    				$player->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&cYou don't have enough money to get the kit"));
    			}
    		}
    	}
    }
    
    public function onSignInteract(PlayerInteractEvent $event){
    	//Check if Kit sign usage is allowed
    	if($this->plugin->getEnableSigns()){
    		//Checking Permissions
    		if($event->getPlayer()->hasPermission("kitpro.use-sign") == true){
    			if($event->getBlock()->getID() == 323 || $event->getBlock()->getID() == 63 || $event->getBlock()->getID() == 68){
    				$sign = $event->getPlayer()->getLevel()->getTile($event->getBlock());
    				if($sign instanceof Sign){
    					//Initialize vars
    					$txtsign = $sign->getText();
    					$lvl = $event->getPlayer()->getLevel()->getName();
    					$sx = $sign->x;
    					$sy = $sign->y;
    					$sz = $sign->z;
    					if($txtsign[0] == "[KitPro]"){
    						//Kit Sign
    						$player = $event->getPlayer();
    						$kit = $txtsign[1];
    						//Check use-permissions
    						if($this->plugin->getUsePermissions()){
    							if($player->hasPermission("kitpro.kit." . strtolower($kit))){
    								$status = $this->plugin->giveKit($player, $kit);
    								if($status == 0){
    									$player->sendMessage($this->plugin->translateColors("&", Main::PREFIX . $this->plugin->getKitReceivedMessage($player, $kit)));
    								}elseif($status == 1){
    									$player->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&cKit not found."));
    								}elseif($status == 2){
    									$player->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&cYou can't get the kit because you are in creative"));
    								}elseif($status == 3){
    									$player->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&cYou don't have enough money to get the kit"));
    								}
    							}
    						}else{
    							$status = $this->plugin->giveKit($player, $kit);
    							if($status == 0){
    								$player->sendMessage($this->plugin->translateColors("&", Main::PREFIX . $this->plugin->getKitReceivedMessage($player, $kit)));
    							}elseif($status == 1){
    								$player->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&cKit not found."));
    							}elseif($status == 2){
    								$player->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&cYou can't get the kit because you are in creative"));
    							}elseif($status == 3){
    								$player->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&cYou don't have enough money to get the kit"));
    							}
    						}
    					}
    				}
    			}
    		}
    	}
    }
    
    public function onSignCreate(SignChangeEvent $event){
    	//Check if Kit sign usage is allowed
    	if($this->plugin->getEnableSigns()){
    		//Checking Permissions
    		if($event->getPlayer()->hasPermission("kitpro.create-sign") == true){
    			if($event->getBlock()->getID() == 323 || $event->getBlock()->getID() == 63 || $event->getBlock()->getID() == 68){
    				$sign = $event->getPlayer()->getLevel()->getTile($event->getBlock());
    				if($sign instanceof Sign){
    					$line0 = $event->getLine(0);
    					$line1 = $event->getLine(1);
    					if($line0=='[KitPro]'){
    						//Check if a kit is specified
    						if(empty($line1) !== true){
    							//Check if the kit exists
    							if($this->plugin->KitExists($line1)){
    								$price = $this->plugin->getKitPrice($line1);
    								$symbol = MassiveEconomyAPI::getInstance()->getMoneySymbol();
    								if($price > 0){
    									$event->setLine(2, $price . $symbol);
    								}
    								$event->getPlayer()->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&aKit sign created"));
    							}else{
    								$event->getPlayer()->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&cCan't create sign: Kit not found."));
    							}
    						}else{
    							$event->getPlayer()->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&cCan't create sign: You must specify a kit."));
    						}
    					}
    				}
    			}
    		}
    	}
    }
	
}
?>
