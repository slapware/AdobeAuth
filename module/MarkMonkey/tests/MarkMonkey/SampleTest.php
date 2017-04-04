<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/MarkMonkey for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace MarkMonkeyTest;

class SampleTest extends Framework\TestCase
{

    public function testSample()
    {
        $this->assertInstanceOf('Zend\Di\LocatorInterface', $this->getLocator());
    }
}
