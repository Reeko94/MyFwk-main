<?php


namespace Fwk\Http;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response as SFResponse;

class Response implements ResponseInterface
{
    /**
     * @var SFResponse
     */
    protected SFResponse $response;

    /**
     * @var bool
     */
    protected bool $sent = false;

    public function __construct(SFResponse $response)
    {
        $this->response = $response;
    }

    /**
     * @return SFResponse
     */
    public function getResponseObject(): SFResponse
    {
        return $this->response;
    }

    /**
     * @inheritDoc
     */
    public function setStatusCode($code, $phrase = null): Response
    {
        $this->response->setStatusCode($code, $phrase);

        return $this;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->response->getStatusCode();
    }

    public function setContentType($type, string $charset = 'UTF-8'): Response
    {
        $this->response->headers->set('Content-Type', $type);
        $this->response->setCharset($charset);

        return $this;
    }

    public function getContentType(): ?string
    {
        return $this->response->headers->get('Content-Type', 'text/html');
    }

    public function setCookie($name, $value = null, $expire = 0, $path = '/', $domain = null, $secure = false, $httpsOnly = true): Response
    {
        $cookie = new Cookie($name, $value, $expire, $path, $domain, $secure, $httpsOnly);
        $this->response->headers->setCookie($cookie);

        return $this;
    }

    public function deleteCookie($name, $path = '/', $domain = null): Response
    {
        $this->response->headers->removeCookie($name, $path, $domain);

        return $this;
    }

    public function setContent($content): Response
    {
        $this->response->setContent($content);

        return $this;
    }

    public function getContent()
    {
        return $this->response->getContent();
    }

    public function addContent($content): Response
    {
        $this->setContent($this->response->getContent().$content);

        return $this;
    }

    public function prependContent($content): Response
    {
        $this->response->setContent($content. $this->response->getContent());

        return $this;
    }

    public function setHeader($key, $values, $replace = true): Response
    {
        $this->response->headers->set($key,$values,$replace);

        return $this;
    }

    public function addHeaders(array $headers): Response
    {
        $this->response->headers->add($headers);

        return $this;
    }

    public function send()
    {
        $this->response->send();
    }
}