<?php
/**
 * Date: 13.07.2016
 * Time: 09:37
 */

namespace Kofel\Uri;

/**
 * Uri ValueObject
 * @package Kofel\Uri
 */
class Uri
{
    /** @var string */
    private $scheme;

    /** @var string */
    private $username;

    /** @var string */
    private $password;

    /** @var string */
    private $host;

    /** @var integer */
    private $port;

    /** @var string */
    private $path;

    /** @var array */
    private $query;

    /** @var string */
    private $fragment;

    /**
     * Uri constructor.
     * @param string $scheme
     * @param string $username
     * @param string $password
     * @param string $host
     * @param int $port
     * @param string $path
     * @param array $query
     * @param string $fragment
     */
    public function __construct(
        string $scheme = null,
        string $username = null,
        string $password = null,
        string $host = null,
        int $port = null,
        string $path = null,
        array $query = null,
        string $fragment = null)
    {
        $this->scheme = $scheme;
        $this->username = $username;
        $this->password = $password;
        $this->host = $host;
        $this->port = $port;
        $this->path = $path;
        $this->query = $query;
        $this->fragment = $fragment;
    }

    /**
     * @return string|null
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * @return string|null
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return string|null
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return string|null
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @return int|null
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @return string|null
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return array|null
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @return string|null
     */
    public function getFragment()
    {
        return $this->fragment;
    }

    /**
     * @param Uri $uri
     * @return bool
     */
    public function equals(Uri $uri): bool
    {
        return (
            $this->getScheme() === $uri->getScheme() &&
            $this->getUsername() === $uri->getUsername() &&
            $this->getPassword() === $uri->getPassword() &&
            $this->getHost() === $uri->getHost() &&
            $this->getPort() === $uri->getPort() &&
            $this->getPath() === $uri->getPath() &&
            $this->getQuery() === $uri->getQuery() &&
            $this->getFragment() === $uri->getFragment()
        );
    }
}