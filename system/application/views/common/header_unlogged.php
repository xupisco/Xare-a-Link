<script type="text/javascript">
$(document).ready(function() {
    var options = {
        beforeSubmit: parseHeaderLogin,
        success: processResponse,
        url:     "ajax/users/validateuser",
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
        $("#hlog_f").css({ color: "red", border: "1px solid #ff0000" }).focus();
        $("#hpwd_f").css({ color: "red", border: "1px solid #ff0000" });
    } else {
        $("#div_load").fadeIn(500);
        window.location.reload();
    }
}
</script>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<form id="login_form" method="post">
    <tr>
        <td><img src="images/common/header_txt-login.gif" /></td>
        <td><input type="text" id="hlog_f" name="login" size="12" class="header_form" /></td>
        <td><img src="images/common/header_txt-passwd.gif" /></td>
        <td><input type="password" id="hpwd_f" name="passwd" size="12" class="header_form" /></td>
        <td><input type="submit" id="log_do" class="header_form-button" value="entrar" /></td>
        <td align="right"><a href="<?= base_url(); ?>users/new.html" rel="nofollow"><img src="images/common/header_txt-signup.gif" border="0" /></a></td>
    </tr>
    <input type="hidden" id="current_page" value="<?=str_replace("index.php/", "", $_SERVER['PHP_SELF']); ?>">
    </form>
</table>