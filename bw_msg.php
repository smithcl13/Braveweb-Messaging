<?php

require('bw.conf.inc');
require('session.inc');
require_once('bw_msg.dao');
require_once('wValidate.inc');
require_once ('const.inc');
require('bw.template.php');
require('braveweb_menu_item.inc');

require (BANNER);

// ensure the pidm is defined in the session
if (empty ( $_SESSION [PIDM] )) {
    log_error ( __FILE__, __LINE__, 'Session ' . SID . ' did not have pidm' );
    $message = 'Your session has expired.  This can happen when you close your browser without logging out of BraveWeb. ' . 'Please log out and log in again to continue.';
    require ('message.php');
    exit ();
}

$bw_message = new bw_messageDAO(BANNER);
//Get Permissions level of user
$permissions = $bw_message->get_permissions($_SESSION['pidm']);

print_header('Braveweb Messaging', array('primary_menu'=>'bw_user_msgs_menu', 'css'=>array('/assets/bootstrap-3.1.1-dist/css/bootstrap.min.css', '/css/dataTables.min.css', '/css/jquery.dataTables.min.css', './bw_msg.css')));

//If user has no permissions then don't allow any further.
if (!$permissions) {
    $message = 'You do not have permissions to be in this application. Please contact a Braveweb Admin if you feel you have reached this page in error.';
}

require('bw_msg.html.php');

print_footer(array('js_files' => array('page.js', '/js/jquery.dataTables.min.js')));
exit;

?>