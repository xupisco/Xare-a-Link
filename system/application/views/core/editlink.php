<?php
$_seo['is_home'] = true;
$this->load->view("common/layout_top", $_seo);
?>

<script src="<?= base_url(); ?>js/jquery_charcounter.js" language="JavaScript"></script>
<script src="<?= base_url(); ?>js/jquery_impromptu.js" language="JavaScript"></script>
<script src="<?= base_url(); ?>js/jquery_selectboxes.js" language="JavaScript"></script>

<script type="text/javascript">
$(document).ready(function() {
    $("#link_desc").charCounter(255, {
        container: "<span style='font-size: 11px'>",
        classname: "counter",
        format: "%1 caracteres restantes!",
        pulse: false
    });

    $("#ndb").click(function() {
        tmp = "last";
        $.prompt(txt,{
            callback: mycallbackform,
            show: 'fadeIn',
            opacity: '0.8',
            overlayspeed: 'fast',
			buttons: { Incluir: 'OK', Cancelar: 'Cancel' }
        });
        //$("#dirName").focus();
    });
    parseDest(1);
});

var lvl_cnt = <?= ($showb) ? $parents_cnt-2 : $parents_cnt-1; ?>;
var lvl = 0;
var tmp = 0;
var txt = "";
var cvals = "";
var cpath = "";

function removeTyped(force) {
    force = (!force) ? 0 : 1;
    if(force) {
        $("#new_dir").css("display","inline");
    } else {
        $("#new_dir").hide();
    }
    $("#new_dir_c").hide();
    $("#new_dir_txt").html("");
    parseDest(1);
}

function removeDir(n, g) {
    g = (!g) ? 0 : g;
    removed = 0;
    for(i = n; i <= lvl_cnt; i++) {
        div = "#ddir_" + i;
        $(div).remove();
        removed++;
    }
    lvl_cnt = (lvl_cnt - removed) + 1;
    if(g) {
        getDir(g, parseInt(n)-1);
    }
    removeTyped();
    parseDest(1);
}

function parseDest(w) {
    w = (!w) ? 0 : w;
    cvals = "";
    cpath = "";
    for(i=0; i< lvl_cnt; i++) {
        opt = "#dir_" + i + " option:selected";
        if($(opt).val() != "0") {
            cvals += $(opt).val() + "|||||";
            cpath += $(opt).text() + " / ";
        }
    }

    if($("#new_dir_txt").html().length > 2) {
        cvals += "new_" + $("#new_dir_txt").html() + "|||||";
    }

    cvals = cvals.substr(0, cvals.length - 5);
    cpath = cpath.substr(0, cpath.length - 3);
    ctitle = $("#link_title").val();

    if(w) {
        ct = (ctitle.length) ? " / <b style='color: #dd2222'>" + ctitle + "</b>" : "";
        typed = $("#new_dir_txt").html();
        t = (typed.length) ? " / (+) " + typed : "";
        $("#ldest").html(cpath + t + ct);
    }

    $("#h_parents").val(cvals);
    return true;
}

function getDir(id, lvl) {
    $.post("ajax/getdir/", { id: (id), lvl: (lvl)},
       function(data){
            if(data != "NONE") {
                $("#new_dir").hide();
                next = "#ddir_" + lvl;
                $(next).after(data);
                lvl_cnt++;
            } else {
                $("#new_dir").css("display","inline");
            }
            $("#loader").hide();
       }
    );
}

function cleanMe(e, txt) {
    if(e.value == txt) e.value = "";
}

function seedMe(e, txt) {
    if(e.value.length == 0) e.value = txt;
}

function getPR() {
    url = $("#link_url").val();
    if(url != "http://" && url != "0" && url != "") {
	    $.post("ajax/getpr/", { url: (url) },
	       function(data){
                $("#quick_pr").html("PageRank: " + data);
	            $("#quick_pr").fadeIn("fast");
	       }
	    );
	} else {
	   $("#quick_pr").hide();
	}
}

txt += "<b>Digite o nome do novo grupo!</b>";
txt += '<br /><img src="images/common/dot.gif" width="1" height="10" /><br />';
txt += '<input type="text" id="dirName" name="dir_name" value="Nome..." style="width: 256px; padding: 4px;" onfocus="cleanMe(this, \'Nome...\');" onblur="seedMe(this, \'Nome...\');" />';

