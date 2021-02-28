<?php
namespace GraphApiManager;

/*
 *
 * Handle Instagram account data
 * Get instagram account id (instagram business account id)
 *
 */
class InstagramAccount extends Controller
{

    public function __construct($longLivedAccessToken, $pageId)
    {
        parent::__construct($longLivedAccessToken);

        $this->params['fields'] = 'instagram_business_account';

        $this->url = $this->conf['endpoint_base'] . $pageId . '?' . http_build_query($this->params);
    }
}