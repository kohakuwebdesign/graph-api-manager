<?php
namespace GraphApiManager;

/*
 *
 * Handle connection with Api
 *
 */
class Controller
{
    protected $endPoint;
    protected $params;
    protected $url;
    protected $conf;

    public function __construct($longLivedAccesstoken)
    {
        $longLivedAccesstoken = urlencode($longLivedAccesstoken);
        $this->conf = include('./config.php');

        if ($this->conf['appsecret_proof_mode'] == true) {
            // endpoint params
            $this->params = array(
                'access_token' => $longLivedAccesstoken,
                'appsecret_proof' => hash_hmac('sha256', $longLivedAccesstoken, $this->conf['facebook_app_secret'])
            );
        } else {
            // endpoint params
            $this->params = array(
                'access_token' => $longLivedAccesstoken
            );
        }

        // add params to endpoint
        $this->endPoint = $this->conf['endpoint_base'] . $this->conf['default_graph_version'];
        $this->url = $this->endPoint . '?' . http_build_query($this->params);
    }

    /**
     * Get response from api
     *
     * @return array
     */
    public function getResponse()
    {
        // setup curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // make call and get response
        $response = curl_exec($ch);

        curl_close($ch);

        $response = json_decode($response, true);

        return $response;
    }

    /**
     * Get endpoint
     *
     * @return string
     */
    public function getEndpoint()
    {
        return $this->url;
    }
}