<?php

include_once "prepend.inc";
include_once OPENHR_LIB."/Form.php";

$page=&Page::singleton("application");

$key=importVar("key");


$form=new Form("application");
$form->addHeader(_("Bewerbungsformular"));

if ($LoginManager->isLoggedIn()){
    print "<center><b>you are logged in</b></center>";
}else{
    print "<center><b>you are logged in</b></center>";
}

$form->addElement("text","firstname",_("firstname"));
$form->addElement("text","lastname",_("lastname"));
$form->addElement("text","zip",_("zip"));
$form->addElement("text","city",_("city"));
$form->addElement("text","street",_("street"));
$form->addElement("text","phone",_("phone"));
$form->addElement("text","email",_("email"));
$form->addElement("textarea","summary",_("summary"));
$form->addElement("submit","submit",_("send application"));

/*
* define validating rules
*/
$form->addRule('lastname', _("Please enter your lastname"), 'required');
$form->addGroupRule('email', _("the entered email is not valid."), 'emailorblank');

/*
* define Filter rules
*/
$form->applyFilter('__ALL__', 'trim');

if ($form->validate()) {
  // TODO
}

$template_dir="applications/";

$smarty->assign("page",Page::getSlots());
$smarty->assign("key",$key);
$smarty->assign("form",$form->toHtml());

$smarty->display($template_dir.'generic.tpl');


?>
