<?php
namespace GraphApiManager;

use Facebook\Facebook;

/*
 *
 * Handle authorization
 *
 */
class Authorization
{
    private $conf;
    private $cred;
    private $helper;
    private $oAuth2Client;

    public function __construct()
    {
        $this->conf = include('./config.php');

        $this->cred = array(
            'app_id' => $this->conf['facebook_app_id'],
            'app_secret' => $this->conf['facebook_app_secret'],
            'default_graph_version' => $this->conf['default_graph_version'],
            'persistant_data_handler' => 'session'
        );

        session_start();

        $facebook = new Facebook($this->cred);

        $this->helper = $facebook->getRedirectLoginHelper();

        $this->oAuth2Client = $facebook->getOauth2Client();
    }

    /**
     * Do authorize
     *
     * @param  string  $code
     * @return array
     */
    public function authorize($code)
    {
        $response = [];

        if (isset($code)) { // get access token

            try {
                $shortLivedAccessToken = $this->helper->getAccessToken(); // short lived access token (one hour)
            } catch (\Facebook\Exceptions\FacebookResponseException $e) { // graph error
                $response['status'] = 'Authorization Failed';
                $response['errors']['graph_error'] = 'Graph returned an error ' . $e->getMessage;
            } catch (\Facebook\Exceptions\FacebookSDKException $e) { // validation error
                $response['status'] = 'Authorization Failed';
                $response['errors']['facebook_sdk_error'] = 'Facebook SDK returned an error ' . $e->getMessage;
            }

            if (!$shortLivedAccessToken->isLongLived()) { // exchange short lived access token for long lived access token (60 days)
                try {
                    $longLivedAccessToken = $this->oAuth2Client->getLongLivedAccessToken($shortLivedAccessToken);
                } catch (\Facebook\Exceptions\FacebookSDKException $e) {
                    $response['status'] = 'Authorization Failed';
                    $response['errors']['long_lived_accesstoken_error'] = 'Error getting long lived access token' . $e->getMessage();
                }
            }

            if (isset($response['status'])) {
                if ($response['status'] != 'Authorization Failed') {
                    $response['status'] = 'Authorization Succeeded';
                }
            }

            $response['short_lived_accesstoken'] = $shortLivedAccessToken;
            $response['long_lived_accesstoken'] = $longLivedAccessToken;

        } else { // display login url
            $permissions = [
                'public_profile',
                'instagram_basic',
                'pages_show_list',
                'instagram_manage_insights' // you need this permission to use 'business_discovery' endpoint
            ];

            $response['login_url'] = $this->helper->getLoginUrl($this->conf['facebook_redirect_uri'], $permissions);
        }

        return $response;
    }
}

