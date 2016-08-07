<?php

namespace LayerShifter\IDN\Tests;

use LayerShifter\IDN\URI;

/**
 * Class with test cases for `toASCII` and `toUTF8` of URL class.
 */
class URLConvertTest extends \PHPUnit_Framework_TestCase
{


    /**
     * Test for `toASCII` method.
     *
     * @param string $inputUrl    URL that will be converted
     * @param string $expectedUrl URL that must be received after conversion
     *
     * @dataProvider urlProvider
     *
     * @return void
     */
    public function testToASCII($inputUrl, $expectedUrl)
    {
        static::assertEquals($expectedUrl, URI::toASCII($inputUrl));
    }

    /**
     * Test for `toUTF8` method.
     *
     * @param string $inputUrl    URL that will be converted
     * @param string $expectedUrl URL that must be received after conversion
     * @param string $specificUrl URL that must be received after conversion, replaces `expectedUrl`
     *
     * @dataProvider urlProvider
     *
     * @return void
     */
    public function testToUTF8($expectedUrl, $inputUrl, $specificUrl = null)
    {
        static::assertEquals(null === $specificUrl ? $expectedUrl : $specificUrl, URI::toUTF8($inputUrl));
    }

    /**
     * Data provider for testToASCII() and testToUTF8().
     *
     * @return array
     */
    public function urlProvider()
    {
        return [
            // Malformed URLs.

            [
                null,
                null
            ],
            [
                'yandex.ru/search',
                'yandex.ru/search'
            ],
            [
                '/search',
                '/search'
            ],
            [
                'scheme+RFC-3986.compliant://php.net',
                'scheme+rfc-3986.compliant://php.net',
                'scheme+rfc-3986.compliant://php.net',
            ],
            [
                'mail@php.net',
                'mail@php.net'
            ],
            [
                'https://alfabank.ru/sendmail/?backurl=/sme/overdraft/feedback/&backname=%C7%E0%E4%E0%F2%FC%20%E2%EE%' .
                'EF%F0%EE%F1',
                'https://alfabank.ru/sendmail/?backurl=/sme/overdraft/feedback/&backname=%C7%E0%E4%E0%F2%FC%20%E2%EE%' .
                'EF%F0%EE%F1'
            ],

            // ASCII symbols.

            [
                'http://php.net/manual/ru/function.parse-url.php',
                'http://php.net/manual/ru/function.parse-url.php'
            ],
            [
                'http://usr:pss@example.com:81/mypath/myfile.html?a=b&b[]=2&b[]=3#myfragment',
                'http://usr:pss@example.com:81/mypath/myfile.html?a=b&b[]=2&b[]=3#myfragment'
            ],
            [
                'http://php.net',
                'http://php.net'
            ],
            [
                'http://php.net/',
                'http://php.net/'
            ],
            [
                'http://php.net/?test=1',
                'http://php.net/?test=1'
            ],
            [
                'hTTp://php.net/?test=1',
                'http://php.net/?test=1',
                'http://php.net/?test=1',
            ],
            [
                'http://php.NET',
                'http://php.net',
                'http://php.net',
            ],
            [
                'http://php.NET',
                'http://php.net',
                'http://php.net'
            ],
            [
                'http://php.net/?TEST',
                'http://php.net/?TEST'
            ],

            // Cyrillic symbols.

            [
                'https://ru.wikipedia.org/wiki/Аврелий_Августин',
                'https://ru.wikipedia.org/wiki/%D0%90%D0%B2%D1%80%D0%B5%D0%BB%D0%B8%D0%B9_%D0%90%D0%B2%D0%B3%D1%83%D1' .
                '%81%D1%82%D0%B8%D0%BD'
            ],
            [
                'http://www.codeisart.ru/blog/Работа-со-строками-в-javascript/',
                'http://www.codeisart.ru/blog/%D0%A0%D0%B0%D0%B1%D0%BE%D1%82%D0%B0-%D1%81%D0%BE-%D1%81%D1%82%D1%80%D0' .
                '%BE%D0%BA%D0%B0%D0%BC%D0%B8-%D0%B2-javascript/'
            ],
            [
                'http://sistem.in.ua/sample-page/вытягиваем-из-html-данные-для-анализа/',
                'http://sistem.in.ua/sample-page/%D0%B2%D1%8B%D1%82%D1%8F%D0%B3%D0%B8%D0%B2%D0%B0%D0%B5%D0%BC-%D0%B8%' .
                'D0%B7-html-%D0%B4%D0%B0%D0%BD%D0%BD%D1%8B%D0%B5-%D0%B4%D0%BB%D1%8F-%D0%B0%D0%BD%D0%B0%D0%BB%D0%B8%D0' .
                '%B7%D0%B0/',
            ],
            [
                'http://здоровьесемьи21.рф/услуги',
                'http://xn--21-dlchgakm9aqaxq6me.xn--p1ai/%D1%83%D1%81%D0%BB%D1%83%D0%B3%D0%B8'
            ],
            [
                'http://пример.рф/страница?раздел=тестовый#тестовый раздел',
                'http://xn--e1afmkfd.xn--p1ai/%D1%81%D1%82%D1%80%D0%B0%D0%BD%D0%B8%D1%86%D0%B0?%D1%80%D0%B0%D0%B7%D0%' .
                'B4%D0%B5%D0%BB=%D1%82%D0%B5%D1%81%D1%82%D0%BE%D0%B2%D1%8B%D0%B9#тестовый раздел'
            ],
            [
                'яндекс.рф/search',
                'xn--d1acpjx3f.xn--p1ai/search'
            ],
            [
                '/поиск',
                '/%D0%BF%D0%BE%D0%B8%D1%81%D0%BA'
            ],

            // Chinese symbols.

            [
                'https://zh-classical.wikipedia.org/wiki/聖奧思定',
                'https://zh-classical.wikipedia.org/wiki/%E8%81%96%E5%A5%A7%E6%80%9D%E5%AE%9A'
            ],

            // Arabic symbols.

            [
                'https://ckb.wikipedia.org/wiki/ئۆگەستین',
                'https://ckb.wikipedia.org/wiki/%D8%A6%DB%86%DA%AF%DB%95%D8%B3%D8%AA%DB%8C%D9%86'
            ],

            // Kannaḍa symbols.

            [
                'https://kn.wikipedia.org/wiki/ಸೇಂಟ್_ಆಗಸ್ಟೀನ್',
                'https://kn.wikipedia.org/wiki/%E0%B2%B8%E0%B3%87%E0%B2%82%E0%B2%9F%E0%B3%8D_%E0%B2%86%E0%B2%97%E0%B2' .
                '%B8%E0%B3%8D%E0%B2%9F%E0%B3%80%E0%B2%A8%E0%B3%8D'
            ],

            /**
             * Test cases from `url-testing`.
             * @see https://raw.githubusercontent.com/cweb/url-testing/master/urls.json
             */

            // A U+0020 space in the fragment.

            [
                'http://www.example.com/#hello, world',
                'http://www.example.com/#hello, world'
            ],

            // Percent-encoding in the fragment.

            [
                'http://www.example.com/#%c2%a9',
                'http://www.example.com/#%c2%a9'
            ],

            // Illegal percent-encoding.

            [
                'http://www.example.com/#%41%a',
                'http://www.example.com/#%41%a'
            ],

            // An extra U+0023 NUMBER SIGN in middle of the fragment.

            [
                'http://www.example.com/#asdf#qwer',
                'http://www.example.com/#asdf#qwer'
            ],

            // An extra U+0023 NUMBER SIGN at the start of fragment.

            [
                'http://www.example.com/##asdf',
                'http://www.example.com/##asdf'
            ]
        ];
    }
}
