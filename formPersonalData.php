<?php

$form =& new HTML_QuickForm('form', 'POST');
$form -> setConstants(array('affix_type'=>'formOfAddress'));
$form -> setDefaults($_SESSION['formData'][$pageID]);

$form->addElement('header','personal',_("application form"));
$form->addElement('hidden','affix_type');

// Grouped elements
$array['mr']  = &HTML_QuickForm::createElement('radio',null, null, _("Mr."),'m');
$array['mrs'] = &HTML_QuickForm::createElement('radio',null, null, _("Mrs.") ,'f');
$form->addElementGroup($array, _("Salutation"), 'salutation', ' &nbsp; ');

unset($array);
$array['firstname'] = &HTML_QuickForm::createElement('text',
                                        'firstname',
                                        _("firstname"),
                                        array('maxlength'=>'50'));
$array['lastname']  = &HTML_QuickForm::createElement('text',
                                        'lastname',
                                        _("lastname"),
                                        array('maxlength'=>'50'));
$form->addElementGroup($array, _("Name"), 'person', ' &nbsp; ');


$element=&$form->addElement('text', 'street', _("street"));
$element->setSize(30);
$element->setMaxLength(50);

$elements['zip'] = &HTML_QuickForm::createElement('text', 'zip', _("Zip"));
$elements['zip']->setMaxLength(10);
$elements['zip']->setSize(6);

$elements['city'] = &HTML_QuickForm::createElement('text', 'city', _("City"));
$elements['city']->setSize(21);
$elements['city']->setMaxLength(50);
$form->addElementGroup($elements, _("Zip/City"),'location');

$element=&$form->addElement('text','phone',_("phone"));
$element->setSize(30);
$element->setMaxLength(50);

$element=&$form->addElement('text','mobile',_("mobile"));
$element->setSize(30);
$element->setMaxLength(50);

$element=&$form->addElement('text','email',_("email"));
$element->setSize(30);
$element->setMaxLength(50);

$form->addElement('textarea','objective',_("summary"));

/*
* define validating rules
*/
$form->addGroupRule('person', _("Please enter your lastname"), 'required');
$form->addRule('lastname', _("Please enter your lastname"), 'required');
$form->addRule('firstname', _("Please enter your firstname"), 'required');
#$form->addRule('salutation', _("Please enter your salutation"), 'required');
$form->addRule('email', _("The entered email adress is not valid"), 'email');
$form->addRule('email', _("Please enter your email adress."), 'required');

?>