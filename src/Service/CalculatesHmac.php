<?php

namespace App\Service;

trait CalculatesHmac
{
    public function hmac($algorithm, $data, $password): string
    {
        /* md5 and sha1 only */
        $algorithm = strtolower($algorithm);
        $p = ['md5' => 'H32', 'sha1' => 'H40'];

        if (strlen($password) > 64) {
            $password = pack($p[$algorithm], $algorithm($password));
        }

        if (strlen($password) < 64) {
            $password = str_pad($password, 64, chr(0));
        }

        $ipad = substr($password, 0, 64) ^ str_repeat(chr(0x36), 64);
        $opad = substr($password, 0, 64) ^ str_repeat(chr(0x5C), 64);

        return ($algorithm($opad . pack($p[$algorithm], $algorithm($ipad . $data))));
    }
}