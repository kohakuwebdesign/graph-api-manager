<?php
namespace GraphApiManager;

/*
 *
 * Handle Instagram hashtag
 *
 */
class InstagramHashtag extends Controller
{
    public function __construct($longLivedAccesstoken,$instagramBusinessAccountId)
    {
        parent::__construct($longLivedAccesstoken);
        $this->params['user_id'] = urlencode($instagramBusinessAccountId);
    }

    /**
     * Get hashtag id using hashtag
     *
     * @param string $hashTag
     * @return array
     */
    public function getHashTagId($hashTag)
    {
        if (isset($this->params['limit'])) {
            unset($this->params['limit']);
        }

        $this->params['q'] = $hashTag;
        $this->url = $this->conf['endpoint_base'] . 'ig_hashtag_search' . '?' . http_build_query($this->params);

        return $this->getResponse();
    }

    /**
     * Get hashtag related posts
     *
     * @param string $instagramHashTagId
     * @param integer $limit
     * @return array
     */
    public function getPostsDataFromHashtagId($instagramHashTagId, $limit)
    {
        if (isset($this->params['q'])) {
            unset($this->params['q']);
        }

        // $limit = 2;

        $this->params['fields'] = 'media_url,caption,permalink,id,media_type,timestamp';
        $this->params['limit'] = $limit;

        $this->url = $this->conf['endpoint_base'] . $instagramHashTagId . '/recent_media' . '?' . http_build_query($this->params);

        return $this->getResponse();
    }
}