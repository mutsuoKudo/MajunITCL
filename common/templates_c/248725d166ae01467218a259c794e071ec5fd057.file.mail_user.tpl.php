<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-05-16 17:09:35
         compiled from "templates/mail_user.tpl" */ ?>
<?php /*%%SmartyHeaderCode:475961855552df5bb75a987-72002550%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '248725d166ae01467218a259c794e071ec5fd057' => 
    array (
      0 => 'templates/mail_user.tpl',
      1 => 1431763735,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '475961855552df5bb75a987-72002550',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_552df5bb75b731_89119521',
  'variables' => 
  array (
    'email' => 0,
    'entry' => 0,
    'var' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_552df5bb75b731_89119521')) {function content_552df5bb75b731_89119521($_smarty_tpl) {?>
この度はエンジニアラボからのエントリーありがとうございました。
担当マネージャーよりご連絡させていただきます。

【ご登録内容】
メールアドレス：
<?php echo $_smarty_tpl->tpl_vars['email']->value;?>


エントリー：
<?php  $_smarty_tpl->tpl_vars['var'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['var']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['entry']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['var']->key => $_smarty_tpl->tpl_vars['var']->value) {
$_smarty_tpl->tpl_vars['var']->_loop = true;
?>
<?php echo $_smarty_tpl->tpl_vars['var']->value;?>

<?php } ?>

----------------------------------------------
ITエンジニア、SEの方たち専門のマッチングサイト
http://it-enlabo.com

<?php }} ?>
