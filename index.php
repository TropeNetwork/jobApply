<?php

include_once 'prepend.inc';
require_once('HTML/QuickForm.php');
require_once('HTML/QuickForm/Renderer/ITStatic.php');
require_once('HTML/Template/Sigma.php');
require_once(OHR_LIB.'Application/Client.php');
require_once(OHR_LIB.'define.php');
require_once(OHR_LIB.'Menu.php');

$page            = &Page::singleton('applications/generic.tpl');
$page->setSlot('menufoot','<a href="'.$OHR_CONFIG['dir.app_jobAdmin'].'/source.php">'._("Sourcecode").'</a>');

function &includeForm($pageID){
    global $page;

    switch($pageID){
    case FORM_APPLY_PERSONAL_DATA:
        $include='formPersonalData.php';
        $page->setSlot('browsertitle',_("online application").' - '._("personal data"));
        $template='formPersonalData.html';
        break;
    case FORM_APPLY_QUALIFICATION:
        $include='formQualification.php';
        $page->setSlot('browsertitle',_("online application").' - '._("qualifications"));
        $template='formQualification.html';
        break;
    case FORM_APPLY_EDUCATION:
        $page->setSlot('browsertitle',_("online application").' - '._("education"));
        $template='formEducation.html';
        $include='formEducation.php';
        break;
    case FORM_APPLY_PROFESSIONAL_EXPERIENCE:
        $page->setSlot('browsertitle',_("online application").' - '._("professional experience"));
        $template='formProfessionalExperience.html';
        $include='formProfessionalExperience.php';
        break;
    default:
        trigger_error('unknown formular '.$pageID);
    }

    $tpl =& new HTML_Template_Sigma('.');
    $tpl->loadTemplateFile($template);

    $renderer =& new HTML_QuickForm_Renderer_ITStatic($tpl);
    $renderer->setRequiredTemplate('{label}<font color="red" size="1">*</font>');
    $renderer->setErrorTemplate('<font color="orange" size="1">{error}</font><br/>{html}');

    include($include);
    
    // navigation buttons
    $back   = &$form->addElement('submit','back',_("back"),array('accesskey'=>'b'));
    $next   = &$form->addElement('submit','next',_("next"),array('accesskey'=>'n'));
    $submit = &$form->addElement('submit','submit',_("send application"),array('accesskey'=>'s'));

    // activate/deactivate submit buttons 
    if ($pageID<=1){
        $back->updateAttributes(array('disabled'=>'disabled'));
    }
    if ($pageID>=4){
        $next->updateAttributes(array('disabled'=>'disabled'));
    }
    if (!(isset($_SESSION['formData'][FORM_APPLY_PERSONAL_DATA]) &&
          isset($_SESSION['formData'][FORM_APPLY_EDUCATION]) && 
          (isset($_SESSION['formData'][FORM_APPLY_QUALIFICATION || $pageID==FORM_APPLY_QUALIFICATION])) &&
           isset($_SESSION['formData'][FORM_APPLY_PROFESSIONAL_EXPERIENCE]))){
        $submit->updateAttributes(array('disabled'=>'disabled'));
    }

    $form->accept($renderer);
    $page->setSlot('content',$tpl->get());

    return $form;
} 

// import HTTP vars
$pageID          = importVar('pageID',array('from'         => 'GET',
                                          'defaultSession' => true,
                                          'saveSession'    => true));
$job_id          = importVar('key', array('from'           => 'GET',
                                          'defaultSession' => true,
                                          'saveSession'    => true));
$application_id  = importVar('form',array('from'           => 'GET',
                                          'defaultSession' => true,
                                          'saveSession'    => true));


if ($pageID<1)         $pageID=1;
if ($pageID>4)         $pageID=4;
if ($application_id<1) $application_id=1;
if ($job_id<1)         $job_id=1;

$application     = new Application_Client($application_id,$job_id);

// make this better
if($pageID==FORM_APPLY_PROFESSIONAL_EXPERIENCE || $pageID==FORM_APPLY_EDUCATION ){
    if (!isset($_SESSION['formData'][$pageID]['index'])){
        $_SESSION['formData'][$pageID]['index']=0;
    }
    $index=&$_SESSION['formData'][$pageID]['index'];
    if (isset($_GET['index'])) $index=$_GET['index'];
}


// define the formular and the validation rules
$form=&includeForm($pageID);

/*
* define Filter rules
*/
$form->applyFilter('__ALL__', 'trim');

if ($form->validate()) {
    $validData          = $form->getSubmitValues(); 
 
    // save validated data in SESSION
    // formulars, which may submitted multiple times must contain
    // a field 'index'.
    if (array_key_exists('index',$validData)){
        $index=$validData['index'];
        unset($validData['index']);
        $_SESSION['formData'][$pageID][$index] = $validData;
    }else{
        $_SESSION['formData'][$pageID] = $validData;
    }

    if (array_key_exists('next',$validData)){
        $pageID++;
    }elseif(array_key_exists('back',$validData)){
        $pageID--;
    }elseif(array_key_exists('add',$validData)){
        $index++;
        header('Location: ?index='.$index);
        exit;
    }elseif(array_key_exists('remove',$validData)){
        $index--;
        header('Location: ?index='.$index);
        exit;
    }elseif(array_key_exists('submit',$validData)){

        foreach($_SESSION['formData'] AS $array){
           $validData=array_merge($validData,$array);
        }

        require_once(OPENHR_LIB.'/HRXML/Resume.php');

        $resume=new HRXML_Resume;
        $resume->setAttributes($validData);
        print 'HRXML:<pre>'.htmlspecialchars($resume->toString('xml')).'</pre><hr>';

        $application->setValues($validData);
        if (!PEAR::isError($application->send())){
            $page->Message(_("Your application was successfully send. You will receive a confirmation by mail"));
            $page->setSlot('counter','<img size="1" width="1" alt="" src="/jobSearch/counter.php?what=applicationSent&key='.$job_id.'">');
        }else{
            $page->Error(_('Could not send application'));
        }
        $page->toHtml();
        exit;

    }else{
        trigger_error('unknown submit button');
    }

    $_SESSION['pageID'] = $pageID;
    header('Location: ?pageID='.$pageID);
    exit;
}else{
    $page->setSlot('counter','<img size="1" width="1" alt="" src="/jobSearch/counter.php?what=application&key='.$job_id.'">');
}

// form->accept() has to be called once again. Otherwise, error-messages
// are not displayed. Move the following into a function?

$page->toHtml();

?>
