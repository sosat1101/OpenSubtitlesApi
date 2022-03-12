<?php

namespace Sosat1101\Opensubtitles;

use Exception;

class OpenSubtitlesImp implements Subtitles
{
    private mixed $loginOpenSubtitles;
    private mixed $searchOpenSubtitles;
    private mixed $downloadOpenSubtitles;

    public function login($username, $password)
    {
        $this->loginOpenSubtitles = new LoginOpenSubtitles($username, $password);
        $this->loginOpenSubtitles->initCurl();
        $this->loginOpenSubtitles->getResult();
        return $this->loginOpenSubtitles->getAccessToken();
    }

    public function search(string $name, string $language = "en"): array
    {
        $this->searchOpenSubtitles = new SearchOpenSubtitles(['query' => $name, 'languages' => $language]);
        $this->searchOpenSubtitles->initCurl();
        return $this->searchOpenSubtitles->getResult();
    }

    public function download(int $subtitleId, string $accessToken, string $language = "en")
    {
        // access_token 需要从缓存中读取
        try {
            $this->downloadOpenSubtitles = new DownloadOpenSubtitles($accessToken, ['file_id' => $subtitleId]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
        $this->downloadOpenSubtitles->initCurl();
        $this->downloadOpenSubtitles->getResult();
        try {
            $this->downloadOpenSubtitles->execDownload();
        } catch (Exception $e) {
//            http_response_code(500);
            return $e->getMessage();
        }
        return null;
    }
}