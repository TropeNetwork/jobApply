<?php
require_once(OHR_LIB.'Category.php');

$category =& new Category;

if (!isset($_SESSION['formData'][$pageID]['index'])){
    $_SESSION['formData'][$pageID]['index']=0;
}

$index=&$_SESSION['formData'][$pageID]['index'];

$form =& new HTML_QuickForm('form', 'POST');

$form -> setConstants(array('index'        =>$index));

$form -> setDefaults($_SESSION['formData'][$pageID][$index]);

$form->addElement('header','personal',_("Education"));

// university degree (Grouped elements)
$array['y']  = &HTML_QuickForm::createElement('radio',null, null, _("yes"),'y');
$array['n']  = &HTML_QuickForm::createElement('radio',null, null, _("no") ,'n');
$form->addElementGroup($array, _("university degree"), 'universityDegree', ' &nbsp; ');


// University name
$form->addElement('text',
                  'education_name',
                  _("university name"),
                  array('id'=>'fieldOfStudyPrimary'));


// field of study
$form->addElement('select',
                  'education_major',
                  _("primary field of study"),
                  $category->getChilds(CAT_FIELD_OF_STUDY),
                  array('id'=>'fieldOfStudyPrimary'));

$form->addElement('select',
                  'fieldOfStudy',
                  _("field of study"),
                  $category->getChilds(CAT_FIELD_OF_STUDY),
                  array('id'       => 'fieldOfStudy',
                        'multiple' => 'multiple',
                        'size'     => '4'));



// Grouped elements
$array['begin']  = &HTML_QuickForm::createElement('date','education_begin',_("begin"));
$array['begin'] -> setFormat('MY');
$array['begin'] -> setMinYear(date('Y',time())-50);
$array['begin'] -> setMaxYear(date('Y',time()));
$array['end']    = &HTML_QuickForm::createElement('date','education_end',_("end"));
$array['end']   -> setFormat('MY');
$array['end']   -> setMinYear(date('Y',time())-50);
$array['end']   -> setMaxYear(date('Y',time()));
$form->addElementGroup($array, _("period"), 'date');



$form->addElement('textarea','education_desc',_("summary"));
$form->addElement('submit','add','+', array('class'=>'apply'));
$form->addElement('submit','remove','-', array('class'=>'apply'));
$form->addElement('hidden','index');

/*
* define validating rules
*/
$form->addRule('education_major', _("please select you major field of study."),'required');

/*
* set template variables for the list of professional experiences
*/
foreach($_SESSION['formData'][FORM_APPLY_EDUCATION] AS $key=>$val){
    if ($key!=='index'){
        $tpl->setVariable('list_education_name', $val['education_name']);
        $tpl->setVariable('list_number', $key+1);
        $tpl->setVariable('list_index', $key);
        $tpl->setVariable('list_period',sprintf(_("%02d/%04d till %02d/%04d"),
                                        $val['date']['education_begin']['M'],
                                        $val['date']['education_begin']['Y'],
                                        $val['date']['education_end']['M'],
                                        $val['date']['education_end']['Y']));
        $tpl->setVariable('list_description', $val['education_desc']);
        $tpl->parse('list');
    }
}



?>