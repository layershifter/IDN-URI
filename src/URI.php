<?php

namespace LayerShifter\IDN;

class URI
{

    public static function toASCII($uri, $convertHost = true)
    {
        $parseResult = static::parse($uri);

        if (null === $parseResult) {
            return null;
        }


        if ($convertHost === true && null !== $parseResult['host']) {
            $parseResult['host'] = idn_to_ascii($parseResult['host']);
        }

        if (null !== $parseResult['path']) {
            $parseResult['path'] = preg_replace_callback('/[^\x20-\x7f]/', function ($match) {
                return urlencode($match[0]);
            }, $parseResult['path']);
        }

        if (null !== $parseResult['query']) {
            $parseResult['query'] = preg_replace_callback('/[^\x20-\x7f]/', function ($match) {
                return urlencode($match[0]);
            }, $parseResult['query']);
        }

        return static::build($parseResult);
    }


    private static function parse($uri)
    {
        $uri = trim($uri);

        if ($uri === '') {
            return null;
        }

        $uriParts = parse_url($uri);

        if ($uriParts === false) {
            return null;
        }

        return [
            'scheme'   => array_key_exists('scheme', $uriParts) ? $uriParts['scheme'] : null,
            'user'     => array_key_exists('user', $uriParts) ? $uriParts['user'] : null,
            'pass'     => array_key_exists('pass', $uriParts) ? $uriParts['pass'] : null,
            'host'     => array_key_exists('host', $uriParts) ? $uriParts['host'] : null,
            'port'     => array_key_exists('port', $uriParts) ? $uriParts['port'] : null,
            'path'     => array_key_exists('path', $uriParts) ? $uriParts['path'] : null,
            'query'    => array_key_exists('query', $uriParts) ? $uriParts['query'] : null,
            'fragment' => array_key_exists('fragment', $uriParts) ? $uriParts['fragment'] : null
        ];
    }

    private static function build($parts)
    {
        $result = '';

        if (null !== $parts['scheme']) {
            $result .= $parts['scheme'] . '://';
        }

        if (null !== $parts['user']) {
            $result .= $parts['user'];
        }

        if (null !== $parts['pass']) {
            $result .= ':' . $parts['pass'];
        }

        if (null !== $parts['user']) {
            $result .= '@';
        }

        if (null !== $parts['host']) {
            $result .= $parts['host'];
        }

        if (null !== $parts['port']) {
            $result .= ':' . $parts['port'];
        }

        if (null !== $parts['path']) {
            $result .= $parts['path'];
        }

        if (null !== $parts['query']) {
            $result .= '?' . $parts['query'];
        }

        if (null !== $parts['fragment']) {
            $result .= '#' . $parts['fragment'];
        }

        return '' === $result ? null : $result;
    }
}