<?php

namespace LayerShifter\IDN\Tests;

use LayerShifter\IDN\URI;

/**
 * Class URITest
 * @package LayerShifter\IDN\Tests
 */
class URITest extends \PHPUnit_Framework_TestCase
{

    /**
     * @param string $inputURI
     * @param array  $inputResult
     *
     * @dataProvider parseProvider
     */
    public function testParse($inputURI, $inputResult)
    {
        $result = URI::parse($inputURI);

        foreach ($inputResult as $part => $value) {
            static::assertArrayHasKey($part, $result);
            static::assertEquals($result[$part], $value);
        }
    }

    /**
     *
     *
     */
    public function parseProvider()
    {
        return [
            [
                'http://example.com',
                [
                    'scheme' => 'http',

                    'host'     => 'example.com',
                    'port'     => null,
                    'path'     => null,
                    'query'    => null,
                    'fragment' => null
                ]
            ],
            [
                'EXAMPLE.COM',
                [
                    'host' => 'example.com',
                    'path' => null
                ]
            ],
            [
                'www.example.com',
                [
                    'host' => 'www.example.com',
                    'path' => null
                ]
            ],
            [
                'WWW.EXAMPLE.COM',
                [
                    'host' => 'www.example.com',
                    'path' => null
                ]
            ],
            [
                'https://www.EXAMPLE.cOm',
                [
                    'scheme' => 'https',
                    'host'   => 'www.example.com',
                    'path'   => null
                ]
            ],
            [
                'HTTPS://www.EXAMPLE.cOm/',
                [
                    'scheme' => 'https',
                    'host'   => 'www.example.com',
                    'path'   => '/'
                ]
            ],
            [
                'ftp://example.com',
                [
                    'scheme' => 'ftp',
                    'host'   => 'example.com',
                    'path'   => null
                ]
            ],
            [
                'http://www.example.com/foo/bar',
                [
                    'scheme' => 'http',
                    'host'   => 'www.example.com',
                    'path'   => '/foo/bar'
                ]
            ],
            [
                'http://www.example.com/foo/bar/',
                [
                    'scheme' => 'http',
                    'host'   => 'www.example.com',
                    'path'   => '/foo/bar/'
                ]
            ],
            [
                'http://www.example.com/foO/BaR',
                [
                    'scheme' => 'http',
                    'host'   => 'www.example.com',
                    'path'   => '/foO/BaR'
                ]
            ],
            [
                'example.com/foo/bar',
                [
                    'scheme' => null,
                    'host'   => 'example.com',
                    'path'   => '/foo/bar'
                ]
            ],
            [
                'www.example.com/foo/bar',
                [
                    'scheme' => null,
                    'host'   => 'www.example.com',
                    'path'   => '/foo/bar'
                ]
            ],
            [
                'http://www.example.com?foo=BaR',
                [
                    'scheme' => 'http',
                    'host'   => 'www.example.com',
                    'path'   => null,
                    'query'  => 'foo=BaR'
                ]
            ],
            [
                'http://www.example.com#fooBaR',
                [
                    'scheme'   => 'http',
                    'host'     => 'www.example.com',
                    'path'     => null,
                    'fragment' => 'fooBaR'
                ]
            ],
            [
                'http://www.example.com:8080',
                [
                    'scheme' => 'http',
                    'host'   => 'www.example.com',
                    'path'   => null
                ]
            ],
            [
                'http://www.example.com:8080/foo/bar',
                [
                    'scheme' => 'http',
                    'host'   => 'www.example.com',
                    'port'   => 8080,
                    'path'   => '/foo/bar'
                ]
            ],
            [
                'www.example.com:8080',
                [
                    'scheme' => null,
                    'host'   => 'www.example.com',
                    'port'   => 8080,
                    'path'   => null
                ]
            ],
            [
                'www.example.com:8080/foo/bar',
                [
                    'scheme' => null,
                    'host'   => 'www.example.com',
                    'port'   => 8080,
                    'path'   => '/foo/bar'
                ]
            ],
            [
                'www.example.com/foo/捦挺挎/bar',
                [
                    'scheme' => null,
                    'host'   => 'www.example.com',
                    'path'   => '/foo/捦挺挎/bar'
                ]
            ],
            [
                'atat.امارات',
                [
                    'scheme' => null,
                    'host'   => 'atat.امارات',
                    'path'   => null
                ]
            ],
            [
                'http://www.example.com/foo/bar?a=b&c=d',
                [
                    'scheme' => 'http',
                    'host'   => 'www.example.com',
                    'path'   => '/foo/bar',
                    'query'  => 'a=b&c=d'
                ]
            ],
            [
                'https://user@example.com:8080',
                [
                    'scheme' => 'https',
                    'user'   => 'user',
                    'port'   => 8080
                ]
            ],
            [
                'https://user:pass@example.com:8080',
                [
                    'scheme' => 'https',
                    'user'   => 'user',
                    'pass'   => 'pass',
                    'port'   => 8080,

                ]
            ],
            [
                '//www.example.com/foo?bar#baz',
                [
                    'scheme'   => null,
                    'host'     => 'www.example.com',
                    'path'     => '/foo',
                    'query'    => 'bar',
                    'fragment' => 'baz'
                ]
            ],
            [
                'www.example.com',
                [
                    'host' => 'www.example.com',
                    'path' => null
                ]
            ],
            [
                'foo/bar/',
                [
                    'host' => null,
                    'path' => 'foo/bar/'
                ],
            ],
            [
                'Http://Example.com/PaTH',
                [
                    'scheme' => 'http',
                    'host'   => 'example.com',
                    'path'   => '/PaTH'
                ],
            ],
            [
                'http://a/b/c/d;p?q',
                [
                    'scheme' => 'http',
                    'host'   => 'a',
                    'path'   => '/b/c/d;p',
                    'query'  => 'q'
                ]
            ],
            [
                'http://www.google.com',
                [
                    'scheme' => 'http',
                    'host'   => 'www.google.com',
                    'path'   => null
                ]
            ],
            [
                'http://www/foo%2Ehtml',
                [
                    'scheme' => 'http',
                    'host'   => 'www',
                    'path'   => '/foo%2Ehtml'
                ]
            ],
            [
                'http://www/foo/%2E/html',
                [
                    'scheme' => 'http',
                    'host'   => 'www',
                    'path'   => '/foo/%2E/html'
                ]
            ],
            [
                'http://%25DOMAIN:foobar@foodomain.com/ ',
                [
                    'scheme' => 'http',
                    'user'   => '%25DOMAIN',
                    'pass'   => 'foobar',
                    'host'   => 'foodomain.com',
                    'path'   => '/'
                ]
            ],
            [
                'a://example.com',
                [
                    'scheme' => 'a',
                    'user'   => null,
                    'pass'   => null,
                    'host'   => 'example.com',
                    'path'   => null
                ]
            ],
            [
                'data:example.com',
                [
                    'scheme' => 'data',
                    'host'   => null,
                    'path'   => 'example.com'
                ]
            ],
            [
                'javascript:example.com/',
                [
                    'scheme' => 'javascript',
                    'host'   => null,
                    'path'   => 'example.com/'
                ]
            ],
            [
                'mailto:example.com/',
                [
                    'scheme' => 'mailto',
                    'host'   => null,
                    'path'   => 'example.com/'
                ]
            ]
        ];
    }

