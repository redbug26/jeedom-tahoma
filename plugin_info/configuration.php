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

require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';
include_file('core', 'authentification', 'php');
if (!isConnect()) {
	include_file('desktop', '404', 'php');
	die();
}

$deamonRunning = tahoma::deamonRunning();

?>

<form class="form-horizontal">
    <fieldset>

<?php
echo '<div class="form-group">';
echo '<label class="col-sm-4 control-label">{{Communication Tahoma}}</label>';
if (!$deamonRunning) {
	echo '<div class="col-sm-1"><span class="label label-danger">NOK</span></div>';
} else {
	echo '<div class="col-sm-1"><span class="label label-success">OK</span></div>';
}
echo '</div>';

?>


        <div class="form-group">
            <label class="col-lg-4 control-label">{{userId}}</label>
            <div class="col-lg-2">
                <input class="configKey form-control" data-l1key="userId" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-4 control-label">{{userPassword}}</label>
            <div class="col-lg-2">
                <input class="configKey form-control" type="password" data-l1key="userPassword" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-4 control-label">{{Debug}}</label>
            <div class="col-lg-8">
                <a class="btn btn-success bt_sendConfigToDEV"><i class='fa fa-book'></i> {{Envoi la configuration au developpeur}}</a>
            </div>
        </div>
       </fieldset>
</form>


<script>
    $('.bt_sendConfigToDEV').on('click', function () {
        $.ajax({// fonction permettant de faire de l'ajax
            type: "POST", // methode de transmission des données au fichier php
            url: "plugins/tahoma/core/ajax/tahoma.ajax.php", // url du fichier php
            data: {
                action: "sendConfigToDEV",
                id : $(this).closest('.slaveConfig').attr('data-slave_id')
            },
            dataType: 'json',
            error: function (request, status, error) {
                handleAjaxError(request, status, error);
            },
            success: function (data) { // si l'appel a bien fonctionné
            if (data.state != 'ok') {
                $('#div_alert').showAlert({message: data.result, level: 'danger'});
                return;
            }
            $('#div_alert').showAlert({message: '{{Vous pouvez maintenant envoyer le fichier log "tahoma" à jeedom.tahoma@kyuran.be}}', level: 'success'});
           // $('#ul_plugin .li_plugin[data-plugin_id=tahoma]').click();
        }
    });
    });


    function tahoma_postSaveConfiguration() {
        $('#ul_plugin .li_plugin[data-plugin_id=tahoma]').click();
    }
</script>
