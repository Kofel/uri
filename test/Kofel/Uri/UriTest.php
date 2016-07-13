<?php
/**
 * Date: 13.07.2016
 * Time: 11:38
 */

namespace Kofel\Uri;


use PHPUnit\Framework\TestCase;

class UriTest extends TestCase
{
    /**
     * @dataProvider equalsTestDataProvider
     * @param Uri $a
     * @param Uri $b
     * @param $expected
     */
    public function testValidEquals(Uri $a, Uri $b, $expected)
    {
        $this->assertSame($expected, $a->equals($b));
    }

    public function equalsTestDataProvider()
    {
        return [
            'same uri' => [
                new Uri('scheme', 'username', 'password', 'host', 80, '/path', ['query' => ''], 'fragment'),
                new Uri('scheme', 'username', 'password', 'host', 80, '/path', ['query' => ''], 'fragment'),
                true
            ],
            'same nulled uri' => [
                new Uri(null, null, null, null, null, '/', null, null),
                new Uri(null, null, null, null, null, '/', null, null),
                true
            ],
            'different' => [
                new Uri('scheme', 'username', 'password', 'host', 80, '/path', ['query' => ''], 'fragment'),
                new Uri('scheme2', 'username2', 'password2', 'host2', 81, '/path2', ['query2' => ''], 'fragment2'),
                false
            ],
            'different scheme' => [
                new Uri('scheme', 'username', 'password', 'host', 80, '/path', ['query' => ''], 'fragment'),
                new Uri('scheme2', 'username', 'password', 'host', 80, '/path', ['query' => ''], 'fragment'),
                false
            ],
            'different username' => [
                new Uri('scheme', 'username', 'password', 'host', 80, '/path', ['query' => ''], 'fragment'),
                new Uri('scheme', 'username2', 'password', 'host', 80, '/path', ['query' => ''], 'fragment'),
                false
            ],
            'different password' => [
                new Uri('scheme', 'username', 'password', 'host', 80, '/path', ['query' => ''], 'fragment'),
                new Uri('scheme', 'username', 'password2', 'host', 80, '/path', ['query' => ''], 'fragment'),
                false
            ],
            'different host' => [
                new Uri('scheme', 'username', 'password', 'host', 80, '/path', ['query' => ''], 'fragment'),
                new Uri('scheme', 'username', 'password', 'host2', 80, '/path', ['query' => ''], 'fragment'),
                false
            ],
            'different port' => [
                new Uri('scheme', 'username', 'password', 'host', 80, '/path', ['query' => ''], 'fragment'),
                new Uri('scheme', 'username', 'password', 'host', 81, '/path', ['query' => ''], 'fragment'),
                false
            ],
            'different path' => [
                new Uri('scheme', 'username', 'password', 'host', 80, '/path', ['query' => ''], 'fragment'),
                new Uri('scheme', 'username', 'password', 'host', 80, '/path/deep', ['query' => ''], 'fragment'),
                false
            ],
            'different query' => [
                new Uri('scheme', 'username', 'password', 'host', 80, '/path', ['query' => ''], 'fragment'),
                new Uri('scheme', 'username', 'password', 'host', 80, '/path', ['query2' => ''], 'fragment'),
                false
            ],
            'different fragment' => [
                new Uri('scheme', 'username', 'password', 'host', 80, '/path', ['query' => ''], 'fragment'),
                new Uri('scheme', 'username', 'password', 'host', 80, '/path', ['query' => ''], 'fragment2'),
                false
            ]
        ];
    }
}