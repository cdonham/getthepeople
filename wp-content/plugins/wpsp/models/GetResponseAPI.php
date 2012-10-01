<?php
class GetResponseAPI {
    /**
    * Adds the given subscriber to the given campaign ID
    * 
    * @param string $campaignName GetResponse Campaign Name
    * @param string $apiKey GetResponse API Key
    * @param string $name Subscriber Name
    * @param string $email Subscriber Email
    * @return mixed True (bool) or error message
    */
    function AddSubscriberToCampaignID($campaignName, $apiKey, $name, $email) {
        require_once(PLUGIN_ROOT.'/models/jsonRPCClient.php');
        
        $client = new jsonRPCClient('http://api2.getresponse.com');

        // Get list of campaigns to get campaign ID based on given name
        try {
            $result = $client->get_campaigns(
                $apiKey,
                array (
                    # find by name literally
                    'name' => array ( 'EQUALS' => $campaignName )
                )
            );
        }
        catch (Exception $e) {
            # check for communication and response errors
            # implement handling if needed
            die($e->getMessage());
        }
        
        $campaignID = array_pop(array_keys($result));

        try {
            $result = $client->add_contact(
                $apiKey,
                array (
                    'campaign'  => $campaignID,
                    'name'      => $name,
                    'email'     => $email,
                    'cycle_day' => '0'
                )
            );
        } catch (Exception $e) {
            return $e->getMessage();
        }
        
        return true;
    }  
}
?>