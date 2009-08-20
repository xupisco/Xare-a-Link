<form id="login_form" method="post">
<div style="color: #FFD016; background-color: #373840; font-size: 12px; width: 100%">
    <img src="images/common/dot.gif" width="1" height="4" /><br />
    <?php if(!$this->session->userdata("uid")) { ?>
        <script src="<?= base_url(); ?>js/jquery_form.js" language="JavaScript"></script>
	    <script type="text/javascript">
		$(document).ready(function() {
		    var options = {
		        beforeSubmit: parseHeaderLogin,
		        success: processResponse,
		        url:     "ajax/validateUser",
		        type:    "POST"
		    }
		    $('#login_form').ajaxForm(options);
		});
		
		function parseHeaderLogin(formData, jqForm, options) {
		    $("#div_fmsg").hide();
		}
		
		function processResponse(responseText, statusText) {
		    if(responseText == "LOGIN_FAILED") {
		        $("<div id='div_fmsg'>Erro. Tente novamente!</div>").appendTo("body").fadeIn(500).click(function() {
		            $(this).fadeOut("slow");
		        });;
		        $("#hf_login").css({ backgroundColor: "red", color: "#ffffff" }).focus();
		        $("#hf_passwd").css({ backgroundColor: "red", color: "#ffffff" });
		    } else {
		        $("#div_load").fadeIn(500);
		        window.location.reload();
		    }
		}
		</script>
    
	    <div style="float:right">
	    <img src="images/common/dot.gif" width="1" height="2" /><br />
	    <a href="t/register" style="color: #eeeeee; text-decoration: none">É novo? <b>Cadastre-se!</b></a>
	    <img src="images/common/dot.gif" width="14" height="1" />
	    </div>
	    <img src="images/common/dot.gif" width="14" height="1" /><b style="color: #eeeeee">Já está registrado? </b> login: <input type="text" class="hf_input" name="login" id="hf_login" /> senha: <input type="password" class="hf_input" name="passwd" id="hf_passwd" /> <input type="submit" value="ok" />
    <?php } else { ?>
	    <script type="text/javascript">
	    function logOut() {
            $("#div_proc").fadeIn("fast");
            $.post("ajax/logOut/",
                    function(msg) {
                        $("#div_proc").hide();
                        $("#div_load").fadeIn("fast");
                        window.location.reload();
                    }
            );
	    }
	    </script>
    
        <div style="float:right">
        <a href="javascript:logOut();" style="color: #eeeeee; text-decoration: none"><b>Sair</b></a>
        <img src="images/common/dot.gif" width="14" height="1" />
        </div>
        <img src="images/common/dot.gif" width="14" height="1" />Olá, <b><?= $this->session->userdata("login"); ?></b> - <a href="u/<?= $this->session->userdata("login"); ?>" style="color: #ffffff; text-decoration: none">Seus links</a>        
    <? } ?>
    <br /><img src="images/common/dot.gif" width="1" height="4" />
</div>
</form>