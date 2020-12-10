<?php


namespace Fwk\Test\Http;


use Fwk\Http\Session;
use Mockery;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\Session as SFSession;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;


class MockSession extends SFSession
{

    protected bool $isStarted;

    public int $id;
    public $destroy;
    public $lifetime;#

    public function __construct()
    {
        $this->isStarted = false;
        $this->id = 1;
    }

    public function start()
    {
        $this->isStarted = true;
    }

    public function invalidate(int $lifetime = null)
    {
        $this->isStarted = false;
        ++$this->id;
    }

    public function migrate(bool $destroy = false, int $lifetime = null)
    {
        $this->destroy = $destroy;
        $this->lifetime = $lifetime;
    }

    public function isStarted(): bool
    {
        return $this->isStarted;
    }

}

class SessionTest extends TestCase
{
    /**
     * @var Session
     */
    protected Session $session;

    protected function setUp(): void
    {
        $sessionStorage = new NativeSessionStorage();
        $session = new SFSession($sessionStorage);

        $this->session = new Session($session);
    }

    protected function tearDown(): void
    {
        $_SESSION = [];
    }

    public function testGetSession()
    {
        $session = $this->session->getSessionObject();

        $this->assertInstanceOf(SFSession::class, $session);
    }

    public function testStart()
    {
        $mockSession = new MockSession();
        $this->session = new Session($mockSession);

        $this->assertFalse($this->session->isStarted());

        $session = $this->session->start();
        $this->assertTrue($this->session->isStarted());
        $this->assertInstanceOf(Session::class, $session);
    }

    public function testDestroy()
    {
        $mockSession = new MockSession();
        $this->session = new Session($mockSession);
        $this->session->start();
        $session = $this->session->destroy();

        $this->assertInstanceOf(Session::class, $session);
    }

    public function testRegenerateId()
    {
        $mockSession = new MockSession();
        $this->session = new Session($mockSession);

        $session = $this->session->regenerateId();
        $this->assertFalse($mockSession->destroy);
        $this->assertInstanceOf(Session::class, $session);
    }
}