<?php
$_seo['is_home'] = true;
$this->load->view("common/layout_top", $_seo);
?>

<script src="<?= base_url(); ?>js/jquery_tag.js" language="JavaScript"></script>

<script type="text/javascript">
function toggleBG(i, e) {
    if(i) { $(e).css({ backgroundColor: '#F2FFE8' }); }
    else { $(e).css({ backgroundColor: '#ffffff' }); }
}

function showTools(n, e) {
    tb = "#tools_" + n;
    if(!<?= $this->session->userdata('uid'); ?>) e = 0;
    if(e) { $(tb).show();
	} else { $(tb).hide(); }
}

function newComment() {
    $("#new_comment-form").slideToggle();
}

function parseCommentForm() {
    if(!$("#cmm_comment").val()) {
        alert("O comentário é obrigatório!! D'uh!");
        return false;
    }
    return true;
}

function cb(e, s) {
    e.src = (s) ? "images/b_oks-on.gif" : "images/b_oks-off.gif";
    e.style.cursor = (s) ? "pointer" : "";
}

function doSearch() {
    if(!$("#search").val()) {
        alert("D'uh!!!");
        return false;
    }
    document.location.href = 's/' + $("#search").val();
    return false;
}

function u(s, i) {
    s = (!s) ? false : s;
    if(s == "save") {
        $.post("ajax/uSaveLink/", { id: (i) },
	       function(data){
                if(data == "OK") {
                    fav = "#fav_" + i;
                    $(fav).fadeIn();
					tagd = "#tagd_" + i;
					$(tagd).slideDown("fast");
                } else {
                    alert("Ops... alguma coisa deu errada!");
                }
	       }
	    );
    }
    if(s == "remove") {
        $.post("ajax/uRemoveLink/", { id: (i) },
	       function(data){
                if(data == "OK") {
                    fav = "#fav_" + i;
                    $(fav).fadeOut();
                } else {
                    alert("Ops... alguma coisa deu errada!");
                }
	       }
	    );
    }
    if(s == "tag") {
   		tagi = "#tag_" + i;
		$.post("ajax/getCurrentTags/", { cid: (i) },
	       function(data){
				$(tagi).val(data);
				// Criar array com elementos criados e validar antes de incluir novamente.
				$(tagi).unbind();
			    $(tagi).tagSuggest({
					url: 'ajax/getUserTags',
					tagContainer: "div",
					delay: 750
			    });
	       }
	    );
		tagd = "#tagd_" + i;
		$(tagd).slideToggle("fast");
    }
}

function saveTags(i) {
	tagi = "#tag_" + i;
	msgi = "#msg_" + i;
	$(msgi).html(" Salvando...");
	$(msgi).fadeIn();
	
	ntags = $(tagi).val();
	$.post("ajax/saveTags/", { cid: (i), tags: (ntags) },
       function(data){
			$(msgi).html(" Pronto!");
			$(msgi).fadeOut().fadeIn().fadeOut().fadeIn().fadeOut().fadeIn();
			$(msgi).click(function(){$(this).fadeOut();});
       }
    );
	tagd = "#tagd_" + i;
}
</script>

<style>
DIV.tagMatches {
    margin: 8px 0px 0px 30px;
	position: absolute;
	font-size: 11px;
}

DIV.tagMatches SPAN {
    padding: 3px;
    margin-right: 4px;
    background-color: #0000AB;
    color: #fff;
    cursor: pointer;
}

