<?php
/**
 * Date: 13.07.2016
 * Time: 10:28
 */

namespace Kofel\Uri;


use PHPUnit\Framework\TestCase;

class UriParserTest extends TestCase
{
    /** @var UriParser */
    protected $parser;

    protected function setUp()
    {
        $this->parser = new UriParser();
    }


    /**
     * @dataProvider validUriDataProvider
     * @param string $uri
     * @param Uri $expected
     */
    public function testValidParseAsExpected(string $uri, Uri $expected)
    {
        $this->assertEquals($expected, $this->parser->parse($uri));
    }

    /**
     * @dataProvider invalidUriDataProvider
     * @param string $uri
     * @param string $exceptionMessage
     */
    public function testParseFailed(string $uri, string $exceptionMessage)
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage($exceptionMessage);

        $this->parser->parse($uri);
    }

    public function validUriDataProvider()
    {
        return [
            'complete' => [
                'scheme://user:pass@host:81/path?query#fragment',
                new Uri('scheme', 'user', 'pass', 'host', 81, '/path', ['query' => ''], 'fragment')
            ],
            'not normalized' =>  [
                'ScheMe://user:pass@HoSt:81/path?query#fragment',
                new Uri('ScheMe', 'user', 'pass', 'HoSt', 81, '/path', ['query' => ''], 'fragment')
            ],
            'without scheme' =>  [
                '//user:pass@HoSt:81/path?query#fragment',
                new Uri(null, 'user', 'pass', 'HoSt', 81, '/path', ['query' => ''], 'fragment')
            ],
            'schema with complex path' => [
                'http:::/path',
                new Uri('http', null, null, null, null, '::/path', null, null)
            ],
            'single scheme with path' => [
                'scheme:example:path:deep',
                new Uri('scheme', null, null, null, null, 'example:path:deep', null, null)
            ],
            'invalid scheme' => [
                '0scheme://host/path?query#fragment',
                new Uri(null, null, null, null, null,'0scheme://host/path', ['query' => ''], 'fragment')
            ],
            'without username and password' =>  [
                'scheme://HoSt:81/path?query#fragment',
                new Uri('scheme', null, null, 'HoSt', 81, '/path', ['query' => ''], 'fragment')
            ],
            'with empty username' =>  [
                'scheme://@HoSt:81/path?query#fragment',
                new Uri('scheme', '', null, 'HoSt', 81, '/path', ['query' => ''], 'fragment')
            ],
            'without port' => [
                'scheme://user:pass@host/path?query#fragment',
                new Uri('scheme', 'user', 'pass', 'host', null, '/path', ['query' => ''], 'fragment')
            ],
            'with an empty port' => [
                'scheme://user:pass@host:/path?query#fragment',
                new Uri('scheme', 'user', 'pass', 'host', null, '/path', ['query' => ''], 'fragment')
            ],
            'without username, password and port' => [
                'scheme://host/path?query#fragment',
                new Uri('scheme', null, null, 'host', null, '/path', ['query' => ''], 'fragment')
            ],
            'without authority' => [
                'scheme:path?query#fragment',
                new Uri('scheme', null, null, null, null, 'path', ['query' => ''], 'fragment')
            ],
            'without authority and scheme' => [
                '/path',
                new Uri(null, null, null, null, null, '/path', null, null)
            ],
            'with an empty host' => [
                'scheme:///path?query#fragment',
                new Uri('scheme', null, null, '', null, '/path', ['query' => ''], 'fragment')
            ],
            'without path' => [
                'scheme://host?query#fragment',
                new Uri('scheme', null, null, 'host', null, '', ['query' => ''], 'fragment')
            ],
            'without query' => [
                'scheme:path#fragment',
                new Uri('scheme', null, null, null, null, 'path', null, 'fragment')
            ],
            'with empty query' => [
                'scheme:path?#fragment',
                new Uri('scheme', null, null, null, null, 'path', [], 'fragment')
            ],
            'with query only' => [
                '?query',
                new Uri(null, null, null, null, null, '', ['query' => ''], null)
            ],
            'without fragment' => [
                'scheme:path',
                new Uri('scheme', null, null, null, null, 'path', null, null)
            ],
            'with empty fragment' => [
                'scheme:path#',
                new Uri('scheme', null, null, null, null, 'path', null, '')
            ],
            'with fragment only' => [
                '#fragment',
                new Uri(null, null, null, null, null, null, null, 'fragment')
            ],
            'without authority 2' => [
                'path#fragment',
                new Uri(null, null, null, null, null, 'path', null, 'fragment')
            ],
            'with empty query and fragment' => [
                '?#',
                new Uri(null, null, null, null, null, '', [], '')
            ],
            'without scheme but a path' => [
                'file.txt',
                new Uri(null, null, null, null, null, 'file.txt', null, null)
            ],
            'with relative path only' => [
                '../relative/path',
                new Uri(null, null, null, null, null, '../relative/path', null, null)
            ],
            'path as single word' => [
                'http',
                new Uri(null, null, null, null, null, 'http', null, null)
            ],
            'with complex authority' => [
                'http://a_.!~*\'(-)n0123Di%25%26:pass;:&=+$,word@www.zend.com',
                new Uri('http', 'a_.!~*\'(-)n0123Di%25%26', 'pass;:&=+$,word', 'www.zend.com', null, '', null, null)
            ]
        ];
    }

    public function invalidUriDataProvider()
    {
        return [
            'invalid port' => [
                'scheme://host:port/path?query#fragment',
                'is not valid port'
            ],
            'invalid host' => [
                'scheme://[127.0.0.1]/path?query#fragment',
                'is not valid host'
            ]
        ];
    }
}