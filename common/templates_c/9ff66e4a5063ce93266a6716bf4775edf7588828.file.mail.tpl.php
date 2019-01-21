<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-04-22 20:19:56
         compiled from "templates/mail.tpl" */ ?>
<?php /*%%SmartyHeaderCode:502319549552df5bb70f7b7-08950585%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9ff66e4a5063ce93266a6716bf4775edf7588828' => 
    array (
      0 => 'templates/mail.tpl',
      1 => 1429696946,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '502319549552df5bb70f7b7-08950585',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_552df5bb756e15_20464703',
  'variables' => 
  array (
    'email' => 0,
    'entry' => 0,
    'var' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_552df5bb756e15_20464703')) {function content_552df5bb756e15_20464703($_smarty_tpl) {?>
メールアドレス:
<?php echo $_smarty_tpl->tpl_vars['email']->value;?>


エントリー:
<?php  $_smarty_tpl->tpl_vars['var'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['var']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['entry']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['var']->key => $_smarty_tpl->tpl_vars['var']->value) {
$_smarty_tpl->tpl_vars['var']->_loop = true;
?>
<?php echo $_smarty_tpl->tpl_vars['var']->value;?>

<?php } ?><?php }} ?>
