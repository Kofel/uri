<?php
/**
 * Date: 13.07.2016
 * Time: 09:46
 */

namespace Kofel\Uri;

/**
 * A RFC 3986 compliant URI parser class
 * @package Kofel\Uri
 */
class UriParser
{
    const REGEXP_URI = '/^((?<scheme>[a-zA-Z][^:\/?#]+):)?(\/\/(?<authority>[^\/?#]*))?(?<path>[^?#]*)(\?(?<query>[^#]*))?(#(?<fragment>.*))?$/';
    const REGEXP_AUTHORITY = '/^((?<username>.*?)(:(?<password>.*?))?@)?(?<host>.*?)?(:(?<port>.*?))?$/';
    const REGEXP_HOSTNAME = '/^(([a-zA-Z0-9]|[a-zA-Z0-9][a-zA-Z0-9\-]*[a-zA-Z0-9])\.)*([A-Za-z0-9]|[A-Za-z0-9][A-Za-z0-9\-]*[A-Za-z0-9])$/';

    public function parse(string $uri): Uri
    {
        $components = $this->parseComponents($uri);
        $components = $this->normalizeComponents($components);

        return new Uri(
            $components['scheme'],
            $components['username'],
            $components['password'],
            $components['host'],
            $components['port'],
            $components['path'],
            $components['query'],
            $components['fragment']
        );
    }

    /**
     * Component is each element of URI as described in RFC
     */
    protected function parseComponents(string $uri): array
    {
        $components = [
            'scheme' => null,
            'path' => null,
            'query' => null,
            'fragment' => null
        ];

        $capture = [];
        preg_match(self::REGEXP_URI, $uri, $capture);
        $components = array_merge($components, $capture);

        if (isset($capture['authority'])) {
            $components = array_merge($components, $this->parseAuthority($capture['authority']));
        }

        if (isset($capture['query']) && '' !== $capture[6]) {
            $components = array_merge($components, $this->parseQuery($capture['query']));
        }
        else {
            $components['query'] = null;
        }

        return $components;
    }

    protected function parseAuthority(string $authority): array
    {
        $components = [
            'username' => null,
            'password' => null,
            'host' => null,
            'port' => null
        ];

        if ('' === $authority) {
            return $components;
        }

        $capture = [];
        preg_match(self::REGEXP_AUTHORITY, $authority, $capture);

        return array_merge($components, $capture);
    }

    protected function parseQuery(string $query): array
    {
        $output = null;
        parse_str($query, $output);

        $components = [
            'query' => $output
        ];

        return $components;
    }

    protected function normalizeComponents(array $components): array
    {
        $components['port'] = $this->normalizePort($components['port']);
        $components['host'] = $this->normalizeHost($components['host']);
        return $components;
    }

    protected function normalizePort($port)
    {
        if ('' === $port || null === $port) {
            return null;
        }

        if (!is_numeric($port)) {
            throw new \InvalidArgumentException(sprintf(
                '"%s" is not valid port.',
                $port
            ));
        }

        return (int)$port;
    }

    protected function normalizeHost($host)
    {
        if ('' === $host || null === $host) {
            return $host;
        }

        if (
            !preg_match(self::REGEXP_HOSTNAME, $host) &&
            !filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6)
        ) {
            throw new \InvalidArgumentException(sprintf(
                '"%s" is not valid host.',
                $host
            ));
        }

        return $host;
    }
}