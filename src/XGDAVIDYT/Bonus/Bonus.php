<?php
declare(strict_types=1);

namespace XGDAVIDYT\Bonus;

use pocketmine\Player;
use pocketmine\Server;

use pocketmine\plugin\PluginBase;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginManager;

use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\entity;

use pocketmine\command\CommandSender;
use pocketmine\command\Command;

use pocketmine\utils\Config;
use pocketmine\utils\Utils;

use onebone\economyapi\EconomyAPI;
use jojoe77777\FormAPI\SimpleForm;



class Bonus extends PluginBase {

    public function onEnable() : void{
        $this->bonus = new Config($this->getDataFolder() . "Bonus.yml", Config::YAML);
        $this->saveResource("config.yml");
        $this->money = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
        if(is_null($this->money)){
            $this->getServer()->getLogger()->notice("Don t found EconomyAPI");
             $this->getServer()->getPluginManager()->disablePlugin($this);
        }else{
            $this->getServer()->getLogger()->notice("EconomyAPI FOUND!\nWebSite: tcg-xgt.tk");
        }
    }
    public function onJoin(PlayerJoinEvent $event)
    {
        $player = $event->getPlayer();
        $name = $player->getName();
        $found =  $this->getConfig()->get("Found");
        $create =  $this->getConfig()->get("Create");
        if(!$this->bonus->exists($name)){
            $this->bonus->set($name, ["Bonus" => 0]);
            $this->getServer()->getLogger()->notice("§7[§bXGT§7] " . $name . " " . $create);
        }else{
            $this->getServer()->getLogger()->notice("§7[§bXGT§7] " . $name . " " . $found);
        }
        $this->bonus->save();
        
    }

    public function onQuit(PlayerQuitEvent $event) {
        $this->bonus->save();
    }

    public function onDisable() {
        $this->bonus->save();
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
        $name = $sender->getName();
        $player = $sender->getServer()->getPlayerExact($name);
        $folosit = $this->getConfig()->get("Folosit");
        $bani = $this->getConfig()->get("Bani");
        switch($command->getName()){
            case "bonus":
              if($sender instanceof Player) {
                  $sender->sendMessage("t");
                if($this->bonus->get($name)["Bonus"] == 0) {
                    $this->openBonus($sender);
                    $this->money->addMoney($sender, $bani);
                    $this->bonus->set($name, ["Bonus" => 1]);
                    $this->bonus->save();
                }elseif($this->bonus->get($name)["Bonus"] == 1){
                    $sender->sendMessage("§7[§bXGT§7] " . $folosit);
                }
              }
              return true;
        }
    }



    public function openBonus($player) { 
        $form = new SimpleForm(function (Player $player, int $data = null){

            $result = $data;
            if($result === null){
                return true;
            }

            switch ($data) {
                case 0:
                    
                break;

            }

        });

        $form->setTitle("§l§5INFO");
        $form->setContent($this->getConfig()->get("Content"));
        $form->addButton("§cClose");
        $form->sendToPlayer($player); 
        return $form;
    }
}
