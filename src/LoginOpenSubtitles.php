<?php

namespace Sosat1101\Opensubtitles;

class LoginOpenSubtitles extends OpenSubtitles
{
    private string $username;
    private string $password;
    const URL = 'https://api.opensubtitles.com/api/v1/login';
    public array|string $loginResult;

    public function __construct(string $username, string $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    public function initCurl($url = self::URL)
    {
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, false,);

        $httpHeader = ['Api-Key:' . self::ApiKey, 'Content-Type:application/json; charset=utf-8'];
        $body = json_encode(["username" => $this->username, "password" => $this->password]);
        curl_setopt($this->ch, CURLOPT_POST, true);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $httpHeader);
    }

    public function getResult(): array|string
    {
        try {
            $this->loginResult = $this->getResponse();
        } catch (Exception $e) {
            $this->loginResult = $e->getMessage();
            return $this->loginResult;
        }
        return $this->loginResult;
    }

    public function getAccessToken()
    {
        // access_token需要存入缓存
        if (is_array($this->loginResult)) {
            return $this->loginResult['token'];
        } else return $this->loginResult;
    }
}