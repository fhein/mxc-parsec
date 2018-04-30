<?php

namespace Mxc\Parsec\Support;

class Uuid
{
    const OPTIONS_BRACES = 1;
    const OPTIONS_UPPERCASE = 2;
    const OPTIONS_NOHYPHENS = 4;

    protected static function applyOptions(string $uuid, int $options = null)
    {
        $options = $options ?? self::OPTIONS_BRACES | self::OPTIONS_UPPERCASE;
        if ($options & self::OPTIONS_BRACES) {
            $uuid = '{'.$uuid.'}';
        }
        if ($options & self::OPTIONS_UPPERCASE) {
            $uuid = strtoupper($uuid);
        }
        if ($options & self::OPTIONS_NOHYPHENS) {
            $uuid = str_replace('-', '', $uuid);
        }
        return $uuid;
    }

    public static function v3(string $namespace, string $name, int $options = null)
    {
        if (! self::isValid($namespace)) {
            return false;
        }

        // Get hexadecimal components of namespace
        $nhex = str_replace([ '-', '{', '}' ], '', $namespace);

        // Binary Value
        $nstr = '';

        // Convert Namespace Uuid to bits
        for ($i = 0; $i < strlen($nhex); $i += 2) {
            $nstr .= chr(hexdec($nhex[$i] . $nhex [$i + 1]));
        }

        // Calculate hash value
        $hash = md5($nstr . $name);

        return self::applyOptions(
            sprintf(
                '%08s-%04s-%04x-%04x-%12s',
                // 32 bits for "time_low"
                substr($hash, 0, 8),
                // 16 bits for "time_mid"
                substr($hash, 8, 4),
                // 16 bits for "time_hi_and_version",
                // four most significant bits holds version number 3
                (hexdec(substr($hash, 12, 4)) & 0x0fff) | 0x3000,
                // 16 bits, 8 bits for "clk_seq_hi_res",
                // 8 bits for "clk_seq_low",
                // two most significant bits holds zero and one for variant DCE1.1
                (hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000,
                // 48 bits for "node"
                substr($hash, 20, 12)
            ),
            $options
        );
    }

    public static function v4(int $options = null)
    {
        return self::applyOptions(
            sprintf(
                '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                // 32 bits for "time_low"
                mt_rand(0, 0xffff),
                mt_rand(0, 0xffff),
                // 16 bits for "time_mid"
                mt_rand(0, 0xffff),
                // 16 bits for "time_hi_and_version",
                // four most significant bits holds version number 4
                mt_rand(0, 0x0fff) | 0x4000,
                // 16 bits, 8 bits for "clk_seq_hi_res",
                // 8 bits for "clk_seq_low",
                // two most significant bits holds zero and one for variant DCE1.1
                mt_rand(0, 0x3fff) | 0x8000,
                // 48 bits for "node"
                mt_rand(0, 0xffff),
                mt_rand(0, 0xffff),
                mt_rand(0, 0xffff)
            ),
            $options
        );
    }

    public static function v5(string $namespace, string $name, int $options = null)
    {
        if (! self::isValid($namespace)) {
            return false;
        }

        // Get hexadecimal components of namespace
        $nhex = str_replace(['-', '{', '}'], '', $namespace);

        // Binary Value
        $nstr = '';

        // Convert Namespace Uuid to bits
        for ($i = 0; $i < strlen($nhex); $i += 2) {
            $nstr .= chr(hexdec($nhex [$i] . $nhex [$i + 1]));
        }

        // Calculate hash value
        $hash = sha1($nstr . $name);

        return self::applyOptions(
            sprintf(
                '%08s-%04s-%04x-%04x-%12s',
                // 32 bits for "time_low"
                substr($hash, 0, 8),
                // 16 bits for "time_mid"
                substr($hash, 8, 4),
                // 16 bits for "time_hi_and_version",
                // four most significant bits holds version number 5
                (hexdec(substr($hash, 12, 4)) & 0x0fff) | 0x5000,
                // 16 bits, 8 bits for "clk_seq_hi_res",
                // 8 bits for "clk_seq_low",
                // two most significant bits holds zero and one for variant DCE1.1
                (hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000,
                // 48 bits for "node"
                substr($hash, 20, 12)
            ),
            $options
        );
    }

    public static function isValid(string $uuid)
    {
        return preg_match(
            '/^\{?[0-9a-f]{8}\-?[0-9a-f]{4}\-?[0-9a-f]{4}\-?' . '[0-9a-f]{4}\-?[0-9a-f]{12}\}?$/i',
            $uuid
        ) === 1;
    }
}