    /**
     * @param string $inputURI
     *
     * @dataProvider parseInvalidProvider
     */
    public function testInvalidParse($inputURI)
    {
        self::assertNull(URI::parse($inputURI));
    }

    /**
     * @return array
     */
    public function parseInvalidProvider()
    {
        return [
            [null]
        ];


//,	{ "urn:issn:1535-3613", 4, { URI_PARSE_RESET, URI_HAS_SCHEME, URI_HAS_PATH, URI_PARSE_DONE } }
//,	{ "/relative/URI/with/absolute/path/to/resource.txt", 3, { URI_PARSE_RESET, URI_HAS_PATH, URI_PARSE_DONE } }
//,	{ "relative/path/to/resource.txt", 3, { URI_PARSE_RESET, URI_HAS_PATH, URI_PARSE_DONE } }
//,	{ "./resource.txt#frag01", 4, { URI_PARSE_RESET, URI_HAS_PATH, URI_HAS_FRAGMENT, URI_PARSE_DONE } }
//,	{ "resource.txt", 3, { URI_PARSE_RESET, URI_HAS_PATH, URI_PARSE_DONE } }
//,	{ "#frag01", 4, { URI_PARSE_RESET, URI_HAS_EMPTY_PATH, URI_HAS_FRAGMENT, URI_PARSE_DONE } }
//,	{ "", 3, { URI_PARSE_RESET, URI_HAS_EMPTY_PATH, URI_PARSE_DONE } }
//,	{ "https://www.google.com/?q=URI+percent+encoding+!*'()%3B%3A%40%26%3D%2B%24%2C%2F%3F%23%5B%5D", 6, { URI_PARSE_RESET, URI_HAS_SCHEME, URI_HAS_HOST, URI_HAS_PATH, URI_HAS_QUERY, URI_PARSE_DONE } }
//,	{ "?q=URI+percent+encoding+!*'()%3B%3A%40%26%3D%2B%24%2C%2F%3F%23%5B%5D", 2, { URI_HAS_QUERY, URI_PARSE_DONE } }
//,	{ "sip:biloxi.com;method=REGISTER;transport=tcp?to=sip:bob%40biloxi.com", 5, { URI_PARSE_RESET, URI_HAS_SCHEME, URI_HAS_PATH, URI_HAS_QUERY, URI_PARSE_DONE } }
//,	{ "mailto:?to=joe@xyz.com&amp;cc=bob@xyz.com&amp;body=hello", 5, { URI_PARSE_RESET, URI_HAS_SCHEME, URI_HAS_EMPTY_PATH, URI_HAS_QUERY, URI_PARSE_DONE } }
//,	{ "file:///c:/WINDOWS/clock.avi", 4, { URI_PARSE_RESET, URI_HAS_SCHEME, URI_HAS_PATH, URI_PARSE_DONE } }
//,	{ "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAUAAAAFCAYAAACNbyblAAAAHElEQVQI12P4//8/w38GIAXDIBKE0DHxgljNBAAO9TXL0Y4OHwAAAABJRU5ErkJggg==", 4, { URI_PARSE_RESET, URI_HAS_SCHEME, URI_HAS_PATH, URI_PARSE_DONE } }
//,	{ "ftp://me@you.com/my%20test.asp?name=st%C3%A5le&car=saab", 7, { URI_PARSE_RESET, URI_HAS_SCHEME, URI_HAS_USERINFO, URI_HAS_HOST, URI_HAS_PATH, URI_HAS_QUERY, URI_PARSE_DONE } }
//,	{ "tag:example.com,2004:fred:", 4, { URI_PARSE_RESET, URI_HAS_SCHEME, URI_HAS_PATH, URI_PARSE_DONE } }
//,	{ "qemu+unix:///system?socket=/opt/libvirt/run/libvirt/libvirt-sock", 5, { URI_PARSE_RESET, URI_HAS_SCHEME, URI_HAS_PATH, URI_HAS_QUERY, URI_PARSE_DONE } }
//,	{ "test+tcp://node.example.com:5000/default", 6, { URI_PARSE_RESET, URI_HAS_SCHEME, URI_HAS_HOST, URI_HAS_PORT, URI_HAS_PATH, URI_PARSE_DONE } }
//,	{ "news://server.example/ab.cd@example.com", 5, { URI_PARSE_RESET, URI_HAS_SCHEME, URI_HAS_HOST, URI_HAS_PATH, URI_PARSE_DONE } }
//,	{ "geo:66,30;u=6.500;FOo=this%2dthat", 4, { URI_PARSE_RESET, URI_HAS_SCHEME, URI_HAS_PATH, URI_PARSE_DONE } }
//,	{ "magnet:?xt.1=urn:sha1:YNCKHTQCWBTRNJIV4WNAE52SJUQCZO5C&xt.2=urn:sha1:TXGCZQTH26NL6OUQAJJPFALHG2LTGBC7", 4, { URI_PARSE_RESET, URI_HAS_SCHEME, URI_HAS_EMPTY_PATH, URI_HAS_QUERY, URI_PARSE_DONE } }
//,	{ "tel:863-1234;phone-context=+1-914-555", 4, { URI_PARSE_RESET, URI_HAS_SCHEME, URI_HAS_PATH, URI_PARSE_DONE } }
//,	{ "http://127.0.0.1:9999/", 6, { URI_PARSE_RESET, URI_HAS_SCHEME, URI_HAS_HOST, URI_HAS_PORT, URI_HAS_PATH, URI_PARSE_DONE } }
//,	{ "http://[FEDC:BA98:7654:3210:FEDC:BA98:7654:3210]:80/index.html", 6, { URI_PARSE_RESET, URI_HAS_SCHEME, URI_HAS_HOST, URI_HAS_PORT, URI_HAS_PATH, URI_PARSE_DONE } }
//,	{ "http://[1080:0:0:0:8:800:200C:417A]/index.html", 5, { URI_PARSE_RESET, URI_HAS_SCHEME, URI_HAS_HOST, URI_HAS_PATH, URI_PARSE_DONE } }
//,	{ "http://[3ffe:2a00:100:7031::1]", 5, { URI_PARSE_RESET, URI_HAS_SCHEME, URI_HAS_HOST, URI_HAS_EMPTY_PATH, URI_PARSE_DONE } }
//,	{ "http://[1080::8:800:200C:417A]/foo", 5, { URI_PARSE_RESET, URI_HAS_SCHEME, URI_HAS_HOST, URI_HAS_PATH, URI_PARSE_DONE } }
//,	{ "http://[::192.9.5.5]/ipng", 5, { URI_PARSE_RESET, URI_HAS_SCHEME, URI_HAS_HOST, URI_HAS_PATH, URI_PARSE_DONE } }
//,	{ "http://[::FFFF:129.144.52.38]:80/index.html", 6, { URI_PARSE_RESET, URI_HAS_SCHEME, URI_HAS_HOST, URI_HAS_PORT, URI_HAS_PATH, URI_PARSE_DONE } }
//,	{ "http://[2010:836B:4179::836B:4179]", 5, { URI_PARSE_RESET, URI_HAS_SCHEME, URI_HAS_HOST, URI_HAS_EMPTY_PATH, URI_PARSE_DONE } }
//,	{ "ftp://ftp.is.co.za/rfc/rfc1808.txt", 5, { URI_PARSE_RESET, URI_HAS_SCHEME, URI_HAS_HOST, URI_HAS_PATH, URI_PARSE_DONE } }
//,	{ "http://www.ietf.org/rfc/rfc2396.txt", 5, { URI_PARSE_RESET, URI_HAS_SCHEME, URI_HAS_HOST, URI_HAS_PATH, URI_PARSE_DONE } }
//,	{ "ldap://[2001:db8::7]/c=GB?objectClass?one", 6, { URI_PARSE_RESET, URI_HAS_SCHEME, URI_HAS_HOST, URI_HAS_PATH, URI_HAS_QUERY, URI_PARSE_DONE } }
//,	{ "mailto:John.Doe@example.com", 4, { URI_PARSE_RESET, URI_HAS_SCHEME, URI_HAS_PATH, URI_PARSE_DONE } }
//,	{ "news:comp.infosystems.www.servers.unix", 4, { URI_PARSE_RESET, URI_HAS_SCHEME, URI_HAS_PATH, URI_PARSE_DONE } }
//,	{ "tel:+1-816-555-1212", 4, { URI_PARSE_RESET, URI_HAS_SCHEME, URI_HAS_PATH, URI_PARSE_DONE } }
//,	{ "telnet://192.0.2.16:80/", 6, { URI_PARSE_RESET, URI_HAS_SCHEME, URI_HAS_HOST, URI_HAS_PORT, URI_HAS_PATH, URI_PARSE_DONE } }
//,	{ "urn:oasis:names:specification:docbook:dtd:xml:4.1.2", 4, { URI_PARSE_RESET, URI_HAS_SCHEME, URI_HAS_PATH, URI_PARSE_DONE } }
    }
}
