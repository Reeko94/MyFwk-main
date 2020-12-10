<?php


namespace Fwk\src\Util;


use Fwk\Util\Acl;
use PHPUnit\Framework\TestCase;

class AclTest extends TestCase
{

    public function testGetMvcResourceFromString()
    {
        $mvcResource = [
            'type' => 'mvc',
            'module' => 'module',
            'controller' => 'controller',
            'action' => 'action'
        ];

        $data = Acl::getMvcResourceFromString('mvc.module.controller.action');
        $this->assertEquals($mvcResource, $data);

        $mvcResource['action'] = null;
        $data = Acl::getMvcResourceFromString('mvc.module.controller');
        $this->assertEquals($mvcResource, $data);

        $mvcResource['controller'] = null;
        $data = Acl::getMvcResourceFromString('mvc.module');
        $this->assertEquals($mvcResource, $data);

        $data = Acl::getMvcResourceFromString('mvc');
        $this->assertEquals(null, $data);

        $data = Acl::getMvcResourceFromString('');
        $this->assertEquals(null, $data);

    }

}