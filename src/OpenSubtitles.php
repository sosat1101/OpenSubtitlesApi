<?php

namespace sosat1101\openSubtitles;

abstract class OpenSubtitles
{
    protected mixed $ch;
    const ApiKey = 'bCZDNxfGMhcVBtr5fAo7sqj9nmasEHLi';

    abstract public function initCurl($url);

    public function getResponse(): array|string
    {
        $result = curl_exec($this->ch);
        if (curl_exec($this->ch) === false) {
            return 'Curl error: ' . curl_error($this->ch);
        }
        if (200 !== curl_getinfo($this->ch, CURLINFO_HTTP_CODE)) {
            return 'Response ERROR: httpCode:' . curl_getinfo($this->ch, CURLINFO_HTTP_CODE) . "Curl Info:" . curl_getinfo($this->ch);
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