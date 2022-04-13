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

if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}

$pluginName = init('m');

if (init('object_id') == '') {
	$object = jeeObject::byId($_SESSION['user']->getOptions('defaultDashboardObject'));
} else {
	$object = jeeObject::byId(init('object_id'));
}
if (!is_object($object)) {
	$object = jeeObject::rootObject();
	if (!is_object($object)) {
		throw new Exception('{{Aucun objet racine trouvé. Pour en créer un, allez dans Général -> Objet.<br/> Si vous ne savez pas quoi faire ou que c’est la premiere fois que vous utilisez Jeedom n’hésitez pas a consulter cette <a href="https://doc.jeedom.com/fr_FR/premiers-pas/" target="_blank">page</a>}}');
	}
}
$child_object = jeeObject::buildTree($object);
$parentNumber = array();

$jeeObjectAll = jeeObject::buildTree(null, true);

if ($_SESSION['user']->getOptions('displayObjetByDefault') == 1 && init('report') != 1) {
	$clazz = "col-lg-10 col-md-9 col-sm-8";
	$style = "";
} else {
	$clazz = "col-lg-12 col-md-12 col-sm-12";
	$style = 'style="display:none;"';
}
?>

<div class="row row-overflow">
	<div class="col-lg-2 col-md-3 col-sm-4" <?= $style ?> id="div_displayObjectList">
		<div class="bs-sidebar">
			<ul id="ul_object" class="nav nav-list bs-sidenav">
				<li class="nav-header">{{Liste objets}} </li>
				<li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
				<?php
				foreach ($jeeObjectAll as $jeeObject) {
					$margin = 5 * $jeeObject->getConfiguration('parentNumber');
					if ($jeeObject->getId() == $object->getId()) {
						$active = "active";
					} else {
						$active = "";
					} ?>
					<li class="cursor li_object <?= $active ?>">
						<a data-object_id="<?= $jeeObject->getId() ?>" href="index.php?v=d&p=panel&m=<?= $pluginName ?>&object_id=<?= $jeeObject->getId() ?>" style="padding: 2px 0px;">
							<span style="position:relative;left:<?= $margin ?>px;"><?= $jeeObject->getHumanName(true) ?></span>
							<span style="font-size : 0.65em;float:right;position:relative;top:7px;"><?= $jeeObject->getHtmlSummary() ?></span>
						</a>
					</li>
				<?php } ?>
			</ul>
		</div>
	</div>

	<div class="<?= $clazz ?>" id="div_displayObject">
		<i class="fa fa-picture-o cursor pull-left reportModeHidden" id="bt_displayObject" data-display="<?= $_SESSION['user']->getOptions('displayObjetByDefault') ?>" title="Afficher/Masquer les objets"></i>
		<br/>
		<div class="div_displayEquipement" style="width: 100%;">
			<?php
			if (init('object_id') == '') {
				foreach ($jeeObjectAll as $object) {
					foreach ($object->getEqLogic(true, false, $pluginName) as $mcast) {
						echo $mcast->toHtml('dview');
					}
				}
			} else {
				foreach ($object->getEqLogic(true, false, $pluginName) as $mcast) {
					echo $mcast->toHtml('dview');
				}
				foreach ($child_object as $child) {
					$mcasts = $child->getEqLogic(true, false, $pluginName);
					if (count($mcasts) > 0) {
						foreach ($mcasts as $mcast) {
							echo $mcast->toHtml('dview');
						}
					}
				}
			}
			?>
		</div>
	</div>
</div>

<?php
include_file('desktop', 'panel', 'js', $pluginName);