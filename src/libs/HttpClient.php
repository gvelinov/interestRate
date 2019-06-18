<?php
namespace App\Libs;

/**
 * Class HttpClient
 * Simple HTTP Cliet
 * CURL wrapper
 */
class HttpClient implements ClientInterface
{
    private $ch;
    public $options = [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30
    ];

    /**
     * @param $url
     */
    private function prepareRequest($url): void
    {
        $this->ch = curl_init($url);
        foreach ($this->options as $opt => $val) {
            curl_setopt($this->ch, $opt, $val);
        }
    }

    /**
     * @param string $url
     * @return string
     */
    public function get(string $url): string
    {
        $this->prepareRequest($url);
        $result = curl_exec($this->ch);
        curl_close($this->ch);
        if(curl_errno($this->ch)){
            curl_error($this->ch);
        }

        return $result;
    }

}