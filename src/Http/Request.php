<?php


namespace Fwk\Http;


use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\Request as SFRequest;

class Request implements RequestInterface
{

    /**
     * @var SFRequest
     */
    protected SFRequest $request;

    /**
     * Request constructor.
     * @param SFRequest $request
     */
    public function __construct(SFRequest $request)
    {
        $this->request = $request;
    }

    /**
     * @return SFRequest
     */
    public function getRequestObject(): SFRequest
    {
        return $this->request;
    }

    /**
     * @return string
     */
    public function getBaseUrl(): string
    {
        return $this->request->getBaseUrl();
    }

    /**
     * @return string
     */
    public function getBasePath(): string
    {
        return $this->request->getBasePath();
    }

    /**
     * @return string
     */
    public function getPathInfo(): string
    {
        return $this->request->getPathInfo();
    }

    /**
     * @param string|null $name
     * @param string|null $default
     * @return string|array
     */
    public function getFromServer(string $name = null, string $default = null)
    {
        if ($name === null) {
            return $this->request->server->all();
        }

        return $this->request->server->get($name, $default);
    }

    /**
     * @param string|null $name
     * @param string|null $default
     * @return array|bool|float|int|string|InputBag
     */
    public function getFromQuery(string $name = null, string $default = null)
    {
        if ($name === null) {
            return $this->request->query->all();
        }

        return $this->request->query->get($name, $default);
    }

    /**
     * @param string|null $name
     * @param string|null $default
     * @return string|array
     */
    public function getFromPost(string $name = null, string $default = null)
    {
        if ($name === null) {
            return $this->request->request->all();
        }

        return $this->request->request->get($name, $default);
    }

    /**
     * @param string|null $name
     * @param string|null $default
     * @return array|bool|float|int|string|InputBag
     */
    public function getFromCookies(string $name = null, string $default = null)
    {
        if ($name === null) {
            return $this->request->cookies->all();
        }

        return $this->request->cookies->get($name, $default);
    }

    /* @param string|null $name
     * @param string|null $default
     * @return array|string|null
     */
    public function getFromHeaders(string $name = null, string $default = null)
    {
        if ($name === null) {
            return $this->request->headers->all();
        }

        return $this->request->headers->get($name, $default);
    }

    /**
     * @return string
     */
    public function getHttpMethod(): string
    {
        return $this->request->getMethod();
    }

    /**
     * @return string
     */
    public function getRequestUri(): string
    {
        return $this->request->getRequestUri();
    }

    /**
     * @return bool
     */
    public function isPost(): bool
    {
        return $this->request->isMethod('post');
    }

    /**
     * @param string $method
     * @return bool
     */
    public function isMethod(string $method): bool
    {
        return $this->request->isMethod($method);
    }

    /**
     * @return bool
     */
    public function isHttps(): bool
    {
        return $this->request->isSecure();
    }

    /**
     * @return bool
     */
    public function isXmlHttpRequest(): bool
    {
        return $this->request->isXmlHttpRequest();
    }

    /**
     * @param array|null $array
     * @return string|null
     */
    public function getPreferredLanguage(array $array = null): ?string
    {
        return $this->request->getPreferredLanguage($array);
    }

    public function getContent(): ?string
    {
        return $this->request->getContent();
    }

    public function getQueryString(): ?string
    {
        return $this->request->getQueryString();
    }
}