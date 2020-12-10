<?php


namespace Fwk\Http;


interface SessionInterface
{

    public function isStarted();

    public function start();

    public function destroy();

    public function regenerateId();

    public function getContainer($name);

    public function dropContainer($name);

}