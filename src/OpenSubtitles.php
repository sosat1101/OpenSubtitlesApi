<?php

namespace Sosat1101\Opensubtitles;

use Exception;

abstract class OpenSubtitles
{
    protected mixed $ch;
    const ApiKey = 'bCZDNxfGMhcVBtr5fAo7sqj9nmasEHLi';

    abstract public function initCurl($url);

    /**
     * @throws Exception
     */
    public function getResponse(): array|string|Exception
    {
        $result = curl_exec($this->ch);
        if (curl_exec($this->ch) === false) {
            throw  new Exception(json_encode("curl False:".curl_error($this->ch)));
        }
        if (200 !== curl_getinfo($this->ch, CURLINFO_HTTP_CODE)) {
            throw new Exception("OpenSubtitles error with response: ".json_encode($result));
        }
        curl_close($this->ch);
        return $this->toArray($result);
    }

    private function toArray(string $json): array
    {
        return json_decode($json, true);
    }

    abstract public function getResult();
}