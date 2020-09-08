<?php
/* Smarty version 3.1.36, created on 2020-06-16 11:56:10
  from '/home/rene/PhpstormProjects/MVC/templates/dist/login.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.36',
  'unifunc' => 'content_5ee8973ab61042_91925930',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '29f3d157367aed7e6a7d2fee8ada3a595f85534f' => 
    array (
      0 => '/home/rene/PhpstormProjects/MVC/templates/dist/login.tpl',
      1 => 1592301368,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5ee8973ab61042_91925930 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_12765899845ee8973ab566d4_94389526', "baselayout");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_3445221915ee8973ab59879_46490965', "body");
$_smarty_tpl->inheritance->endChild($_smarty_tpl, "basic.tpl");
}
/* {block "baselayout"} */
class Block_12765899845ee8973ab566d4_94389526 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'baselayout' => 
  array (
    0 => 'Block_12765899845ee8973ab566d4_94389526',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block "baselayout"} */
/* {block "body"} */
class Block_3445221915ee8973ab59879_46490965 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'body' => 
  array (
    0 => 'Block_3445221915ee8973ab59879_46490965',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

    <body>
    <section class="page-section" id="login">
        <div class="container">
            <div class="text-center">
                <h2 class="section-heading text-uppercase">Login Area</h2>
                <h3 class="section-subheading text-muted">I am not a robot. I am not a monkey. I will not dance, even if
                    the beat is funky.</h3>
            </div>
            <form novalidate="novalidate" method="post" id="contactForm" name="sentMessage">
                <?php if ((isset($_smarty_tpl->tpl_vars['loginMessage']->value))) {?>
                    <?php echo $_smarty_tpl->tpl_vars['loginMessage']->value;?>

                <?php }?>
                <div class="row align-items-stretch mb-5">
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="hidden" name="page" value="backend">
                            <input class="form-control" id="username" type="email" name="username"
                                   placeholder="Your E-Mail *" required="required"
                                   data-validation-required-message="Please enter your email address."/>
                            <p class="help-block text-danger"></p>
                        </div>
                        <div class="form-group">
                            <input class="form-control" id="password" type="password" name="password"
                                   placeholder="Your Passwort *" required="required"
                                   data-validation-required-message="Please enter your email address."/>
                            <p class="help-block text-danger"></p>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <div id="login">
                        <button class="btn btn-primary btn-xl text-uppercase" id="login"
                                name="login" type="submit" value="login">
                            Login
                    </div>
                </div>
            </form>

            <div class="text-center">

                    <a href="/Index.php?cl=login&page=reset&admin=true" class="list-group-item list-group-item-action">Forgotten Password</a>

                    </div>
                </div>
            </div>

        </div>
    </section>
    </body>
<?php
}
}
/* {/block "body"} */
}
