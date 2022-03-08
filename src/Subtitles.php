<?php

namespace Sosat1101\Opensubtitles;
interface Subtitles
{
    public function search(string $name, string $language = "en"): array;

    public function download(int $subtitleId, string $language = "en");
    // download 函数返回 text/vtt 文本内容
}