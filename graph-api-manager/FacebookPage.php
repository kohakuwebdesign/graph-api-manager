<?php
namespace GraphApiManager;

/*
 *
 * Handle Facebook page data
 *
 */
class FacebookPage extends Controller
{
    public function __construct($longLivedAccesstoken)
    {
        parent::__construct($longLivedAccesstoken);

        $this->url = $this->conf['endpoint_base'] . 'me/accounts?' . http_build_query($this->params);
    }
}