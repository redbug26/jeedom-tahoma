<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
sendVarToJS('eqType', 'tahoma');
$eqLogics = eqLogic::byType('tahoma');
?>


<div class="row row-overflow">

    <div class="col-lg-2">
        <div class="bs-sidebar">
            <ul id="ul_eqLogic" class="nav nav-list bs-sidenav">
                <li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
                <?php
foreach (eqLogic::byType('tahoma') as $eqLogic) {
	echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $eqLogic->getId() . '"><a>' . $eqLogic->getHumanName() . '</a></li>';
}
?>
            </ul>
        </div>
    </div>

    <div class="col-lg-10 col-md-9 col-sm-8 eqLogicThumbnailDisplay" style="border-left: solid 1px #EEE; padding-left: 25px;">
    <legend>{{Mes équipements}}
    </legend>

    <div class="eqLogicThumbnailContainer">
      <div class="cursor expertModeVisible" id="bt_syncEqLogic" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
         <center>
            <i class="fa fa-refresh" style="font-size : 7em;color:#94ca02;"></i>
        </center>
        <span style="font-size : 1.1em;position:relative; top : 23px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#94ca02"><center>{{Synchroniser}}</center></span>
    </div>
    <?php
foreach ($eqLogics as $eqLogic) {
	echo '<div class="eqLogicDisplayCard cursor" data-eqLogic_id="' . $eqLogic->getId() . '" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >';
	echo "<center>";

	$image = "plugins/tahoma/doc/images/tahoma_icon.png";

	foreach ($eqLogic->getCmd() as $action) {

		if ($action->getConfiguration('type') == "core:ClosureState") {
			$image = sprintf("plugins/tahoma/doc/images/tahoma_icon.php?pos=%d", $action->execCmd());
		}
	}

	echo '<img src="' . $image . '" height="105" width="95" />';

	echo "</center>";
	echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>' . $eqLogic->getHumanName(true, true) . '</center></span>';
	echo '</div>';
}
?>
</div>
</div>




<div class="col-lg-10 col-md-9 col-sm-8 eqLogic" style="border-left: solid 1px #EEE; padding-left: 25px;display: none;">
    <div class="row">
        <div class="col-sm-8">
            <form class="form-horizontal">
                <fieldset>
                <legend><i class="fa fa-arrow-circle-left eqLogicAction cursor" data-action="returnToThumbnailDisplay"></i> {{Général}}  <i class='fa fa-cogs eqLogicAction pull-right cursor expertModeVisible' data-action='configure'></i></legend>
                <div class="form-group">
                    <label class="col-lg-3 control-label">{{Nom de l'équipement}}</label>
                    <div class="col-lg-3">
                        <input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
                        <input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement}}"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label" >{{Objet parent}}</label>
                    <div class="col-lg-4">
                        <select class="eqLogicAttr form-control" data-l1key="object_id">
                            <option value="">{{Aucun}}</option>
                            <?php
foreach (object::all() as $object) {
	echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
}
?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-3 control-label">{{Catégorie}}</label>
                    <div class="col-lg-9">
                        <?php
foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
	echo '<label class="checkbox-inline">';
	echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
	echo '</label>';
}
?>

                    </div>
                </div>

                    <div class="form-group">
                        <label class="col-lg-3 control-label"></label>
                        <div class="col-lg-9">
                          <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked/>{{Activer}}</label>
                          <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/>{{Visible}}</label>
                      </div>
                  </div>

            </fieldset>
        </form>
        </div>

        <div class="col-sm-4">
            <form class="form-horizontal">
                <fieldset>
                    <legend>{{Informations}}
                    <!--
                        <i id="bt_autoDetectModule" class="fa fa-search expertModeVisible pull-right tooltips cursor" title="{{Detecter automatiquement le modele du module}}"></i>
                    -->
                    </legend>

<!--
                    <div class="form-group">
                        <label class="col-sm-2 control-label">{{Module}}</label>
                        <div class="col-sm-5">
                        <span class="zwaveInfo tooltips label label-default" data-l1key="brand"><?php

// print_r(get_defined_vars());

?>
</span>
                      </div>



                </div>
 -->


            </fieldset>
            </form>
        </div>
</div>



<a style="display:none" class="btn btn-success btn-sm cmdAction" data-action="add"><i class="fa fa-plus-circle"></i> {{Ajouter une commande tahoma}}</a><br/>

<legend>Commandes</legend>
        <table id="table_cmd" class="table table-bordered table-condensed">
            <thead>
                <tr>
            		<th style="width:  50px;">#</th>
            		<th style="width: 130px;">{{Nom}}</th>
            		<th style="width: 150px;">{{Type}}</th>
            		<th style="width:  50px;">{{Commande API Tahoma}}</th>
            		<th style="width: 100px;">{{Unité}}</th>
            		<th style="width: 120px;">{{Paramètres}}</th>
            		<th style="width: 120px;"></th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>

        <form class="form-horizontal">
            <fieldset>
                <div class="form-actions">
                    <a class="btn btn-danger eqLogicAction" data-action="remove"><i class="fa fa-minus-circle"></i> {{Supprimer}}</a>
                    <a class="btn btn-success eqLogicAction" data-action="save"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
                </div>
            </fieldset>
        </form>

    </div>
</div>


<?php include_file('desktop', 'tahoma', 'js', 'tahoma');?>
<?php include_file('core', 'plugin.template', 'js');?>
