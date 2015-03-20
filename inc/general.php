<?php
/**
 * Created by PhpStorm.
 * Date: 14-7-31
 * Time: 下午10:34
 * @overview 
 * @author Meatill <lujia.zhai@dianjoy.com>
 * @since 
 */
require_once dirname(__FILE__) . "/Spokesman.class.php";

/**
 *  检查是否登录
 */
function is_login() {
  $result = array(
    'is_login' => is_user_logged_in(),
    'code' => 0,
  );
  Spokesman::say($result);
}
add_action('wp_ajax_nopriv_is_login', "is_login");
add_action('wp_ajax_is_login', "is_login");

function ajax_login(){
  // First check the nonce, if it fails the function will break
  $check = check_ajax_referer('ajax-login-nonce', 'security', false);
  if (!$check) {
    exit(json_encode(array(
      'code' => 1,
      'msg' => '验证码错误',
    )));
  }

  // Nonce is checked, get the POST data and sign user on
  $info = array(
    'user_login' => $_POST['log'],
    'user_password' => $_POST['pwd'],
    'remember' => $_POST['rememberme'],
  );


  $user_signon = wp_signon($info);
  $success = !is_wp_error($user_signon);
  $error_msg = $success ? '' : $user_signon->get_error_message();
  Spokesman::judge($success, '登录成功', $error_msg);
  exit();
}
add_action('wp_ajax_nopriv_ajax_login', "ajax_login");
add_action('wp_ajax_ajax_login', "ajax_login");

/**
 * 要求Wordpress使用SMTP发送邮件
 * 从php角度来说这样就够了，不过有些SElinux里默认禁止php使用fsockopen连接外网
 * 所以需要运行 `setsebool -P httpd_can_network_connect 1` 解禁
 * @see http://yml.com/fv-b-1-619/selinux--apache-httpd--php-establishing-socket-connections-using-fsockopen---et-al.html
 * @param PHPMailer $phpmailer
 *
 * @param PHPMailer $phpmailer
 */
function configure_smtp(PHPMailer $phpmailer) {
  $phpmailer->isSMTP();
  $phpmailer->CharSet = 'UTF-8';
  $phpmailer->Host = 'smtp.exmail.qq.com';
  $phpmailer->SMTPAuth = true;
  $phpmailer->Username = 'service@xline.com.cn';
  $phpmailer->Password = MAIL_PASSWORD;
  $phpmailer->SMTPSecure = 'ssl';
  $phpmailer->Port = 465;
  $phpmailer->isHTML(true);

  $phpmailer->From = 'service@xline.com.cn';
  $phpmailer->FromName = 'XLINE客服';
}
add_action('phpmailer_init', 'configure_smtp');