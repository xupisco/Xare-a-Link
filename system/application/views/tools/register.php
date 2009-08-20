<?php $this->load->view("common/layout_top"); ?>

<script src="<?= base_url(); ?>js/jquery_alphanumeric.js" language="JavaScript"></script>

<script type="text/javascript">
function doSearch() {
    if(!$("#search").val()) {
        alert("D'uh!!!");
        return false;
    }
    document.location.href = 's/' + $("#search").val();
    return false;
}

$(document).ready(function() {
	$("#reg_login").alpha({nocaps:true});
});

function parseLogin() {
	
}
</script>

<!-- Width = 100% para pegar a largura toda. 760 = Default -->
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td align="left">

		<img src="images/common/dot.gif" width="1" height="6" />

        <div>
        <table width="80%">
        <form action="search" method="get" onsubmit="return doSearch();">
        <tr>
        <td width="1%" nowrap="nowrap"><b>Buscar por:</b></td>
        <td width="80%"><input type="text" name="q" id="search" value="" class="main_search" style="width: 100%" /></td>
        <td><img src="images/common/dot.gif" width="6" height="1" /></td>
        <td width="1%"><select name="qt" class="main_select"><option value="all">Em qualquer lugar</option></select></td>
        <td width="1%"><img src="images/b_oks-off.gif" onmouseover="cb(this, 1);" onmouseout="cb(this, 0);" border="0" onclick="javascript:doSearch();" /></td>
        </tr>
        </form>
        </table>
        </div>

        <img src="images/common/dot.gif" width="1" height="12" /><br />

		<table width="100%" cellpadding="0" cellspacing="0">
			<tr>
				<td class="str_nav">
				<img src="images/common/dot.gif" width="2" height="1" /><a href="<?= base_url(); ?>"><b>x.are</b></a>
				<span class="nav_sep">&raquo;</span>
				Cadastro
				</td>
			</tr>
		</table>
		
		<div id="content_desc" style="padding-left: 2px">Isso mesmo, é só preencher esses 3 campos que seu cadastro está pronto.</div>
        <img src="images/common/dot.gif" width="1" height="8" /><br />
		
        <div id="dbar">
        <b>Fomulário para cadastro</b>
        </div>

        <img src="images/common/dot.gif" width="1" height="8" /><br />

        <div id="content_rel">
        <table width="100%" cellpadding="4" cellspacing="2">
        <form method="POST" action="t/register_do">
        <tr><td align="right" width="10%" valign="top"><img src="images/common/dot.gif" width="1" height="6" /><br />Login: </td><td width="40%"><input type="text" class="std_input" name="reg_login" id="reg_login" onblur="parseLogin()" style="font-size: 18px;" maxlength="16" /><div id="logincheck" style="display: none; font-size: 11px; color: #ff0000; padding-top: 2px"><img src="images/icons/error_alert.gif" align="left" />Login já utilizado, escolha outro.</div></td><td style="font-size: 11px; color: #777777; padding-left: 10px;" width="30%" valign="top"><img src="images/common/dot.gif" width="1" height="9" /><br />Login é único e não poderá ser alterado.</td></tr>
        <tr><td align="right" nowrap="nowrap">Senha: </td><td nowrap><input type="text" class="std_input" name="reg_passwd" id="link_url" style="font-size: 18px;" maxlength="32" /></td><td style="font-size: 11px; color: #777777; padding-left: 10px;">Sua senha será encriptada antes de ser salva no banco de dados.</td></tr>
        <tr><td align="right">E-mail: </td><td><input type="text" class="std_input" value="" name="reg_email" id="reg_email" style="font-size: 18px;" maxlength="128" /></td><td style="font-size: 11px; color: #777777; padding-left: 10px;">Uma confirmação será enviada. Digite um e-mail válido.</td></tr>
		<tr><td></td><td>
			<img src="images/common/dot.gif" width="1" height="4" /><br />
			<img src="images/icons/big_info.gif" align="left" />
			<img src="images/common/dot.gif" width="1" height="10" /><br />
	        <b>Um e-mail de confirmação será enviado para o endereço cadastrado.</b> <b style="color: #ff0000">Você DEVE clicar no link que receber para finalizar seu cadastro</b>.
		</td></tr>
		<tr><td></td><td>
			<img src="images/common/dot.gif" width="1" height="4" /><br />
	        <input type="submit" value="Enviar cadastro" style="background-color: #99C145; border: 1px solid #69832F; padding: 6px; color: #ffffff; font-weight: bold" />		
		</td></tr>
		</form>
        </table>
        </div>
		
		<img src="images/common/dot.gif" width="1" height="12" /><br />
        <div id="roots">
        <div style="float: right">{elapsed_time}</div>
        <?php
            $cnt = 1;
	        foreach($root->result() as $row) {
	            echo "<a href='".$row->name_url."' rel='nofollow'>".$row->name."</a> (".$row->related.")";
	            if($cnt != $root->num_rows()) echo " - ";
	            $cnt++;
	        }
	    ?>
        </div>
		
		</td>
	</tr>
</table>

<?php $this->load->view("common/layout_bottom"); ?>