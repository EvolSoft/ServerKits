<?php

/*
 * KitPro (v1.1) by EvolSoft
 * Developer: EvolSoft (Flavius12)
 * Website: http://www.evolsoft.tk
 * Date: 27/12/2014 04:00 PM (UTC)
 * Copyright & License: (C) 2014 EvolSoft
 * Licensed under MIT (https://github.com/EvolSoft/KitPro/blob/master/LICENSE)
 */

namespace KitPro\Commands;

use pocketmine\plugin\PluginBase;
use pocketmine\permission\Permission;
use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

use KitPro\Main;
use KitPro\EventListener;

class Commands extends PluginBase implements CommandExecutor{

	public function __construct(Main $plugin){
        $this->plugin = $plugin;
    }
    
    public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) {
    	$fcmd = strtolower($cmd->getName());
    	switch($fcmd){
    		case "kitpro":
    			if(isset($args[0])){
    				$args[0] = strtolower($args[0]);
    				if($args[0]=="help"){
    					if($sender->hasPermission("kitpro.commands.help")){
    						$sender->sendMessage($this->plugin->translateColors("&", "&7<<>> &cAvailable Commands &7<<>>"));
    						$sender->sendMessage($this->plugin->translateColors("&", "&c/kitpro info &7<>&a Show info about this plugin"));
    						$sender->sendMessage($this->plugin->translateColors("&", "&c/kitpro help &7<>&a Show help about this plugin"));
    						$sender->sendMessage($this->plugin->translateColors("&", "&c/kitpro reload &7<>&a Reload the config"));
    						$sender->sendMessage($this->plugin->translateColors("&", "&c/kit &7<>&a Get a kit"));
    						break;
    					}else{
    						$sender->sendMessage($this->plugin->translateColors("&", "&cYou don't have permissions to use this command"));
    						break;
    					}
    				}elseif($args[0]=="info"){
    					if($sender->hasPermission("kitpro.commands.info")){
    						$sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&7KitPro &av" . Main::VERSION . " &7developed by&a " . Main::PRODUCER));
    						$sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&7Website &a" . Main::MAIN_WEBSITE));
    				        break;
    					}else{
    						$sender->sendMessage($this->plugin->translateColors("&", "&cYou don't have permissions to use this command"));
    						break;
    					}
    				}elseif($args[0]=="reload"){
    					if($sender->hasPermission("kitpro.commands.reload")){
    						$this->plugin->reloadConfig();
    						$sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&aConfiguration Reloaded."));
    				        break;
    					}else{
    						$sender->sendMessage($this->plugin->translateColors("&", "&cYou don't have permissions to use this command"));
    						break;
    					}
    				}else{
    					if($sender->hasPermission("kitpro")){
    						$sender->sendMessage($this->plugin->translateColors("&",  Main::PREFIX . "&cSubcommand &a" . $args[0] . " &cnot found. Use &a/kitpro help &cto show available commands"));
    						break;
    					}else{
    						$sender->sendMessage($this->plugin->translateColors("&", "&cYou don't have permissions to use this command"));
    						break;
    					}
    				}
    				}else{
    					if($sender->hasPermission("kitpro.commands.help")){
    						$sender->sendMessage($this->plugin->translateColors("&", "&7<<>> &cAvailable Commands &7<<>>"));
    						$sender->sendMessage($this->plugin->translateColors("&", "&c/kitpro info &7<>&a Show info about this plugin"));
    						$sender->sendMessage($this->plugin->translateColors("&", "&c/kitpro help &7<>&a Show help about this plugin"));
    						$sender->sendMessage($this->plugin->translateColors("&", "&c/kitpro reload &7<>&a Reload the config"));
    						$sender->sendMessage($this->plugin->translateColors("&", "&c/kit &7<>&a Get a kit"));
    						break;
    					}else{
    						$sender->sendMessage($this->plugin->translateColors("&", "&cYou don't have permissions to use this command"));
    						break;
    					}
    				}
    			}
    	}
}
?>
