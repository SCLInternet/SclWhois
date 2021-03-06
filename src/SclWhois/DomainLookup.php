<?php
/**
 * SclWhois library (https://github.com/SCLInternet/SclWhois)
 *
 * @link https://github.com/SCLInternet/SclWhois for the canonical source repository
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace SclWhois;

use SclSocket\SocketInterface;
use SclWhois\Exception\UnknownTldException;

/**
 * Performs whois lookups on domain names.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class DomainLookup
{
    const WHOIS_PORT = 43;

    /**
     * A list of whois servers keyed by the TLD.
     *
     * @var array
     */
    protected $server = array();

    /**
     * The socket connection object.
     *
     * @var SocketInterface
     */
    protected $socket;

    /**
     * Pulls in the default list of servers.
     */
    public function __construct(SocketInterface $socket)
    {
        $this->server = include __DIR__ . '/../../config/servers.config.php';
        $this->socket = $socket;
    }

    /**
     * Can be used to add a server for a given TLD.
     *
     * @param string $tld
     * @param string $server
     *
     * @return Whois
     */
    public function addServer($tld, $server)
    {
        $this->server[(string) $tld] = (string)$server;
        return $this;
    }

    /**
     * Returns the very top level domain.
     *
     * @param string $domain
     *
     * @return string
     */
    protected function getTld($domain)
    {
        $parts = explode('.', $domain);
        return array_pop($parts);
    }

    /**
     * Returns the whois server for the given domain name.
     *
     * @param string $domain
     *
     * @return string
     *
     * @throws UnknownTldException
     */
    public function getWhoisServer($domain)
    {
        $domain = strtolower($domain);

        $tld = $this->getTld($domain);

        if (!isset($this->server[$tld])) {
            throw new UnknownTldException("$tld is not in the list of servers.");
        }

        return $this->server[$tld];
    }

    /**
     * Recursively lookups up a domain name.
     *
     * @param string $domain
     *
     * @return string
     *
     * @throws UnknownTldException
     */
    public function lookup($domain)
    {
        $result = $this->queryServer($domain);

        while (preg_match_all('/.*Whois Server: (.*)/', $result, $matches)) {
            $server = array_pop($matches[1]);

            if ($server) {
                $result = $this->queryServer($domain, $server);
            }
        }

        return $result;
    }

    /**
     * Performs a single lookup to a given whois server.
     *
     * @param string       $domain
     * @param string|false $server
     */
    public function queryServer($domain, $server = false)
    {
        $domain = strtolower($domain);

        if (!$server) {
            $server = $this->getWhoisServer($domain);
        }

        if (!$this->socket->connect($server, self::WHOIS_PORT)) {
            return false;
        }

        // @todo This shouldn't be hard coded
        if ($server == 'whois.verisign-grs.com') {
            $domain = '=' . $domain;
        }

        $this->socket->write($domain . "\r\n");

        $result = '';

        while (!$this->socket->closed()) {
            $result .= $this->socket->read();
        }

        $this->socket->disconnect();

        return $result;
    }
}
