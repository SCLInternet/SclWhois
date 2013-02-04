<?php
/**
 * SclWhois library (https://github.com/SCLInternet/SclWhois)
 *
 * @link https://github.com/SCLInternet/SclWhois for the canonical source repository
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace SclWhois;

use SclSocket\SocketInterface;

/**
 * Test for {@see \SclWhois\DomainLookup}
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
        $this->socket = $this->getMock('SclSocket\SocketInterface');

        $this->whois = new DomainLookup($this->socket);
    }

    /**
     * @expectedException \SclWhois\Exception\UnknownTldException
     */
    public function testBadTld()
    {
        $this->whois->queryServer('mydomain.' . self::UNKNOWN_TLD);
    }

    /**
     * @covers \SclWhois\DomainLookup::addServer
     */
    public function testAddServer()
    {
        $this->socket->expects($this->any())->method('closed')->will($this->returnValue(true));

        $this->whois->addServer(self::UNKNOWN_TLD, self::FAKE_SERVER);
        $this->whois->queryServer('mydomain.' . self::UNKNOWN_TLD);
    }

    /**
     * @covers \SclWhois\DomainLookup::queryServer
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