DIV.tagMatches SPAN:hover {
    background-color: #AB0000;
}
</style>

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
        <td width="1%"><select name="qt" class="main_select"><option value="all">Em qualquer lugar</option><option value="">Apenas em: <?= $content->name ?>&nbsp;</option></select></td>
        <td width="1%"><img src="images/b_oks-off.gif" onmouseover="cb(this, 1);" onmouseout="cb(this, 0);" border="0" onclick="javascript:doSearch();" /></td>
        </tr>
        </form>
        </table>
        </div>

        <img src="images/common/dot.gif" width="1" height="12" /><br />

		<table width="100%" cellpadding="0" cellspacing="0">
			<tr>
				<td class="str_nav">
				<div style="float: right; margin-top: 6px">
				<table><tr>
				<?php
				    if($this->session->userdata("admin") || ($content->parent && ($content->user_id && ($this->session->userdata("uid") == $content->user_id)))):
				?>
				<td><a href="editlink/rel=<?= $content->id; ?>" rel="nofollow" style="color: red">[edit]</a>&nbsp;</td>
                <?php endif; ?>
                <?php if($this->session->userdata("uid")): ?>
                <td><a href="newlink/rel=<?= $content->id; ?>" rel="nofollow"><img src="images/icons/add_link.gif" border="0" /></a></td>
				<td><a href="newlink/rel=<?= $content->id; ?>" rel="nofollow">Incluir link</a>&nbsp;</td>
				<?php endif; ?>
				</tr></table>
				</div>
				<img src="images/common/dot.gif" width="2" height="1" /><a href="<?= base_url(); ?>"><b>x.are</b></a>
				<?php
				$nav_link = "";
				if(count($previous)) {
					foreach($previous as $row) {
						$nav_link .= $row[1]."/";
						echo " <span class='nav_sep'>&raquo;</span> ";
						echo "<a href='".$nav_link."'>".$row[0]."</a>";
					}
				}
				?>
				<span class="nav_sep">&raquo;</span>
				<a href="<?= $nav_link; ?><?= $content->name_url; ?>" id="content_name"><?= $content->name ?></a>
                <?php
                $fav = ($content->ul_id) ? "" : "none";
                echo '<span style="display: '.$fav.';" id="fav_'.$content->id.'"><a href="javascript:u(\'remove\','.$content->id.');"><img src="images/icons/fav.gif" border="0" title="Está nos seus favoritos. Clique para remover." /></a></span>';
                ?>
                <img src="images/common/dot.gif" width="2" height="1" />
                <?php
                if($this->session->userdata('uid')) {
	                echo '<span class="tools_box-on">';
	                echo '<a href="javascript:u(\'save\','.$content->id.');"><img src="images/icons/mini_save.gif" title="Salvar nos seus links" border="0" /></a><img src="images/common/dot.gif" width="2" height="1" />';
	                echo '<a href="javascript:u(\'tag\','.$content->id.');"><img src="images/icons/mini_tag.gif" title="Criar/Editar etiquetas" border="0" /></a><img src="images/common/dot.gif" width="1" height="1" />';
	                echo '<img src="images/icons/mini_vote.gif" title="Votar neste link" /><img src="images/common/dot.gif" width="2" height="1" />';
	                echo '<img src="images/icons/mini_report.gif" title="Denunciar" />';
	                echo '</span>';
                }
				echo "<div class='tag_input' style='display: none; padding-bottom: 5px' id='tagd_".$content->id."'>Tags: <input type='text' id='tag_".$content->id."' class='tag_select' /> <input type='button' value='salvar' class='tagsave' style='width: 50px' onclick='saveTags(".$content->id.")' /> <input type='button' value='X' class='tagsave' style='width: 15px; background-color: #9F0000;' onclick='u(\"tag\",".$content->id.")' /><span id='msg_".$content->id."' style='color: red; display: none'></span></div>";
                ?>
				</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td class="default_content" valign="top" align="left">
    	<?php if(strlen($content->short_desc)) { ?>
    	<div id="content_desc" style="padding-left: 2px"><?= $content->short_desc ?></div>
    	<?php } ?>

		<?php
			if($tags) {
				echo "<div id='tag_cloud'>";
				echo "<img src='images/icons/tags.gif' align='left' alt='Tags' title='Tags' />";
				$base_size = 11;
				foreach($tags as $row) {
					$tag_size = (($base_size + $row->rel) > 20) ? 20 : $base_size + $row->rel;
					$tag_size += $row->rel * $row->rel - 1;
					$tag_size = ($tag_size > 20) ? 20 : $tag_size;
					echo "<a href='tag/".$row->tag_url."' class='tag'><span style='font-size: ".$tag_size."px'>".$row->tag."</span></a>\n";
				}
				echo "</div>";
			}
		?>

    	<img src="images/common/dot.gif" width="1" height="6" /><br />
    	<div id="content_desc" style="color: #000000; padding: 3px;">
        <div style="float: right">
        <b>Avaliação:</b> <?= $content->score ?> -
        <b>Clicks:</b> <?= $content->clicks ?> -
        <b>Visitas:</b> <?= $content->views ?> -
        <b>Usuários:</b> <?= $content->users ?> -
        <b>Coment.:</b> <?= $content->comments ?> -
        <b>Data:</b> <?= date("d/m/Y", $content->date_added) ?>
        </div>
        <?php if(strlen($content->link) && $content->link != "http://" && $content->link != "0") { ?>
        <b>URL:</b> <a href="go/<?= $content->id ?>" ><?= $content->link ?> <img src="images/external.png" border="0" /></a> - <b>PageRank:</b> <div style="display: inline; vertical-align: middle"><?= pageRank($content->pagerank, 40, "image") ?></div> (<?= $content->pagerank ?>/10)
        <?php } ?>
        &nbsp;
    	</div>

        <?php if($related->result()) { ?>
        <img src="images/common/dot.gif" width="1" height="6" /><br />
        <div id="dbar">
        <b>Listagem de links</b> em <?= $content->name; ?></div>

        <div id="content_rel">
		<table width="100%" cellpadding="3">
        <tr style="background-color: #f6f6f6; font-size: 11px">
		    <td><a href="<?= $base; ?>" rel="nofollow" title="Ordenar por pastas" alt="Ordenar por pastas"><img src="images/icons/folder_sort<?= ($sort_1 == "related") ? "" : "-off"; ?>.gif" border="0" /></a><a href="<?= $base; ?>/page:<?= $page; ?>/sort:name" class="lsorter<?= ($sort_1 == "name") ? "_sel" : ""; ?>" rel="nofollow" title="Ordenar por nome" alt="Ordenar por nome">Nome</a></td>
			<td align="center"><a href="<?= $base; ?>/page:<?= $page; ?>/sort:pagerank" class="lsorter<?= ($sort_1 == "pagerank") ? "_sel" : ""; ?>" rel="nofollow" title="Ordenar por PageRank" alt="Ordenar por PageRank">PageRank</a></td>
			<td align="center"><a href="<?= $base; ?>/page:<?= $page; ?>/sort:score" class="lsorter<?= ($sort_1 == "score") ? "_sel" : ""; ?>" rel="nofollow" title="Ordenar por avaliação" alt="Ordenar por avaliação">Avaliação</a></td>
			<td align="center"><a href="<?= $base; ?>/page:<?= $page; ?>/sort:clicks" class="lsorter<?= ($sort_1 == "clicks") ? "_sel" : ""; ?>" rel="nofollow" title="Ordenar por cliques" alt="Ordenar por cliques">Cliques</a></td>
			<td align="center"><a href="<?= $base; ?>/page:<?= $page; ?>/sort:views" class="lsorter<?= ($sort_1 == "views") ? "_sel" : ""; ?>" rel="nofollow" title="Ordenar por visitas" alt="Ordenar por visitas">Visitas</a></td>
			<td align="center"><a href="<?= $base; ?>/page:<?= $page; ?>/sort:users" class="lsorter<?= ($sort_1 == "users") ? "_sel" : ""; ?>" rel="nofollow" title="Ordenar por usuários" alt="Ordenar por usuários">Usuários</a></td>
			<td align="right"><a href="<?= $base; ?>/page:<?= $page; ?>/sort:date" class="lsorter<?= ($sort_1 == "date") ? "_sel" : ""; ?>" rel="nofollow" title="Ordenar por data" alt="Ordenar por data">Data</a></td>
		</tr>
        <?php
        foreach($related->result() as $row) {
            $rel = ($row->related) ? "<span class='cnt_related'> (".$row->related.")</span>" : "";
			$folder = ($row->related) ? "<img src='images/icons/folder.gif' align='left' border='0' />" : "";
            echo "<tr onmouseover='toggleBG(1,this);' onmouseout='toggleBG(0,this);'><td onmouseover='showTools(".$row->id.", 1)' onmouseout='showTools(".$row->id.", 0)' width='85%' style='padding: 6px 3px;'>";
            echo "<div class='tools_box' id='tools_".$row->id."'>";
            echo '<a href="javascript:u(\'save\','.$row->id.');"><img src="images/icons/mini_save.gif" title="Salvar nos seus links" border="0" /></a><img src="images/common/dot.gif" width="2" height="1" />';
            echo '<a href="javascript:u(\'tag\','.$row->id.');"><img src="images/icons/mini_tag.gif" title="Criar/Editar etiquetas" border="0" /></a><img src="images/common/dot.gif" width="1" height="1" />';
            echo '<img src="images/icons/mini_vote.gif" title="Votar neste link" /><img src="images/common/dot.gif" width="2" height="1" />';
            echo '<img src="images/icons/mini_report.gif" title="Denunciar" />';
            echo "</div>";
            $fav = ($row->ul_id) ? "" : "none";
            echo '<div style="float: left; margin-top: 1px; display: '.$fav.'" id="fav_'.$row->id.'"><a href="javascript:u(\'remove\','.$row->id.');"><img src="images/icons/fav.gif" border="0" title="Está nos seus favoritos. Clique para remover." /></a></div>';
			echo "<a href='".$base."/".$row->name_url."'>".$folder.$row->name."</a>";
            if($row->link != "0" && $row->link != "http://") {
                echo "&nbsp;<a href='go/".$row->id."'><img src='images/external.png' border='0' /></a>";
            }
            echo $rel;
            //echo "<div class='tag_input'><span class='curr_tags'>ovo, pão, salame</span></div>";
            if(strlen($row->short_desc)) {
                echo "<br><span style='font-size: 11px; color: #555555'>".$row->short_desc."</span>";
            }
			echo "<div class='tag_input' style='display: none' id='tagd_".$row->id."'>Tags: <input type='text' id='tag_".$row->id."' class='tag_select' /> <input type='button' value='salvar' class='tagsave' style='width: 50px' onclick='saveTags(".$row->id.")' /> <input type='button' value='X' class='tagsave' style='width: 15px; background-color: #9F0000;' onclick='u(\"tag\",".$row->id.")' /><span id='msg_".$row->id."' style='color: red; display: none'></span></div>";
            echo "</td>";
            echo "<td align='center'>".pageRank($row->pagerank)."</td>";
            echo "<td class='link_info' title='Avaliação'>".$row->score."</td>";
            echo "<td class='link_info' title='Cliques'>".$row->clicks."</td>";
            echo "<td class='link_info' title='Visitas'>".$row->views."</td>";
            echo "<td class='link_info' title='Usuários'>".$row->users."</td>";
            echo "<td class='link_info' title='Data do cadastro' style='text-align: right' nowrap>".date("d/m/Y", $row->date_added)."</td>";
            echo "</tr>";
        }
        ?>
        </table>
        <?php } ?>
        </div>

		<img src="images/common/dot.gif" width="1" height="8" /><br />
		<div id="dbar" style="border-bottom: 1px solid #ee2222">
		<div style="padding-top: 1px; float: right"><img src="images/common/feed.png" border="0" /></div>
        <table cellpadding="0" cellspacing="0"><tr>
        <td><b>Comentários</b> (<?= ($comments) ? $comments->num_rows() : "0" ?>) &nbsp;</td>
        <?php if($this->session->userdata("uid")) { ?>
        <td><a href="javascript:newComment();"><img src="images/icons/mini_add-link.gif" border="0" /></a></td>
        <td><a href="javascript:newComment();" class="mini_link">incluir comentário</a>&nbsp;</td>
        <?php } else { ?>
        <td style="font-size: 11px"><span style="color: red">Cadastre-se ou faça login para comentar</span></td>
        <?php } ?>
        </tr></table>
        </div>

        <div id="new_comment-form" style="display: none; padding: 6px">
        <table width="100%">
        <form action="comment" method="post" onsubmit="return parseCommentForm()">
        <tr>
        <td width="1%" nowrap="nowrap">Nome:</td>
        <td width="15%"><input type="text" name="cmm_name" id="cmm_name" style="width: 100%; padding: 2px" /></td>
        <td width="1%" nowrap="nowrap">&nbsp;&nbsp;E-mail:</td>
        <td width="15%"><input type="text" name="cmm_email" id="cmm_email" style="width: 100%; padding: 2px" /></td>
        <td width="1%" nowrap="nowrap">&nbsp;&nbsp;Comentário:</td><td nowrap="nowrap"><input type="text" name="cmm_comment" id="cmm_comment" style="width: 100%; padding: 2px" /></td>
        <td width="1%" nowrap="nowrap">&nbsp;&nbsp;<input type="submit" value="Enviar" /></td>
        </tr>
        <input type="hidden" name="cmm_content_id" value="<?= $content->id ?>" />
        </form>
        </table>
        </div>

        <?php if($comments): ?>
        <img src="images/common/dot.gif" width="1" height="3" /><br />
        <div id="comments" style="padding: 6px">
            <?php
            foreach($comments->result() as $cmm) {
            	echo "<div style='padding: 3px 0px'><table cellpadding='0' cellspacing='0'><tr><td nowrap width='1%' valign='top'>";
            	$u = explode(":", $cmm->foo_user);
                $email = (!$cmm->user_id) ? "<span style='color: #888888;'>[".$u[1]."]</span>" : "";

                echo "<img src='images/icons/arrow_right.gif' /> <b>".$u[0]."</b> $email: </td>";
            	echo "<td><div style='float: right; color: #888888; font-size: 11px;'>(".date("d/m/Y H:i", $cmm->date_added).")</div>";
                echo $cmm->comment;
                echo "</td></tr></table></div>";
            }
            ?>
        </div>
        <?php endif; ?>

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