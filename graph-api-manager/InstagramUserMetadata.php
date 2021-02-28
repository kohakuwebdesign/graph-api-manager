<?php
namespace GraphApiManager;

/*
 *
 * Handle instagram a user's metadata
 *  
 */
class InstagramUserMetadata extends Controller
{
    public function __construct($longLivedAccessToken, $instagramAccountId, $instagramUserName)
    {
        parent::__construct($longLivedAccessToken);

        $this->params['fields'] = 'business_discovery.username(' . $instagramUserName . '){username,website,name,ig_id,id,profile_picture_url,biography,follows_count,followers_count,media_count,media{caption,like_count,comments_count,media_url,permalink,media_type}}';

        $this->url = $this->conf['endpoint_base'] . $instagramAccountId . '?' . http_build_query($this->params);
    }
}
