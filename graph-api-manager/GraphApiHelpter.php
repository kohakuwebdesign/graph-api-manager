<?php
namespace GraphApiManager;

/*
 *
 * Handle Hashtags
 *
 */
class GraphApiHelpter
{
    public function getHashtagList($text)
    {
        $matches = array();
        preg_match_all('/#(w*[一-龠_ぁ-ん_ァ-ヴーａ-ｚＡ-Ｚa-zA-Z0-9]+|[a-zA-Z0-9_]+|[a-zA-Z0-9_]w*)/', $text, $matches);
        return $matches[0];
    }
}
