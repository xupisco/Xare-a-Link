<script type="text/javascript">
function logOut() {
    $.post(site_path + "/ajax/users/logout",
            function(msg) {
                $("#div_load").show(500);
                window.location.reload();
            }
    );
}

$(document).ready(function() {
    broadcasting = true;
    $.post("ajax/broadcast/getNewMail",
       function(data){
            if(data != "0") {
                $("#libmail").attr({ src: "images/users/ico_h-libmail-on.gif", alt: "Você tem libMail(s) não lido(s)." });
            }
            broadcasting = false;
       }
    );
});

function hicon(o, t, a){
    o.style.cursor = 'pointer';
    txt = "#ico_" + t;
    if(t != "admin") {
        tc = (a) ? "#000000" : "#aaaaaa";
        $(txt).css({ color: tc });
    }
}

function readAll() {
    x = confirm("Isso marcará todos os textos enviados até agora como lidos.\nInclusive todos os fóruns e tópicos.\n\nDeseja continuar?");
    if(x) {
        $.post("ajax/users/allReaded",
            function(data){
                if(data != "0") {
                    document.location.reload();
                }
            }
        );
    }
}

function newContent() {
    document.location.href = site_path + "/stories/post";
}

function goU(l) {
    url = '/users/<?= $this->session->userdata('login'); ?>/';
    document.location.href = site_path + url + l + ".html";
}
</script>

<style>
.icons_txt {
    font-family: Verdana;
    letter-spacing: -1px;
    font-size: 9px;
    color: #aaaaaa;
}
</style>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td align="left" width="1%" nowrap style="font-size: 11px">Olá <b><?= $this->session->userdata('login'); ?></b>.</td>
        <td align="left" width="80%" nowrap>

        <table border="0" cellpadding="0" cellspacing="0"><tr>
        <td align="center" width="40" onclick="goU('index');" onmouseover="hicon(this, 'home', 1);" onmouseout="hicon(this, 'home', 0);"><img src="images/users/ico_h-home.gif" border="0" /><br /><span id="ico_home" class="icons_txt">home</span></td>
        <td align="center" width="40" onclick="newContent();" onmouseover="hicon(this, 'add', 1);" onmouseout="hicon(this, 'add', 0);"><img src="images/users/ico_h-add.gif" border="0" /><br /><span id="ico_add" class="icons_txt">incluir</span></td>
        <td align="center" width="40" onclick="goU('libmail/index');" onmouseover="hicon(this, 'libmail', 1);" onmouseout="hicon(this, 'libmail', 0);"><img src="images/users/ico_h-libmail.gif" id="libmail" border="0" /><br /><span id="ico_libmail" class="icons_txt">libMail</span></td>
        <td align="center" width="40" onclick="goU('blog/index');" onmouseover="hicon(this, 'liblog', 1);" onmouseout="hicon(this, 'liblog', 0);"><img src="images/users/ico_h-liblog.gif" border="0" /><br /><span id="ico_liblog" class="icons_txt">liBlog</span></td>
        <td align="center" width="40" onclick="goU('games/index');" onmouseover="hicon(this, 'games', 1);" onmouseout="hicon(this, 'games', 0);"><img src="images/users/ico_h-games.gif" border="0" /><br /><span id="ico_games" class="icons_txt">games</span></td>
        <td align="center" width="40" onclick="goU('amigos/index');" onmouseover="hicon(this, 'friends', 1);" onmouseout="hicon(this, 'friends', 0);"><img src="images/users/ico_h-friends.gif" border="0" /><br /><span id="ico_friends" class="icons_txt">amigos</span></td>
        <td align="center" width="40" onclick="goU('participacao/index');" onmouseover="hicon(this, 'history', 1);" onmouseout="hicon(this, 'history', 0);"><img src="images/users/ico_h-history.gif" border="0" /><br /><span id="ico_history" class="icons_txt">arquivo</span></td>
        <td align="center" width="40" onclick="goU('editar/index');" onmouseover="hicon(this, 'edit', 1);" onmouseout="hicon(this, 'edit', 0);"><img src="images/users/ico_h-edit.gif" border="0" /><br /><span id="ico_edit" class="icons_txt">editar</span></td>
        <?php if($this->session->userdata('admin')) { ?>
        <td align="center" width="40" onclick="document.location.href='admin/index.html';" onmouseover="hicon(this, 'admin', 1);" onmouseout="hicon(this, 'admin', 0);"><img src="images/users/ico_h-admin.gif" border="0" /><br /><span id="ico_admin" class="icons_txt" style='color: #990000'>admin</span></td>
        <?php } ?>
        </tr></table>

        </td>

        <td align="right">

        <table width="80" border="0" cellpadding="0" cellspacing="0"><tr>
        <td align="center" width="40" onclick="readAll();"onmouseover="hicon(this, 'readed', 1);" onmouseout="hicon(this, 'readed', 0);"><img src="images/users/ico_h-readed.gif" border="0" /><br /><span id="ico_readed" class="icons_txt">ler tudo</span></td>
        <td align="center" width="40" onclick="logOut();" onmouseover="hicon(this, 'logout', 1);" onmouseout="hicon(this, 'logout', 0);"><img src="images/users/ico_h-logout.gif" border="0" /><br /><span id="ico_logout" class="icons_txt">sair</span></td>
        </tr></table>

        </td>
    </tr>
</table>