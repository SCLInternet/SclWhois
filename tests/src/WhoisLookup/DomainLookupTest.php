<?php
/**
 * WhoisLookup library (https://github.com/tomphp/WhiosLookup)
 *
 * @link https://github.com/tomphp/BasicSocket for the canonical source repository
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace WhoisLookup;

use BasicSocket\SocketInterface;

/**
 * Test for {@see \WhoisLookup\DomainLookup}
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class DomainLookupTest extends \PHPUnit_Framework_TestCase
{
    const UNKNOWN_TLD = 'terribletld';
    const FAKE_SERVER = 'my.server.com';

    /**
     * @var DomainLookup
     */
    private $whois;

    /**
     * @var SocketInterface
     */
    private $socket;

    /**
     * Setup a DomainLookup object
     */
    protected function setUp()
    {
        $this->socket = $this->getMock('BasicSocket\SocketInterface');

        $this->whois = new DomainLookup($this->socket);
    }

    /**
     * @expectedException \WhoisLookup\Exception\UnknownTldException
     */
    public function testBadTld()
    {
        $this->whois->queryServer('mydomain.' . self::UNKNOWN_TLD);
    }

    /**
     * @covers \WhoisLookup\DomainLookup::addServer
     */
    public function testAddServer()
    {
        $this->socket->expects($this->any())->method('closed')->will($this->returnValue(true));

        $this->whois->addServer(self::UNKNOWN_TLD, self::FAKE_SERVER);
        $this->whois->queryServer('mydomain.' . self::UNKNOWN_TLD);
    }

    /**
     * @covers \WhoisLookup\DomainLookup::queryServer
     */
    public function testQueryServer()
    {
        $domainName = 'DoMaIn.CoM';
        $lcDomain = strtolower($domainName);

        $expected = 'WHOIS ENTRY';

        $this->socket->expects($this->once())
            ->method('connect')
            ->with($this->equalTo(self::FAKE_SERVER), $this->equalTo(43))
            ->will($this->returnValue(true));

        $this->socket->expects($this->once())
            ->method('write')
            ->with($this->equalTo($lcDomain . "\r\n"));

        $this->socket->expects($this->exactly(2))->method('closed')->will($this->onConsecutiveCalls(false, true));

        $this->socket->expects($this->once())
            ->method('read')
            ->will($this->returnValue($expected));

        $this->assertEquals($expected, $this->whois->queryServer($domainName, self::FAKE_SERVER));
    }
}
