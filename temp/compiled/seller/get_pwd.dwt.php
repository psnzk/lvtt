<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><?php echo $this->fetch('library/seller_html_head.lbi'); ?></head>
<body>
<div class="sller_login">
    <div class="sller_login_warp">
        <?php if ($this->_var['form_act'] == "forget_pwd"): ?>
        <form action="get_password.php" method="post" name="submitAdmin" onsubmit="return validate()">
        <div class="ecsc-login-container">
            <div class="login-left">
                <h3>商家管理中心欢迎您.</h3>
                <span>请输入你管理员注册时的邮箱，找回密码链接会发送到您的邮箱里</span>
            </div>
            <div class="login-right">
                <div class="login-logo"><img src="images/V1.png" /></div>
                <div class="items">
                    <div class="item item_bor mb10">
                        <b><img src="images/login_icon01.png" /></b>
                        <input type="text" name="user_name" class="text valid" placeholder="<?php echo $this->_var['lang']['user_name']; ?>"/>
                        <i></i>
                    </div>
                    <div class="item item_bor mb10">
                        <b><img src="images/login_icon02.png" /></b>
                        <input type="text" name="email" class="text valid" placeholder="<?php echo $this->_var['lang']['email']; ?>"/>
                        <i></i>
                    </div>
                    <div class="item mt30">
                        <input type="hidden" name="action" value="get_pwd" />
                        <input type="hidden" name="act" value="forget_pwd" />
                        <input type="reset" value="<?php echo $this->_var['lang']['reset_button']; ?>" class="login-submit login-reset" />
                        <input type="submit" value="<?php echo $this->_var['lang']['click_button']; ?>" class="login-submit mr20" />
                    </div>
                </div>
            </div>
        </div>
        </form>
        <?php endif; ?>
        <?php if ($this->_var['form_act'] == "reset_pwd"): ?>
        <form action="get_password.php" method="post" name="submitAdmin" onsubmit="return validate()">
        <div class="ecsc-login-container">
            <div class="login-left">
                <h3>商家管理中心欢迎您.</h3>
                <span>请输入你管理员注册时的邮箱，找回密码链接会发送到您的邮箱里</span>
            </div>
            <div class="login-right">
                <div class="login-logo"><img src="images/V1.png" /></div>
                <div class="items">
                    <div class="item item_bor mb10">
                        <b><img src="images/login_icon02.png" /></b>
                        <input type="password" name="password" class="text valid" placeholder="<?php echo $this->_var['lang']['enter_admin_pwd']; ?>"/>
                        <i></i>
                    </div>
                    <div class="item item_bor mb10">
                        <b><img src="images/login_icon02.png" /></b>
                        <input type="password" name="confirm_pwd" class="text valid" placeholder="<?php echo $this->_var['lang']['confirm_admin_pwd']; ?>"/>
                        <i></i>
                    </div>
                    <div class="item mt30">
                        <input type="hidden" name="action" value="reset_pwd" />
                        <input type="hidden" name="act" value="forget_pwd" />
                        <input type="hidden" name="adminid" value="<?php echo $this->_var['adminid']; ?>" />
                        <input type="hidden" name="code" value="<?php echo $this->_var['code']; ?>" />
                        <input type="reset" value="<?php echo $this->_var['lang']['reset_button']; ?>" class="login-submit login-reset" />
                        <input type="submit" value="<?php echo $this->_var['lang']['click_button']; ?>" class="login-submit mr20" />
                    </div>
                </div>
            </div>
        </div>
        </form>
        <?php endif; ?>
    </div>
</div>
<script type="text/javascript">
<!--
/**
* 检查表单输入的数据
*/
function validate()
{
  validator = new Validator("submitAdmin");
  validator.required("user_name", user_name_empty);
  validator.required("email", email_empty, 1);
  validator.isEmail("email", email_error);

  return validator.passed();
}

function validate2()
{
  validator = new Validator("submitPwd");
  validator.required("password",            admin_pwd_empty);
  validator.required("confirm_pwd",         confirm_pwd_empty);
  if (document.forms['submitPwd'].elements['confirm_pwd'].value.length > 0)
  {
    validator.eqaul("password","confirm_pwd", both_pwd_error);
  }

  return validator.passed();
}
//-->
</script>

</body>
</html>