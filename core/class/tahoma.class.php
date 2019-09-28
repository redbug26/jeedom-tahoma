<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
require_once dirname(__FILE__) . '/../../3rdparty/tahoma.inc.php';

class tahoma extends eqLogic {

	public static function event() {
		$cmd = tahomaCmd::byId(init('id'));
		if (!is_object($cmd)) {
			throw new Exception('Commande ID virtuel inconnu : ' . init('id'));
		}
		$value = init('value');
		$tahomaCmd = tahomaCmd::byId($cmd->getConfiguration('infoId'));
		if (is_object($tahomaCmd)) {
			if ($tahomaCmd->getEqLogic()->getEqType_name() != 'tahoma') {
				throw new Exception(__('La cible de la commande tahoma n\'est pas un équipement de type tahoma', __FILE__));
			}
			if ($tahomaCmd->getSubType() != 'slider' && $virtualCmd->getSubType() != 'color') {
				$value = $tahomaCmd->getConfiguration('value');
			}
		}
		log::add('tahoma', 'debug', "Tahoma event cmdName: " . $tahomaCmd->getName() . " / value: " . $value);
	}

	public static function pull($_options="") {
		sleep(rand(0, 240));
		tahoma::syncEqLogicWithRazberry();
	}

	public static function pullSonde($_options) {
	}

	public function postInsert() {
	}

	public static function deamonRunning() {

		$userId = config::byKey('userId', 'tahoma');
		$userPassword = config::byKey('userPassword', 'tahoma');

		$modules = tahomaGetModules($userId, $userPassword);

		return is_array($modules);
	}

	public static function getConfigurationData($_serverId = 1) {

		$userId = config::byKey('userId', 'tahoma');
		$userPassword = config::byKey('userPassword', 'tahoma');

		return tahomaGetConfigurationData($userId, $userPassword);
	}

