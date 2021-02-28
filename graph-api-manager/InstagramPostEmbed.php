<?php
namespace GraphApiManager;

/*
 *
 * Getting instagram account id (instagram business account id)
 *  
 */
class InstagramPostEmbed extends Controller
{
    public function __construct($longLivedAccessToken, $postUrl)
    {
        parent::__construct($longLivedAccessToken);

        $this->params['url'] = $postUrl;
        $this->url = $this->conf['endpoint_base'] . 'instagram_oembed' . '?' . http_build_query($this->params);
    }
}