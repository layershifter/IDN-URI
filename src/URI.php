<?php

namespace LayerShifter\IDN;

class URI
{

    public static function toASCII($uri)
    {
        $uriParts = parse_url($uri);

        $scheme = $uriParts['scheme'];
        $user = array_key_exists('user', $uriParts) ? $uriParts['user'] : null;
        $pass = array_key_exists('pass', $uriParts) ? $uriParts['pass'] : null;
        $host = $uriParts['host'];
        $port = array_key_exists('port', $uriParts) ? $uriParts['port'] : null;
        $path = $uriParts['path'];
        $query = array_key_exists('query', $uriParts) ? $uriParts['query'] : null;
        $fragment = array_key_exists('fragment', $uriParts) ? $uriParts['fragment'] : null;

        $result = '';

        if (null !== $scheme) {
            $result .= $scheme . '://';
        }

        if (null !== $user) {
            $result .= $user;
        }

        if (null !== $pass) {
            $result .= ':' . $pass;
        }

        if (null !== $user) {
            $result .= '@';
        }

        if (null !== $host) {
            $result .= idn_to_ascii($host);
        }

        if (null !== $port) {
            $result .= ':' . $port;
        }

        if (null !== $path) {
            $result .= preg_replace_callback('/[^\x20-\x7f]/', function ($match) {
                return urlencode($match[0]);
            }, $path);
        }

        if (null !== $query) {
            $result .= '?' . preg_replace_callback('/[^\x20-\x7f]/', function ($match) {
                    return urlencode($match[0]);
                }, $query);
        }

        if (null !== $fragment) {
            $result .= '#' . $fragment;
        }

        return $result;
    }


}