	public static function syncEqLogicWithRazberry($_serverId = 1) {
		//log::add('tahoma', 'debug', "syncEqLogicWithRazberry()");

		$userId = config::byKey('userId', 'tahoma');
		$userPassword = config::byKey('userPassword', 'tahoma');

		$eqLogics = eqLogic::byType('tahoma');

		$modules = tahomaGetModules($userId, $userPassword);

		foreach ($modules as $module) {

			$found = false;

			foreach ($eqLogics as $eqLogic) {
				if ($module->deviceURL == $eqLogic->getConfiguration('deviceURL')) {
					$eqLogic_found = $eqLogic;
					$found = true;
					break;
				}
			}

			if (!$found) {
				$eqLogic = new eqLogic();
				$eqLogic->setEqType_name('tahoma');
				$eqLogic->setIsEnable(1);
				$eqLogic->setIsVisible(1);
				$eqLogic->setName($module->label);
				$eqLogic->setConfiguration('type', $module->controllableName);
				$eqLogic->setConfiguration('deviceURL', $module->deviceURL);
				$eqLogic->save();

				$eqLogic = self::byId($eqLogic->getId());

				/***********************************/
				//Actions
				if ($module->uiClass == "HeatingSystem") {
					$tahomaCmd = new tahomaCmd();
					$tahomaCmd->setType('action');
					$tahomaCmd->setSubType('other');
					$tahomaCmd->setName('On');
					$tahomaCmd->setEqLogic_id($eqLogic->getId());
					$tahomaCmd->setConfiguration('deviceURL', $module->deviceURL);
					$tahomaCmd->setConfiguration('commandName', 'setOperatingMode');
					$tahomaCmd->setConfiguration('nparams', 1);
					$tahomaCmd->setConfiguration('parameters', 'on');
					$tahomaCmd->save();

					$tahomaCmd = new tahomaCmd();
					$tahomaCmd->setType('action');
					$tahomaCmd->setSubType('other');
					$tahomaCmd->setName('Off');
					$tahomaCmd->setEqLogic_id($eqLogic->getId());
					$tahomaCmd->setConfiguration('deviceURL', $module->deviceURL);
					$tahomaCmd->setConfiguration('commandName', 'setOperatingMode');
					$tahomaCmd->setConfiguration('nparams', 1);
					$tahomaCmd->setConfiguration('parameters', 'standby');
					$tahomaCmd->save();

					$tahomaCmd = new tahomaCmd();
					$tahomaCmd->setType('action');
					$tahomaCmd->setSubType('other');
					$tahomaCmd->setName('Auto');
					$tahomaCmd->setEqLogic_id($eqLogic->getId());
					$tahomaCmd->setConfiguration('deviceURL', $module->deviceURL);
					$tahomaCmd->setConfiguration('commandName', 'setOperatingMode');
					$tahomaCmd->setConfiguration('nparams', 1);
					$tahomaCmd->setConfiguration('parameters', 'auto');
					$tahomaCmd->save();

					//$tahomaCmd = new tahomaCmd();
					//$tahomaCmd->setType('action');
					//$tahomaCmd->setSubType('other');
					//$tahomaCmd->setName('Manuel');
					//$tahomaCmd->setEqLogic_id($eqLogic->getId());
					//$tahomaCmd->setConfiguration('deviceURL', $module->deviceURL);
					//$tahomaCmd->setConfiguration('commandName', 'setOperatingMode');
					//$tahomaCmd->setConfiguration('nparams', 1);
					//$tahomaCmd->setConfiguration('parameters', 'manu');
					//$tahomaCmd->save();

					$tahomaCmd = new tahomaCmd();
					$tahomaCmd->setType('action');
					$tahomaCmd->setSubType('other');
					$tahomaCmd->setName('Eco');
					$tahomaCmd->setEqLogic_id($eqLogic->getId());
					$tahomaCmd->setConfiguration('deviceURL', $module->deviceURL);
					$tahomaCmd->setConfiguration('commandName', 'setOperatingMode');
					$tahomaCmd->setConfiguration('nparams', 1);
					$tahomaCmd->setConfiguration('parameters', 'eco');
					$tahomaCmd->save();

					$tahomaCmd = new tahomaCmd();
					$tahomaCmd->setType('action');
					$tahomaCmd->setSubType('other');
					$tahomaCmd->setName('Confort');
					$tahomaCmd->setEqLogic_id($eqLogic->getId());
					$tahomaCmd->setConfiguration('deviceURL', $module->deviceURL);
					$tahomaCmd->setConfiguration('commandName', 'setOperatingMode');
					$tahomaCmd->setConfiguration('nparams', 1);
					$tahomaCmd->setConfiguration('parameters', 'comfort');
					$tahomaCmd->save();

					$tahomaCmd = new tahomaCmd();
					$tahomaCmd->setType('action');
					$tahomaCmd->setSubType('other');
					$tahomaCmd->setName('HG');
					$tahomaCmd->setEqLogic_id($eqLogic->getId());
					$tahomaCmd->setConfiguration('deviceURL', $module->deviceURL);
					$tahomaCmd->setConfiguration('commandName', 'setHeatingLevel');
					$tahomaCmd->setConfiguration('nparams', 1);
					$tahomaCmd->setConfiguration('parameters', 'frostprotection');
					$tahomaCmd->save();

					$tahomaCmd = new tahomaCmd();
					$tahomaCmd->setType('action');
					$tahomaCmd->setSubType('slider');
					$tahomaCmd->setName('Confort temperature');
					$tahomaCmd->setEqLogic_id($eqLogic->getId());
					$tahomaCmd->setConfiguration('deviceURL', $module->deviceURL);
					$tahomaCmd->setConfiguration('commandName', 'setComfortTemperature');
					$tahomaCmd->setConfiguration('nparams', 1);
					$tahomaCmd->setConfiguration('parameters', '#slider#');
					$tahomaCmd->setConfiguration('minValue', '15');
					$tahomaCmd->setConfiguration('maxValue', '30');
					$tahomaCmd->save();

					$tahomaCmd = new tahomaCmd();
					$tahomaCmd->setType('action');
					$tahomaCmd->setSubType('slider');
					$tahomaCmd->setName('Eco temperature');
					$tahomaCmd->setEqLogic_id($eqLogic->getId());
					$tahomaCmd->setConfiguration('deviceURL', $module->deviceURL);
					$tahomaCmd->setConfiguration('commandName', 'setEcoTemperature');
					$tahomaCmd->setConfiguration('nparams', 1);
					$tahomaCmd->setConfiguration('parameters', '#slider#');
					$tahomaCmd->setConfiguration('minValue', '10');
					$tahomaCmd->setConfiguration('maxValue', '25');
					$tahomaCmd->save();

					$tahomaCmd = new tahomaCmd();
					$tahomaCmd->setType('action');
					$tahomaCmd->setSubType('slider');
					$tahomaCmd->setName('HG temperature');
					$tahomaCmd->setEqLogic_id($eqLogic->getId());
					$tahomaCmd->setConfiguration('deviceURL', $module->deviceURL);
					$tahomaCmd->setConfiguration('commandName', 'setSecuredPositionTemperature');
					$tahomaCmd->setConfiguration('nparams', 1);
					$tahomaCmd->setConfiguration('parameters', '#slider#');
					$tahomaCmd->setConfiguration('minValue', '5');
					$tahomaCmd->setConfiguration('maxValue', '10');
					$tahomaCmd->save();

				} else {
					foreach ($module->definition->commands as $command) {

						$tahomaCmd = new tahomaCmd();

						if ($module->controllableName == "io:RollerShutterGenericIOComponent") {
							// Store
						}

						if ($module->controllableName == "rts:OnOffRTSComponent") {
							// Prise On-Off
						}

						if ($module->controllableName == "io:LightIOSystemSensor") {
							// Module de luminosité
						}

						if ($module->controllableName == "rts:LightRTSComponent") {
							// Lampe
						}

						$useCmd = true;

						if ($command->commandName == "setClosure") {
							$tahomaCmd->setType('action');
							$tahomaCmd->setIsVisible(0);
							$tahomaCmd->setSubType('slider');
							$tahomaCmd->setConfiguration('request', 'closure');
							$tahomaCmd->setConfiguration('parameters', '#slider#');
							$tahomaCmd->setConfiguration('minValue', '0');
							$tahomaCmd->setConfiguration('maxValue', '100');
							$tahomaCmd->setDisplay('generic_type', 'FLAP_SLIDER');
						} else if ($command->commandName == "setOrientation") {
							$tahomaCmd->setType('action');
							$tahomaCmd->setIsVisible(0);
							$tahomaCmd->setSubType('slider');
							$tahomaCmd->setConfiguration('request', 'orientation');
							$tahomaCmd->setConfiguration('parameters', '#slider#');
							$tahomaCmd->setConfiguration('minValue', '0');
							$tahomaCmd->setConfiguration('maxValue', '180');
						} else if ($command->commandName == "open") {
							$tahomaCmd->setType('action');
							$tahomaCmd->setSubType('other');
							$tahomaCmd->setDisplay('icon', '<i class="fa fa-arrow-up"></i>');
							$tahomaCmd->setDisplay('generic_type', 'FLAP_UP');
						} else if ($command->commandName == "close") {
							$tahomaCmd->setType('action');
							$tahomaCmd->setSubType('other');
							$tahomaCmd->setDisplay('icon', '<i class="fa fa-arrow-down"></i>');
							$tahomaCmd->setDisplay('generic_type', 'FLAP_DOWN');
						} else if ($command->commandName == "lock") {
							// serrure connectée : commande action ouvrir
							$tahomaCmd->setType('action');
							$tahomaCmd->setSubType('other');
							$tahomaCmd->setDisplay('icon', '<i class="fa fa-lock"></i>');
							$tahomaCmd->setDisplay('generic_type', 'LOCK_CLOSE');
						} else if ($command->commandName == "unlock") {
							// serrure connectée : commande action fermer
							$tahomaCmd->setType('action');
							$tahomaCmd->setSubType('other');
							$tahomaCmd->setDisplay('icon', '<i class="fa fa-unlock"></i>');
							$tahomaCmd->setDisplay('generic_type', 'LOCK_OPEN');
						} else if ($command->commandName == "setLockedUnlocked") {
							// serrure connectée : commande action ouvrir ou fermer
							$tahomaCmd->setType('action');
							$tahomaCmd->setSubType('select');
							$tahomaCmd->setIsVisible(0);
							$tahomaCmd->setConfiguration('parameters', '#select#');
							$tahomaCmd->setConfiguration('listValue', 'unlocked|Ouvrir;locked|Fermer');
							$tahomaCmd->setDisplay('icon', '<i class="fa fa-unlock-alt"></i>');
						} else if ($command->commandName == "my") {
							$tahomaCmd->setType('action');
							$tahomaCmd->setSubType('other');
							$tahomaCmd->setDisplay('icon', '<i class="fa fa-star-o"></i>');
							$tahomaCmd->setDisplay('generic_type', 'FLAP_STOP');
						} else if ($command->commandName == "stop") {
							$tahomaCmd->setType('action');
							$tahomaCmd->setSubType('other');
							$tahomaCmd->setDisplay('icon', '<i class="fa fa-stop"></i>');
						} else if ($command->commandName == "on") {
							$tahomaCmd->setType('action');
							$tahomaCmd->setSubType('other');
							$tahomaCmd->setDisplay('icon', '<i class="fa fa-toggle-on"></i>');
						} else if ($command->commandName == "alarmPartial1") {
							//zone alarme 1
							$tahomaCmd->setType('action');
							$tahomaCmd->setSubType('other');
							$tahomaCmd->setDisplay('icon', '<i class="fa fa-toggle-on"></i>');
						} else if ($command->commandName == "alarmPartial2") {
							//zone alarme 2
							$tahomaCmd->setType('action');
							$tahomaCmd->setSubType('other');
							$tahomaCmd->setDisplay('icon', '<i class="fa fa-toggle-on"></i>');
						} else if ($command->commandName == "off") {
							$tahomaCmd->setType('action');
							$tahomaCmd->setSubType('other');
							$tahomaCmd->setDisplay('icon', '<i class="fa fa-toggle-off"></i>');
						} else if ($command->commandName == "down") {
							$tahomaCmd->setType('action');
							$tahomaCmd->setSubType('other');
							$tahomaCmd->setDisplay('icon', '<i class="fa fa-arrow-down"></i>');
							$tahomaCmd->setIsVisible(0);
						} else if ($command->commandName == "up") {
							$tahomaCmd->setType('action');
							$tahomaCmd->setSubType('other');
							$tahomaCmd->setDisplay('icon', '<i class="fa fa-arrow-up"></i>');
							$tahomaCmd->setIsVisible(0);
						} else if ($command->commandName == "test") {
							$tahomaCmd->setType('action');
							$tahomaCmd->setSubType('other');
							$tahomaCmd->setDisplay('icon', '<i class="fa fa-exchange"></i>');
						} else {
							$useCmd = false;
						}

						if ($useCmd) {
							$tahomaCmd->setName($command->commandName);
							//   $tahomaCmd->setLogicalId('on');
							$tahomaCmd->setEqLogic_id($eqLogic->getId());
							$tahomaCmd->setConfiguration('deviceURL', $module->deviceURL);
							$tahomaCmd->setConfiguration('commandName', $command->commandName);
							$tahomaCmd->setConfiguration('nparams', $command->nparams);

							$tahomaCmd->save();
						}
					}
				}
				// Cancel operation
				$tahomaCmd = new tahomaCmd();
				$tahomaCmd->setType('action');
				$tahomaCmd->setSubType('other');
				$tahomaCmd->setDisplay('icon', '<i class="fa fa-ban"></i>');
				$tahomaCmd->setName("cancel");
				$tahomaCmd->setEqLogic_id($eqLogic->getId());
				$tahomaCmd->setConfiguration('deviceURL', $module->deviceURL);
				$tahomaCmd->setConfiguration('commandName', "cancelExecutions");
				$tahomaCmd->setConfiguration('nparams', 1);
				$tahomaCmd->save();
				/***********************************/
				//Infos
				//foreach ($module->definition->states as $state) {
				foreach ($module->states as $state) {

					$tahomaCmd = new tahomaCmd();

					$tahomaCmd->setName($state->name);
					$tahomaCmd->setEqLogic_id($eqLogic->getId());
					$tahomaCmd->setLogicalId($state->name);
					$tahomaCmd->setConfiguration('type', $state->name);
					$tahomaCmd->setType('info');
					switch ($state->type) {
					case 1:
						$tahomaCmd->setSubType('numeric');
						break;
					case 2:
						$tahomaCmd->setSubType('numeric');
						break;
					case 3:
						$tahomaCmd->setSubType('string');
						break;
					}
					$tahomaCmd->setIsVisible(0);
					$tahomaCmd->setEventOnly(1);
					foreach ($module->attributes as $attribute) {
						switch ($attribute->name) {
						case 'core:MeasuredValueType':
							switch ($attribute->value) {
							case 'core:TemperatureInCelcius':
								$tahomaCmd->setUnite('°C');
								break;
							case 'core:VolumeInCubicMeter':
								$tahomaCmd->setUnite('m3');
								break;
							case 'core:ElectricalEnergyInWh':
								$tahomaCmd->setUnite('Wh');
								break;
							}
							break;
						case 'core:MaxSensedValue':
							$tahomaCmd->setConfiguration('maxValue', $attribute->value);
							break;
						case 'core:MinSensedValue':
							$tahomaCmd->setConfiguration('minValue', $attribute->value);
							break;

						}
					}
					$tahomaCmd->save();

					$linkedCmdName = '';
					switch ($state->name) {
					//if ($state->name == "core:ClosureState") {
					case 'core:ClosureState':
						$linkedCmdName = 'setClosure';
						$tahomaCmd->setDisplay('generic_type', 'FLAP_STATE');
						$tahomaCmd->save();
						break;
					case 'core:SlateOrientationState':
						$linkedCmdName = 'setOrientation';
						break;
					case 'core:ComfortRoomTemperatureState':
						$linkedCmdName = 'setComfortTemperature';
						break;
					case 'core:EcoRoomTemperatureState':
						$linkedCmdName = 'setEcoTemperature';
						break;
					case 'core:SecuredPositionTemperatureState':
						$linkedCmdName = 'setSecuredPositionTemperature';
						break;
					case 'core:LockedUnlockedState':
						// Serrure connectée état lié
						$linkedCmdName = 'setLockedUnlocked';
						$tahomaCmd->setDisplay('generic_type', 'LOCK_STATE');
						$tahomaCmd->save();
						break;
					}
					if ($linkedCmdName !== '') {
						foreach ($eqLogic->getCmd() as $action) {
							if ($action->getConfiguration('commandName') == $linkedCmdName) {
								$action->setValue($tahomaCmd->getId());
								$action->save();
							}
						}
					}
				}
			} else {
				$eqLogic = $eqLogic_found;

// Update !

			}

			foreach ($eqLogic->getCmd() as $command) {

				// Mise a jour des generic_type

				if ($command->getType() == 'action') {
					if ($command->getName() == 'open') {
						$command->setDisplay('generic_type', 'FLAP_UP');
						$command->save();
					}
					if ($command->getName() == 'close') {
						$command->setDisplay('generic_type', 'FLAP_DOWN');
						$command->save();
					}
					if ($command->getName() == 'my') {
						$command->setDisplay('generic_type', 'FLAP_STOP');
						$command->save();
					}
					// Serrure connectée
					if ($command->getName() == 'lock') {
						$command->setDisplay('generic_type', 'LOCK_CLOSE');
						$command->save();
					}
					if ($command->getName() == 'unlock') {
						$command->setDisplay('generic_type', 'LOCK_OPEN');
						$command->save();
					}
				}

				//Recupération des valeur et mise a jour des commandes info par event

				if ($command->getType() == 'info') {
					foreach ($module->states as $state) {
						if ($state->name == $command->getConfiguration('type')) {
							$command->setCollectDate('');

							$value = $state->value;
							if ($state->name == "core:ClosureState") {
								$value = 100 - $value;
							}

							$command->event($value);
						}
					}
				}
			}
		}

// Creation des scenarios

		$found = false;

		foreach ($eqLogics as $eqLogic) {
			// Recherche le module 'ActionGroups'
			log::add('tahoma', 'debug', "eqlabel: " . $eqLogic->getConfiguration('deviceURL'));

			if ($eqLogic->getConfiguration('deviceURL') == "ActionGroups") {
				$eqLogic_found = $eqLogic;
				$found = true;
				break;
			}
		}

		if (!$found) {
			$eqLogic = new eqLogic();
			$eqLogic->setEqType_name('tahoma');
			$eqLogic->setIsEnable(1);
			$eqLogic->setIsVisible(1);
			$eqLogic->setName("Scenarios");
			$eqLogic->setConfiguration('deviceURL', "ActionGroups");
			$eqLogic->save();

			$eqLogic = self::byId($eqLogic->getId());
		}

		$scenarios = tahomaGetScenarios($userId, $userPassword);

		foreach ($scenarios as $scenario) {
			$found = false;

			foreach ($eqLogic->getCmd() as $command) {
				if ($command->getType() == 'action') {
					if ($command->getConfiguration('deviceURL') == $scenario->oid) {
						$found = true;
					}
				}
			}

			if (!$found) {
				$tahomaCmd = new tahomaCmd();

				$tahomaCmd->setType('action');
				$tahomaCmd->setSubType('other');
				$tahomaCmd->setName($scenario->label);
				$tahomaCmd->setEqLogic_id($eqLogic->getId());
				$tahomaCmd->setConfiguration('deviceURL', $scenario->oid);
				$tahomaCmd->setConfiguration('commandName', 'execAction');
				$tahomaCmd->save();
			}
		}
	}
}

