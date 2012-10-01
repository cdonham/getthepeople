<?php
/**
* Lists Model
* 
* @package Models
* @author Tim Carr
* @version 1
*/

/**
* ListsModel is a model to deal with processing lists
* 
* @package Models
* @author Tim Carr
*/
class ListsModel { 
    /**
    * Returns the form field data for the given opt in type, list ID and username
    * 
    * @param string $optin Optin Provider
    * @param string $listID List ID
    * @param string $username Optin Provider Username
    * @param string $successURL Success Redirect URL
    * @param string $errorURL Error Redirect URL
    * @return array Form Data
    */
    function GetFormData($optin, $listID, $username = '', $successURL = '', $errorURL = '') {
        // Define form field names
        switch (strtolower($optin)) {
            case 'aweber':
                $data['name'] = 'name';
                $data['email'] = 'email';
                $data['listID'] = 'listname';
                $data['postURL'] = 'http://www.aweber.com/scripts/addlead.pl';
                $data['additionalFields'] = '<input type="hidden" name="redirect" value="'.($successURL != '' ? $successURL : 'http://www.aweber.com/thankyou-coi.htm?m=text').'" />';
                break;
            case 'mailchimp':
                $data['name'] = 'MERGE1';
                $data['email'] = 'MERGE0';
                $data['listID'] = 'listID';
                $data['postURL'] = $_SERVER['REQUEST_URI'];
                break;
            case 'getresponse':
                $data['name'] = 'MERGE1';
                $data['email'] = 'MERGE0';
                $data['listID'] = 'listID'; // Not used?
                $data['postURL'] = $_SERVER['REQUEST_URI']; 
                break;
        }
        
        // Validate submitted form
        if (isset($_POST['submit'])) {
            if (trim($_POST[$data['name']]) == '') $data['error'] = 'No name was specified.';
            elseif (trim($_POST[$data['email']]) == '') $data['error'] = 'No email was specified.';
            elseif (trim($_POST[$data['email']]) == '') $data['error'] = 'No email was specified.';
            elseif(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $_POST[$data['email']])) $data['error'] = 'Invalid email address specified.';
        }
        
        // Return now if an error
        if (isset($data['error'])) return $data;
        
        // API Submit for MailChimp + GetResponse
        if (isset($_POST['submit'])) {
            switch (strtolower($optin)) { 
                case 'mailchimp':
                    if (!class_exists('MCAPI')) require_once(PLUGIN_ROOT.'/models/MCAPI.php');
                    $mcapi = new MCAPI($username); // Username is the API key
                    $nameParts = explode(' ', $_POST['MERGE1'], 1);
                    $result = $mcapi->listSubscribe($listID, $_POST['MERGE0'], array('FNAME' => $nameParts[0], 'LNAME' => $nameParts[1]));
                    if ($mcapi->errorCode) {
                        $data['error'] = $mcapi->errorCode.': '.$mcapi->errorMessage;
                        
                        // Redirect if URL specified
                        if ($errorURL != '') {
                            header('Location: '.$errorURL);
                            die();
                        }    
                    } else {
                        $data['success'] = 'Subscribe successful. Please check your email.';
                        
                        // Redirect if URL specified
                        if ($successURL != '') {
                            header('Location: '.$successURL);
                            die();
                        }
                    }
                    break;
                case 'getresponse':
                    require_once(PLUGIN_ROOT.'/models/GetResponseAPI.php');
                    $grapi = new GetResponseAPI();
                    $result = $grapi->AddSubscriberToCampaignID($listID, $username, $_POST['MERGE1'], $_POST['MERGE0']);
                    
                    if (!is_bool($result)) {
                        // Error
                        $data['error'] = 'An error occurred when subscribing.  Please try again.  Message: '.$result;
                        
                        // Redirect if URL specified
                        if ($errorURL != '') {
                            header('Location: '.$errorURL);
                            die();
                        } 
                    } else {
                        // OK
                        $data['success'] = 'Subscribe successful. Please check your email.'; 
                        
                        // Redirect if URL specified
                        if ($successURL != '') {
                            header('Location: '.$successURL);
                            die();
                        } 
                    }
                    break; 
            }
        }
        
        return $data;
    }
}
?>
