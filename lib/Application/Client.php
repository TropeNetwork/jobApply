<?php

require_once ('SOAP/Client.php');
require_once (OHR_LIB.'Application.php');
require_once (OHR_LIB.'Database.php');

class Application_Client extends Application {

    /** data of the application */
    var $data=array();

    function Application_Client($application_id=0,$job_id=0){
        $this->_validAttributes = $this->_personalAttributes ;
        $this->db             = &Database::getConnection( DB_JOBS ); 
        $this->application_id = $application_id;
        $this->job_id         = $job_id;
    }

    function send(){
        global $OHR_CONFIG;

        $endpoint     = $OHR_CONFIG["soap.jobAdmin"];

        $key          = $this->job_id;
        $wsdl         = false;
        $portName     = false;
        $proxy_params = array();

        $sc = new SOAP_Client($endpoint);
        $sc->setEncoding("ISO-8859-1");

        $method = 'addApplication';
        $params = array("key"    => $key,
                        "job_id" => $this->application_id,
                        "data"   => $this->data);

        $options = array('namespace'    => 'urn:SOAP_jobAdmin',
                         'trace'        => true,
                         'timeout'      => 30,
                         'from'         => $OHR_CONFIG["soap.jobAdmin_from"],
                         'Subject'      => "SOAP Request jobAdmin: send CV for job: $key");

        // PEAR SOAP classes are not E_ALL save yet
        $errorReportingOld=error_reporting( E_ALL & ~(E_WARNING | E_NOTICE) );
        $res = $sc->call($method, $params, $options);
        error_reporting($errorReportingOld);
        if (PEAR::isError($res)){
            $page=&Page::singleton();
            $page->Error(sprintf(_("job could not be puplished on $endpoint %s"),
                                   "<hr><pre>".var_export($res,true)."</pre>"));
        }
        return $res;
    }

    function setValues($array){
        foreach($array AS $key=>$val){
            if (in_array($key,$this->_validAttributes)){
                $this->data["$key"]=$val;
            }else{
                trigger_error("UNKOWN $key=$val");
            }
        }
    }

}

?>