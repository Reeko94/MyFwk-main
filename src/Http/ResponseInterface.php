<?php


namespace Fwk\Http;


interface ResponseInterface
{
    /**
     * @param $code
     * @param null $phrase
     */
    public function setStatusCode($code, $phrase = null);

    public function getStatusCode();

    public function setContentType($type, string $charset = 'UTF-8');

    public function getContentType();

    public function setCookie($name, $value = null, $expire = 0, $path = '/', $domain = null, $secure = false, $httpsOnly = true);

    public function deleteCookie($name, $path = '/', $domain = null);

    public function setContent($content);

    public function prependContent($content);

    public function setHeader($key, $values, $replace = true);

    public function addHeaders(array $headers);

    public function send();

}