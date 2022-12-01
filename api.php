<?php
	include('./Convertor.php');
    $result = array();

    if(isset($_POST['action'])){
	    switch ($_POST['action']) {
	       	case 'calculate': 		
	       		$amount = $_POST['amount'] *1;
			    $convertor = new Convertor($_POST['from'], $_POST['to'],$amount);
        		$result = $convertor->convert();
	       	break;
	    	case 'get_types':
	    		$g = new UnitConverter();
	 			$result = $g->getTypes();
	    	break;
	    	case 'get_unit_types':
	    			$g = new UnitConverter();
    				$result = $g->getByUnitType($_POST['conversion']);
	    	break;	    	
	        default: 
	        	$result  = array(
    				"action_status" => "failed",
    				"result" => "No action received"
    			);	
    		 break;
	    }
	}else{
		$result  = array(
    		"action_status" => "failed",
    		"result" => "No action received"
    	);
	}
    echo(json_encode($result));
?>