function mycallbackform(v, m){
    nn = m.children('#dirName').val();
    if(v == "OK") {
        if(nn != "Nome...") {
            if(tmp != "last") {
		        selected = "#dir_" + tmp;
		        ngo = "nd_" + tmp;
				$(selected).addOption("new_" + nn, "(+) " + nn, true);
				removeDir(parseInt(tmp) + 1);
			} else {
			    $("#new_dir").hide();
			    $("#new_dir_c").css("display","inline");
			    $("#new_dir_txt").html(nn);
			}
			parseDest(1);
	    }
    }
}

function parseDir(e) {
    lvl = e.id.replace("dir_", "");
    val = e.value.replace("nd_", "");

    if(e.value.substr(0,3) == "nd_") {
        tmp = lvl;
        $.prompt(txt,{
            callback: mycallbackform,
            show: 'fadeIn',
            opacity: '0.8',
            overlayspeed: 'fast',
            buttons: { Incluir: 'OK', Cancelar: 'Cancel' }
        });
    } else {
        if(val == "0") {
            removeDir(parseInt(lvl)+1);
            return false;
        }
        removeDir(parseInt(lvl)+1, val);
        $("#loader").css("display","inline");
    }
}

function toggleBG(i, e) {
    if(i) { $(e).css({ backgroundColor: '#F2FFE8' }); }
    else { $(e).css({ backgroundColor: '#ffffff' }); }
}

function parseForm() {
    $("#n_parents").val(cpath);
    if(!$("#link_title").val()) {
        alert("Título do link obrigatório!");
        return false;
    }
    $("#urlerror").hide();
    url = $("#link_url").val();
    if(url != "http://" && url != "0" && url != "") {
        $.post("ajax/validateURL/", { url: (url) },
           function(data){
                if(data == "FOUND") {
                    $("#quick_pr").hide();
                    $("#urlerror").show();
                    return false;
                }
           }
        );
    }
    return true;
}
</script>

<style>
@import "css/impromptu.css";
</style>

