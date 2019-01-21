<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-04-14 08:48:31
         compiled from "templates\mail.tpl" */ ?>
<?php /*%%SmartyHeaderCode:14734552c4f890ee249-84800573%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'fd449eaa36f48c69325926c89a950082dcdcfd41' => 
    array (
      0 => 'templates\\mail.tpl',
      1 => 1428968909,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '14734552c4f890ee249-84800573',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_552c4f89144154_26408640',
  'variables' => 
  array (
    'email' => 0,
    'entry' => 0,
    'var' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_552c4f89144154_26408640')) {function content_552c4f89144154_26408640($_smarty_tpl) {?>
メールアドレス:<?php echo $_smarty_tpl->tpl_vars['email']->value;?>

エントリー:
<?php  $_smarty_tpl->tpl_vars['var'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['var']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['entry']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['var']->key => $_smarty_tpl->tpl_vars['var']->value) {
$_smarty_tpl->tpl_vars['var']->_loop = true;
?>
<?php echo $_smarty_tpl->tpl_vars['var']->value;?>

<?php } ?><?php }} ?>
