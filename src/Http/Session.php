<?php


namespace Fwk\Http;


use stdClass;
use Symfony\Component\HttpFoundation\Session\SessionInterface as SFSessionInterface;

class Session implements SessionInterface
{
    /**
     * @var SFSessionInterface
     */
    private $session;

    public function __construct(SFSessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @return SFSessionInterface
     */
    public function getSessionObject(): SFSessionInterface
    {
        return $this->session;
    }

    public function isStarted(): bool
    {
        return $this->session->isStarted();
    }

    public function start(): Session
    {
        $this->session->start();

        return $this;
    }

    public function destroy(): Session
    {
        $this->session->invalidate();

        return $this;
    }

    public function regenerateId(): Session
    {
        $this->session->migrate(false);

        return $this;
    }

    public function getContainer($name)
    {
        if (!$this->session->has($name)) {
            $this->session->set($name, new stdClass());
        }

        return $this->session->get($name);
    }

    public function dropContainer($name): Session
    {
        $this->session->remove($name);

        return $this;
    }
}