class tahomaCmd extends cmd {
	/*     * *************************Attributs****************************** */

	/*     * ***********************Methode static*************************** */

	/*     * *********************Methode d'instance************************* */

	public function preSave() {

	}

	public function execute($_options = null) {
		//log::add('tahoma', 'debug', "execute()");

		$userId = config::byKey('userId', 'tahoma');
		$userPassword = config::byKey('userPassword', 'tahoma');

		$deviceURL = $this->getConfiguration('deviceURL');
		$commandName = $this->getConfiguration('commandName');
		$parameters = $this->getConfiguration('parameters');

		if ($commandName == "execAction") {
			$oid = $deviceURL;

			tahomaExecAction($userId, $userPassword, $oid);

			return;
		}

		if ($this->type == 'action') {
			switch ($this->subType) {
			case 'slider':
				$type = $this->getConfiguration('request');
				$parameters = str_replace('#slider#', $_options['slider'], $parameters);

				$newEventValue = $parameters;

				switch ($type) {
				case 'orientation':
					if ($commandName == "setOrientation") {
						$parameters = array_map('intval', explode(",", $parameters));
						tahomaSendCommand($userId, $userPassword, $deviceURL, $commandName, $parameters, $this->getName());

						$eqLogics = eqLogic::byType('tahoma');
						foreach ($eqLogics as $eqLogic) {
							if ($eqLogic->getConfiguration('deviceURL') == $deviceURL) {
								foreach ($eqLogic->getCmd() as $command) {
									if ($command->getType() == 'info') {
										if ($command->getName() == "core:SlateOrientationState") {
											$command->setCollectDate('');
											$command->event($newEventValue);
										}
									}
								}
							}
						}

						return;
					}
				case 'closure':
					if ($commandName == "setClosure") {
						$parameters = 100 - $parameters;

						$parameters = array_map('intval', explode(",", $parameters));
						tahomaSendCommand($userId, $userPassword, $deviceURL, $commandName, $parameters, $this->getName());

						$eqLogics = eqLogic::byType('tahoma');
						foreach ($eqLogics as $eqLogic) {
							if ($eqLogic->getConfiguration('deviceURL') == $deviceURL) {
								foreach ($eqLogic->getCmd() as $command) {
									if ($command->getType() == 'info') {
										if ($command->getName() == "core:ClosureState") {
											$command->setCollectDate('');
											$command->event($newEventValue);
										}
									}
								}
							}
						}

						return;
					}
					break;
				}
				case 'select':
					if ($commandName == 'setLockedUnlocked'){
						$parameters = str_replace('#select#', $_options['select'], $parameters);
					}
				break;
			}

			if ($this->getConfiguration('nparams') == 0) {
				$parameters = "";
			} else if ($commandName == "setClosure") {
				$parameters = array_map('intval', explode(",", $parameters));
			} else {
				$parameters = explode(",", $parameters);
			}

			if ($commandName == "cancelExecutions") {
				$execId = $parameters[0];

				log::add('tahoma', 'debug', "will cancelExecutions: (" . $execId . ") from tahoma.class");

				tahomaCancelExecutions($userId, $userPassword, $execId);
			} else {
				$execId = tahomaSendCommand($userId, $userPassword, $deviceURL, $commandName, $parameters, $this->getName());

				log::add('tahoma', 'debug', "return cancelExecutions: (" . $execId . ")");

				$eqLogics = eqLogic::byType('tahoma');
				foreach ($eqLogics as $eqLogic) {
					if ($eqLogic->getConfiguration('deviceURL') == $deviceURL) {
						foreach ($eqLogic->getCmd() as $command) {
							//if ($command->getConfiguration('commandName') == "cancelExecutions") {
							if ($commandName == "cancelExecutions") {
								log::add('tahoma', 'debug', "set cancelExecutions: (" . $execId . ")");

								$command->setConfiguration('parameters', $execId);
								$command->save();

							}
						}
					}
				}
			}
			// Rafraichissement des valeurs après actions
			if ($commandName == 'setSecuredPositionTemperature'
				|| $commandName == 'setEcoTemperature'
				|| $commandName == 'setComfortTemperature'
				|| $commandName == 'setManuAndSetPointModes'
				|| $commandName == 'setHeatingLevel'
				|| $commandName == 'setOperatingMode'
				|| $commandName == 'setOnOff'
				|| $commandName == 'lock'
				|| $commandName == 'unlock'
				|| $commandName == 'setLockedUnlocked') {
				sleep(5);
				tahoma::syncEqLogicWithRazberry();
			}

			return;
		}

		if ($this->type == 'info') {

			return;
		}

	}

}

?>