<!-- Width = 100% para pegar a largura toda. 760 = Default -->
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td align="left">

		<img src="images/common/dot.gif" width="1" height="6" />

        <div>
        <b>Buscar por:</b> <input type="text" name="q" value="" class="main_search" /> <select name="qt" class="main_select"><option value="all">Em qualquer lugar&nbsp;&nbsp;</option></select>
        </div>

        <img src="images/common/dot.gif" width="1" height="12" /><br />

		<table width="100%" cellpadding="0" cellspacing="0">
			<tr>
				<td class="str_nav">
				<img src="images/common/dot.gif" width="2" height="1" /><a href="<?= base_url(); ?>index.html"><b>x.are</b></a>
				<span class="nav_sep">&raquo;</span>
                <a href="<?= $base; ?>"><?= $content->name; ?></a>
				<span class="nav_sep">&raquo;</span>
				<a href="editlink/rel=<?= $rel; ?>" id="content_name">Editing...</a>
				</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td class="default_content" valign="top" align="left">

    	<div id="content_desc" style="padding-left: 2px">Formulário para inclusão de novo link. Siga as instruções que tudo da certo.</div>

        <img src="images/common/dot.gif" width="1" height="8" /><br />

        <div id="dbar">
        <div style="float: left"><img src="images/icons/step_01.gif" /></div>
        <div style="float: right">Dúvidas? Leia nosso FAQ</div>
        <b>Informações básicas</b>
        </div>

        <form action="post" method="post" onsubmit="return parseForm();">

        <div id="content_rel">
        <table width="100%" cellpadding="4" cellspacing="2">
        <tr><td align="right" width="10%">Título do link: </td><td width="40%"><input type="text" class="std_input" name="link_title" id="link_title" onblur="parseDest(1)" value="<?= $content->name; ?>" /></td><td style="font-size: 11px; color: #777777; padding-left: 10px;" width="30%">Como o link será listado nas páginas.</td></tr>
        <tr><td align="right" valign="top" nowrap="nowrap"><img src="images/common/dot.gif" width="1" height="3" /><br />Endereço (URL): </td><td><input type="text" class="std_input" value="<?= $content->link; ?>" name="link_url" id="link_url" onblur="getPR()" /><div id="quick_pr" style="display: none; font-size: 11px; color: #777777; padding-top: 2px">PageRank: </div></td><td style="font-size: 11px; color: #777777; padding-left: 10px;" valign="top"><img src="images/common/dot.gif" width="1" height="6" /><br />Não publique links repetidos, faça uma busca antes de incluir.</td></tr>
        <tr><td align="right" valign="top"><img src="images/common/dot.gif" width="1" height="2" /><br />Descrição: </td><td><textarea class="std_ta" name="link_desc" id="link_desc"><?= $content->short_desc; ?></textarea></td><td style="font-size: 11px; color: #777777; padding-left: 10px;" valign="top"><img src="images/common/dot.gif" width="1" height="2" /><br />Observe o limite de caracteres e seja o mais objetivo possível.</td></tr>
        </table>
        </div>

        <div id="dbar">
        <div style="float: left"><img src="images/icons/step_02.gif" /></div>
        <div style="float: right">Selecione um diretório para o link</div>
        <b>Segmentação</b></div>
        <img src="images/common/dot.gif" width="1" height="10" /><br />

        <div id="content_rel" style="padding: 7px;">

        <div id="null" style="display: inline;"><b>x.are</b></div>
        <div id="sep" style="display: inline;"><span class="nav_sep">&raquo;</span></div>

        <? for($i = 0; $i < ($parents_cnt-1); $i++) { $rs = "lvl_$i"?>
        <div id="ddir_<?= $i ?>" style="display: inline;">

        <div style="display: inline;">
        <select name="dir_<?= $i ?>" id="dir_<?= $i ?>" onchange="parseDir(this)">
        <option value="0">Selecione</option>
        <option value="0" style="color: #dddddd">---------------</option>
	        <?php
	        foreach($$rs as $row) {
	        	$sel = ($row->id == $parents[$i]) ? " selected" : "";
	        	echo "<option value='".$row->id."'$sel>".$row->name."</option>";
	        }
	        ?>
        <option value="0" style="color: #dddddd">---------------</option>
        <option value="nd_<?= $i ?>" style="color: #ee2222"> + incluir grupo</option>
        </select>
        </div>

        <?php if($i != 0) { ?>
        <div style="display: inline; vertical-align: middle" id="remove_<?= $i ?>"><a href="javascript:removeDir(<?= $i ?>);"><img src="images/icons/dir_remove.gif" border="0" title="Remover este sub-grupo" /></a></div>
        <?php } ?>
        <?php if($i != ($parents_cnt - 1)) { ?>
        <div id="sep" style="display: inline;"><span class="nav_sep">&raquo;</span></div>
        <?php } ?>
        </div>
        <? } ?>

        <div style="display: none;" id="loader">
        <div style="display: inline; vertical-align: middle;" id="loader_img"><img src="images/ajax_loader-content.gif" /></div>
        <div id="sep" style="display: inline;"><span class="nav_sep">&raquo;</span></div>
        </div>

        <div id="new_dir" style="display: <?= ($showb == 0) ? "inline" : "none"; ?>">
        <input type="button" value=" + Sub-grupo " id="ndb" / ></div>
        <div id="new_dir_c" style="display: none;">
        <div id="new_dir_txt" style="display: inline; font-weight: bold"></div>
        <div style="display: inline; vertical-align: middle" id="remove_ndt"><a href="javascript:removeTyped(1);"><img src="images/icons/dir_remove.gif" border="0" title="Remover este sub-grupo" /></a></div>
        </div>

        </div>

        <img src="images/common/dot.gif" width="1" height="10" /><br />

        <div id="dbar">
        <div style="float: left"><img src="images/icons/step_03.gif" /></div>
        <div style="float: right">Confirme os dados antes de enviar</div>
        <b>Confirmação</b></div>

        <img src="images/common/dot.gif" width="1" height="4" /><br />
        <div style="padding: 6px; float: right">
        <input type="submit" value="Atualizar link" style="background-color: #99C145; border: 1px solid #69832F; padding: 6px; color: #ffffff; font-weight: bold" />
        </div>
        <div style="padding: 10px; font-size: 18px">
        <span style="color: #888888">Destino do link:</span> <b>x.are</b> / <span id="ldest"></span>
        </div>

        <img src="images/common/dot.gif" width="1" height="10" /><br />
        <input type="hidden" name="h_id" id="h_id" value="<?= $content->id; ?>" />
        <input type="hidden" name="h_parents" id="h_parents" value="" />
        <input type="hidden" name="n_parents" id="n_parents" value="" />
        <input type="hidden" name="h_cparents" id="h_cparents" value="<?= $content->parent; ?>" />
        </form>

        <img src="images/common/dot.gif" width="1" height="12" /><br />
        <div id="roots">
        <b>x.are</b> 2007 - Todos os direitos reservados. Leia nosso FAQ para mais informações sobre como utilizar a ferramenta.
        </div>

		</td>
	</tr>
</table>

<?php $this->load->view("common/layout_bottom"); ?>