<?php


namespace Fwk\Http;


use Symfony\Component\HttpFoundation\InputBag;

interface RequestInterface
{

    public function getFromServer(string $name = null, string $default = null);

    public function getFromQuery(string $name = null, string $default = null);

    public function getFromPost(string $name = null, string $default = null);

    public function getFromCookies(string $name = null, string $default = null);

    public function getFromHeaders(string $name = null, string $default = null);

    public function getBaseUrl(): string;

    public function getBasePath(): string;

    public function getRequestUri(): string;

    public function getPathInfo(): string;

    public function getHttpMethod(): string;

    public function getQueryString(): ?string;

    /**
     * @return bool
     */
    public function isPost(): bool;

    /**
     * @param string $method
     * @return bool
     */
    public function isMethod(string $method): bool;

    /**
     * @return bool
     */
    public function isHttps(): bool;

    /**
     * @return bool
     */
    public function isXmlHttpRequest(): bool;

    public function getPreferredLanguage(array $array = null): ?string;

    public function getContent(): ?string;

}