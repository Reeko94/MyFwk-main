<?php

namespace Fwk\Test\Http;

use Fwk\Http\Response;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response as SFResponse;

class ResponseTest extends TestCase
{
    /**
     * @var Response
     */
    protected Response $response;

    protected function setUp(): void
    {
        $response = new SFResponse();

        $this->response = new Response($response);
    }

    public function testGetResponseObject()
    {
        $this->assertInstanceOf(Response::class, $this->response);
    }

    public function testGetStatusCode()
    {
        $this->response->setStatusCode(200, 'OK');
        $this->assertEquals(200, $this->response->getStatusCode());
    }

    public function testSetStatusCode()
    {
        $code = 200;

        $this->response->setStatusCode(200, 'OK');

        $statusCode = $this->response->getStatusCode();

        $this->assertEquals($statusCode, $code);
    }

    public function testSetContentType()
    {
        $type = 'HTML';

        $this->response->setContentType($type);

        $result = $this->response->getContentType();

        $this->assertEquals($type, $result);
    }

    public function testSetCookie()
    {
        $this->response->deleteCookie('test');

        $cookies = $this->response->getResponseObject()->headers->getCookies();

        $this->assertEmpty($cookies);

        $this->response->setCookie('test', 'value', 12, '/test', 'domain', true, false);

        $cookies = $this->response->getResponseObject()->headers->getCookies();

        $this->assertNotEmpty($cookies);

        $cookie = $cookies[0];

        $this->assertEquals('test', $cookie->getName());
        $this->assertEquals('value', $cookie->getValue());
        $this->assertEquals('/test', $cookie->getPath());
        $this->assertEquals(12, $cookie->getExpiresTime());
        $this->assertEquals('domain', $cookie->getDomain());
        $this->assertTrue($cookie->isSecure());
        $this->assertFalse($cookie->isHttpOnly());

        $this->response->deleteCookie('test', '/test', 'domain');

        $cookies = $this->response->getResponseObject()->headers->getCookies();

        $this->assertEmpty($cookies);
    }

    public function testContent()
    {
        $content = $this->response->getContent();

        $this->assertEmpty($content);

        $this->response->setContent('test');

        $this->assertEquals('test', $this->response->getContent());

        $this->response->addContent('value');

        $this->assertEquals('testvalue', $this->response->getContent());

        $this->response->prependContent('before');

        $this->assertEquals('beforetestvalue', $this->response->getContent());
    }

    public function testSetHeader()
    {
        $this->response->setHeader('key', 'value');
        $this->response->setHeader('key', 'value1');

        $this->assertEquals('value1', $this->response->getResponseObject()->headers->get('key'));
    }

    public function testSend()
    {
        $this->response->setContent('test');

        $this->response->send();

        $this->expectOutputString('test');
    }
}
