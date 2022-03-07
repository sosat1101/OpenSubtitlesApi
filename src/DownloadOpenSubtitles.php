<?php

namespace sosat1101\openSubtitles;

class DownloadOpenSubtitles extends OpenSubtitles
{
    private array $defaultParameters = [
        'file_id' => 0,      // integer    file_id from /subtitles search results
        'sub_format' => 'srt',
        'file_name' => '',
        'in_fps' => '',
        'out_fps' => '',
        'timeshift' => '',
        'force_download' => '',
    ];
    const URL = 'https://api.opensubtitles.com/api/v1/download';
    protected string $access_token;
    private mixed $downloadResult;

    public function __construct($access_token, array $body)
    {
        $this->access_token = $access_token;
        foreach ($body as $key => $value) {
            if (array_key_exists($key, $this->defaultParameters)) {
                $this->defaultParameters[$key] = $value;
            } else {
                return "参数初始化错误";
            }
        }
    }

    public function initCurl($url = self::URL)
    {
        $presentBody = [];
        foreach ($this->defaultParameters as $key => $value) {
            if (!empty($value)) {
                $presentBody[$key] = $value;
            }
        }
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, false,);

        $httpHeader = ['Authorization:' . $this->access_token, 'Api-Key:' . self::ApiKey, 'Content-Type:application/json; charset=utf-8'];
        curl_setopt($this->ch, CURLOPT_POST, true);
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $httpHeader);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, json_encode($presentBody));
    }

    public function getResult(): array|string
    {
        $this->downloadResult = $this->getResponse();
        return $this->downloadResult;
    }

    public function getLink()
    {
        return $this->downloadResult['link'];
    }

    /**
     * @throws Exception
     */
    public function execDownload($bufferSize = 4096)
    {
        $pathInfo = pathinfo($this->getLink());
        $basename = $pathInfo['basename'];
        header("Content-Type: text/plain");
        header("Content-Disposition: attachment; filename=$basename");
        header("Content-Transfer-Encoding: binary");
        // 获取Resource
        $resource = fopen($this->getLink(), 'rb');
        if ($resource) {
            // 开启一个大小为4096 bit的缓冲区
            ob_start(function ($chunk) {
                return $chunk;
            }, $bufferSize);

            // 从之前获取到的Resource中以4096 bit大小读入到缓冲区，并输出
            while (true) {
                $data = fread($resource, $bufferSize);
                if (strlen($data) == 0) {
                    break;
                }
                // 向客户端echo缓冲区内容， 此时缓冲区为空
                echo($data);
            }
            fclose($resource);
            // 关闭 output_buffer
            ob_end_clean();
        } else {
            throw new Exception('无法获取当前URL资源');
        }
    }
}