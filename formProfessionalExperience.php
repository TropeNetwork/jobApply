<?php

require_once(OHR_LIB.'Category.php');
$category = new Category;

$form =& new HTML_QuickForm('form', 'POST');

if (!isset($_SESSION['formData'][$pageID]['index'])){
    $_SESSION['formData'][$pageID]['index']=0;
}
$index=&$_SESSION['formData'][$pageID]['index'];

//print "<br><h1>A index=$index CREATE FORM</h1>";

$form -> setConstants(array('index'        =>$index,
                            'employer_type'=>'soleEmployer'));
$form -> setDefaults($_SESSION['formData'][$pageID][$index]);

$form->addElement('header','personal',_("professional experiences"));
// description
$form->addElement('textarea',
                  'employer_position_desc',
                  _("description"),
                  array('id'   => 'employer_position_desc',
                        'rows' => '5'));

// company name
$form->addElement('text',
                  'employer_name',
                  _("company"), 
                  array('id'=>'employer_name'));

$form->addElement('hidden','index');
$form->addElement('hidden','employer_type');

// Grouped elements
$array['begin']  = &HTML_QuickForm::createElement('date','employer_position_begin',_("begin"),array('id'=>'employer_position_begin'));
$array['begin'] -> setFormat('MY');
$array['begin'] -> setMinYear(date('Y',time())-50);
$array['begin'] -> setMaxYear(date('Y',time()));
$array['end']    = &HTML_QuickForm::createElement('date','employer_position_end',_("end"),array('id'=>'employer_position_end'));
$array['end']   -> setFormat('MY');
$array['end']   -> setMinYear(date('Y',time())-50);
$array['end']   -> setMaxYear(date('Y',time()));
$form->addElementGroup($array, _("period"), 'date');

$form->addElement('select','employer_industry',_("industry"), $category->getChilds( CAT_INDUSTRY ), array('id'=>'employer_industry'));
$form->addElement('select','employer_position',_("position"), $category->getChilds( CAT_POSITION ), array('id'=>'employer_position'));
$form->addElement('submit','add','+', array('class'=>'apply'));
$form->addElement('submit','remove','-', array('class'=>'apply'));

/*
* define validating rules
*/
#$form->addRule('salutation', _("Please enter your salutation"), 'required');


/*
* set template variables for the list of professional experiences
*/
foreach($_SESSION['formData'][FORM_APPLY_PROFESSIONAL_EXPERIENCE] AS $key=>$val){
    if ($key!=='index'){
        $tpl->setVariable('list_employer_name', $val['employer_name']);
        $tpl->setVariable('list_number', $key+1);
        $tpl->setVariable('list_index', $key);
        $tpl->setVariable('list_period',sprintf(_("%02d/%04d till %02d/%04d"), 
                                         $val['date']['employer_position_begin']['M'],
                                         $val['date']['employer_position_begin']['Y'],
                                         $val['date']['employer_position_end']['M'],
                                         $val['date']['employer_position_end']['Y']));
        $tpl->setVariable('list_description', $val['employer_position_desc']);
        $tpl->parse('list');
    }
}

?>