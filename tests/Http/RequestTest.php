<?php


namespace Fwk\Test\Http;


use Fwk\Http\Request;
use Mockery;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request as SFRequest;

class RequestTest extends TestCase
{
    /**
     * @var Request
     */
    protected Request $request;

    protected function setUp(): void
    {
        $request = SFRequest::createFromGlobals();

        $this->request = new Request($request);
    }

    public function testConstruct()
    {
        $result = $this->request->getRequestObject();

        $this->assertInstanceOf(SFRequest::class, $result);
    }

    public function testGetBaseUrlEmpty()
    {
        $this->mockBaseUrl('');

        $this->assertEquals('', $this->request->getBaseUrl());
    }

    public function testGetBaseUrl()
    {
        $this->mockBaseUrl('/test');

        $this->assertEquals('/test', $this->request->getBaseUrl());
    }

    public function testGetBasePathEmpty()
    {
        $this->mockBasePath('');
        $this->assertEquals('', $this->request->getBasePath());
    }

    public function testGetPathInfo()
    {
        $this->mockBasePathInfo('/test');
        $this->assertEquals('/test', $this->request->getPathInfo());
    }

    public function testGetFromServer()
    {
        $this->request->getRequestObject()->server->set('test', 'value');
        $result = $this->request->getFromServer('test');
        $this->assertEquals('value', $result);

        $this->request->getRequestObject()->server->set('test2', 'value2');
        $result = $this->request->getFromServer();
        $this->assertArrayHasKey('test', $result);
        $this->assertArrayHasKey('test2', $result);

        $result = $this->request->getFromServer('test3', 'test2');
        $this->assertEquals('test2', $result);
    }

    public function testGetFromQuery()
    {
        $this->request->getRequestObject()->query->set('test', 'value');
        $result = $this->request->getFromQuery('test');
        $this->assertEquals('value', $result);

        $this->request->getRequestObject()->query->set('test2', 'value2');
        $result = $this->request->getFromQuery();
        $this->assertArrayHasKey('test', $result);
        $this->assertArrayHasKey('test2', $result);

        $result = $this->request->getFromQuery('test3', 'test2');
        $this->assertEquals('test2', $result);

    }

    public function testGetFromPost()
    {
        $this->request->getRequestObject()->request->set('test', 'value');
        $result = $this->request->getFromPost('test');
        $this->assertEquals('value', $result);

        $this->request->getRequestObject()->request->set('test2', 'value2');
        $result = $this->request->getFromPost();
        $this->assertArrayHasKey('test', $result);
        $this->assertArrayHasKey('test2', $result);

        $result = $this->request->getFromPost('test3', 'test2');
        $this->assertEquals('test2', $result);

    }

    public function testGetFromCookies()
    {
        $this->request->getRequestObject()->cookies->set('test', 'value');
        $result = $this->request->getFromCookies('test');
        $this->assertEquals('value', $result);

        $this->request->getRequestObject()->cookies->set('test2', 'value2');
        $result = $this->request->getFromCookies();
        $this->assertArrayHasKey('test', $result);
        $this->assertArrayHasKey('test2', $result);

        $result = $this->request->getFromCookies('test3', 'test2');
        $this->assertEquals('test2', $result);

    }

    public function testGetFromHeaders()
    {
        $this->request->getRequestObject()->headers->set('test', 'value');
        $result = $this->request->getFromHeaders('test');
        $this->assertEquals('value', $result);

        $this->request->getRequestObject()->headers->set('test2', 'value2');
        $result = $this->request->getFromHeaders();
        $this->assertArrayHasKey('test', $result);
        $this->assertArrayHasKey('test2', $result);

        $result = $this->request->getFromHeaders('test3', 'test2');
        $this->assertEquals('test2', $result);

    }

    public function testHttpMethod()
    {
        $method = $this->request->getHttpMethod();

        $this->assertEquals('GET', $method);

        $this->request->getRequestObject()->setMethod('POST');

        $method = $this->request->getHttpMethod();

        $this->assertEquals('POST', $method);
    }

    public function testGetRequestUri()
    {
        $this->request->getRequestObject()->server->set('REQUEST_URI', 'test');
        $this->assertEquals('test', $this->request->getRequestUri());
    }

    public function testIsPost()
    {
        $this->request->getRequestObject()->setMethod('POST');
        $this->assertTrue($this->request->isPost());
    }

    public function testIsMethod()
    {
        $this->assertTrue($this->request->isMethod('GET'));
        $this->assertFalse($this->request->isMethod('POST'));
    }

    public function testIsHttps()
    {
        $this->assertFalse($this->request->isHttps());

        $this->request->getRequestObject()->server->set('HTTPS', true);

        $this->assertTrue($this->request->isHttps());
    }

    public function testIsXmlHttpRequest()
    {
        $this->assertFalse($this->request->isXmlHttpRequest());

        $this->request->getRequestObject()->headers->set('X-Requested-With','XMLHttpRequest');

        $this->assertTrue($this->request->isXmlHttpRequest());
    }

    protected function mockBasePath(string $baseUrl)
    {
        $request = Mockery::mock(SFRequest::class);
        $request->shouldReceive('getBasePath')
            ->once()
            ->withNoArgs()
            ->andReturn($baseUrl);

        $this->request = new Request($request);
    }

    protected function mockBasePathInfo(string $baseUrl)
    {
        $request = Mockery::mock(SFRequest::class);
        $request->shouldReceive('getPathInfo')
            ->once()
            ->withNoArgs()
            ->andReturn($baseUrl);

        $this->request = new Request($request);
    }

    protected function mockBaseUrl(string $baseUrl)
    {
        $request = Mockery::mock(SFRequest::class);

        $request->shouldReceive('getBaseUrl')
            ->once()
            ->withNoArgs()
            ->andReturn($baseUrl);

        $this->request = new Request($request);
    }
}