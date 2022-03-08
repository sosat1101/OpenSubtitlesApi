<?php

namespace Sosat1101\Opensubtitles;

class SearchOpenSubtitles extends OpenSubtitles
{
    const URL = 'https://api.opensubtitles.com/api/v1/subtitles';
    public array $searchResult;

    private array $defaultParameters = [
        'ai_translated' => '',      // string    exclude, include (default: exclude)
        'episode_number' => 0,      // integer   For Tv shows
        'foreign_parts_only' => '', // string    foreign_parts_only
        'hearing_impaired' => '',   // string    include, exclude, only. (default: include)
        'id' => 0,                  // integer   ID of the movie or episode
        'imdb_id' => 0,             // string    IMDB ID of the movie or episode
        'languages' => '',          // string    Language code(s), coma separated (en,fr)
        'machine_translated' => '', // string    exclude, include (default: exclude)
        'moviehash' => '',          // string    include, only (default: include)
        'moviehash_match' => '',    // string    include, only (default: include)
        'order_by' => '',           // string    Order of the returned results, accept any of above fields
        'order_direction' => '',    // string    Order direction of the returned results (asc,desc)
        'page' => 0,                // integer   Results page to display
        'parent_feature_id' => 0,   // integer   For Tvshows
        'parent_imdb_id' => 0,      // integer   For Tvshows
        'parent_tmdb_id' => 0,      // integer   For Tvshows
        'query' => '',              // string    file name or text search
        'season_number' => 0,       // integer   TMDB ID of the movie or episode
        'trusted_sources' => '',    // string    include, only (default: include)
        'type' => '',               // string    movie, episode or all, (default: all)
        'user_id' => 0,             // integer   To be used alone - for user uploads listing
        'year' => 0,                // integer   Filter by movie/episode year
    ];

    public function __construct(array $fields)
    {
        foreach ($fields as $key => $field) {
            if (array_key_exists($key, $this->defaultParameters)) {
                $this->defaultParameters[$key] = $field;
            }
        }
    }

    public function initCurl($url = self::URL)
    {
        $presentParameters = [];
        foreach ($this->defaultParameters as $key => $value) {
            if (!empty($value)) {
                $presentParameters[$key] = $value;
            }
        }
        $url = $url.'?'.http_build_query($presentParameters);
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, false,);

        $httpHeader = ['Api-Key:' . self::ApiKey, 'Content-Type:application/json; charset=utf-8'];
        curl_setopt($this->ch, CURLOPT_POST, false);
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $httpHeader);
    }

    public function getResult()
    {
        $this->searchResult = $this->getResponse();
        return $this->searchResult;
    }
}