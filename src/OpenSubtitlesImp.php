<?php

include_once "Subtitles.php";
include_once "SearchOpenSubtitles.php";
include_once "DownloadOpenSubtitles.php";

class OpenSubtitlesImp implements Subtitles
{
    public function login($username, $password)
    {
        $loginOpenSubtitles = new LoginOpenSubtitles($username, $password);
        $loginOpenSubtitles->initCurl();
        $loginOpenSubtitles->getResponse();
        return $loginOpenSubtitles->getAccessToken();
    }

    public function search(string $name, string $language = "en"): array
    {
        $searchOpenSubtitles = new SearchOpenSubtitles(['query' => $name, 'languages' => $language]);
        $searchOpenSubtitles->initCurl();
        $searchOpenSubtitles->getResponse();
        return $searchOpenSubtitles->getResult();
    }

    public function download(int $subtitleId, string $language = "en"): ?Exception
    {
        // access_token 需要从缓存中读取
        $access_token = "";
        $downloadOpenSubtitles = new DownloadOpenSubtitles($access_token, ['file_id' => $subtitleId]);
        $downloadOpenSubtitles->initCurl();
        $downloadOpenSubtitles->getResponse();
        $downloadOpenSubtitles->getResult();
        try {
            $downloadOpenSubtitles->execDownload();
        } catch (Exception $e) {
            return $e;
        }
        return null;
    }
}