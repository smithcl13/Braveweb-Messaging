<?php
    
// require sessions and stuff
require ('bw.conf.inc');
require ('session.inc');
// require (AWD);
require (BANNER);
require ('bw_msg.dao');
// require_once ('wValidate.inc');
require ('const.inc');

//ensure the pidm is defined in the session
if (empty($_SESSION[PIDM])) {
    log_error(__FILE__, __LINE__, 'Session ' . SID . ' did not have pidm');
    $message = 'Your session has expired.  This can happen when you close your browser without logging out of BraveWeb. ' . 'Please log out and log in again to continue.';
    require ('message.php');
    exit();
}

$bw_message = new bw_messageDAO(BANNER);

$action = isset($_POST['action']) ? $_POST['action'] : null;

if ($action != 'get_all_records') {
    $period_key = isset($_POST['period_key']) ? $_POST['period_key'] : null;
    if (!preg_match('/^[0-9]*$/', $period_key)) {
        header('Content-type: application/json');
        echo json_encode(array(
            'success'=>false,'message'=>'The message key doesnt exist' ));
        return;
    }
}
switch ($action) {
    case 'response':
        get_response($period_key);
        break;
        
    case 'end':
        end_message_period($period_key);
        break;
        
    case 'delete':
        delete_message();
        break;

    case 'get_all_records':
        get_record();
        break;
        
    case 'get_record':
        get_record($period_key);
        break;
    
    case 'permissions':
        get_permissions();
        break;
        
    default:
        header('Content-type: application/json');
        echo json_encode(array(
            'success'=>false,'message'=>'Invalid call' ));
}

function get_record($period_key=null) {
    global $bw_message;
    
    //get records
    $records = $bw_message->get_record($period_key);
    $permissions = $bw_message->get_permissions($_SESSION['pidm']);
    
    //#TODO check pidm to see if you can be in here
  
    if (isset($period_key)) {
        if ($records) {
          $row [] = array('note_code'=>$records[0]->note_code,
                'start_date'=>$records[0]->start_date,
                'end_date'=>$records[0]->end_date,
                'active'=>$records[0]->active,
                'message_text'=>htmlspecialchars($records[0]->message_text),
                'activity_date'=>$records[0]->activity_date,
                'message_key' => $records[0]->msg_key,
                'period_key' => $records[0]->period_key
            );
            
            $response = array('success'=> true, 'message'=> null, 'record'=> $row);
            
        } else {
            $response = array(
                'success'=>true,
                'message'=> $period_key,
            );
        }
    } else {
        $rows = array();
        foreach ($records as $record) {
            if ($permissions[0]->note_code == null || $permissions[0]->note_code == $record->note_code) {
                $rows[] = array(
                    'note_code'=>$record->note_code,
                    'start_date'=>$record->start_date,
                    'end_date'=>$record->end_date,
                    'message_title'=>$record->message_title,
                    'message_key' => $record->msg_key,
                    'period_key' => $record->period_key
                );
            } else {
                continue;
            }
        }
        
        $response = array(
            'success' => true, 
            'message' => null,
            'records' => $rows
        );
    }
    header('Content-type: application/json');
    echo json_encode($response);
}

function end_message_period($period_key) {
    global $bw_message;
    
    if (isset($period_key)) {
        
        $msg_period = $bw_message->end_msg_period($period_key);
        
        if (is_null($msg_period)) {
            $response = array(
                'success'=>true,
                'message'=> 'The message period was stopped'
            );
        } else {
            $response = array(
                'success'=>false,
                'message'=> 'The message period was not stopped'
            );
        }
    } else {
        $response = array(
            'success'=>false,
            'message'=> 'There are no records that match this period key'
        );
    }
    
    header('Content-type: application/json');
    echo json_encode($response);
}

function get_response($period_key = NULL) {
    global $bw_message;
    if (isset($period_key)) {
        // get records
        $records = $bw_message->get_response($period_key);
        $rows = [];
        foreach ($records as $record) {
            $rows [] = array('first_name'=>$record->fname,
                             'last_name'=>$record->lname,
                             'response'=>$record->response
                            );
        }
            
        $response = array('success'=> true, 'message'=> $period_key, 'records'=> $rows);
            
    } else {
        $response = array('success'=>false,
                          'message'=> 'There is no period key',
            );
    }
    
    header('Content-type: application/json');
    echo json_encode($response);
}

function get_permissions(){
    global $bw_message;
}
?>