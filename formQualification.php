<?php

require_once(OHR_LIB.'Category.php');

$category =& new Category;
$form     =& new HTML_QuickForm('form', 'POST');
$data     = $_SESSION['formData'][$pageID];

$form -> setDefaults($_SESSION['formData'][$pageID]);

$form->addElement('header','personal',_("qualifications"));

$form->addElement('select',
                  'languageSkillsNative',
                  _("native Language"),
                  $category->getChilds( CAT_LANGUAGE ));

$form->addElement('select',
                  'languageSkillsPerfect',
                  _("perfect skills"),
                  $category->getChilds( CAT_LANGUAGE ),
                  array('multiple'=>'multiple',
                        'size'    =>'4',
                        'class'   =>'languageBox'));

$form->addElement('select',
                  'languageSkillsGood',
                  _("good skills"),
                  $category->getChilds( CAT_LANGUAGE ),
                  array('multiple'=>'multiple',
                        'size'    =>'4',
                        'class'   =>'languageBox'));

$form->addElement('select',
                  'languageSkillsBasic',
                  _("basic skills"),
                  $category->getChilds( CAT_LANGUAGE ),
                  array('multiple'=>'multiple',
                        'size'    =>'4',
                        'class'   =>'languageBox'));

$form->addElement('textarea',
                  'computerSkills',
                  _("computer skills"),
                  array('rows' => '4',
                        'id'   => 'computerSkills'));

$form->addElement('textarea',
                  'additionalSkills',
                  _("additional skills"),
                  array('rows' => '4',
                        'id'   => 'additionalSkills'));

/*
* define validating rules
*/
$form->addRule('languageSkillsNative', _("Please enter your native language"), 'required');

?>