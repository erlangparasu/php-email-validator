<?php

/**
 * Created by Erlang Parasu 2021 erlangparasu.
 */

function _is_email_valid($email)
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    try {
        $host = explode('@', $email)[1] ?? '-';
        $ip = getHostByName($host);

        if ($ip === $host) {
            return false;
        }

        if (filter_var($host, FILTER_VALIDATE_IP)) {
            return false;
        }

        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            return false;
        }

        // https://github.com/egulias/EmailValidator/blob/c81f18a3efb941d8c4d2e025f6183b5c6d697307/src/Validation/DNSCheckValidation.php#L58
        // Reserved Top Level DNS Names (https://tools.ietf.org/html/rfc2606#section-2),
        // mDNS and private DNS Namespaces (https://tools.ietf.org/html/rfc6762#appendix-G)
        $reservedTopLevelDnsNames = [
            // Reserved Top Level DNS Names
            'test',
            'example',
            'invalid',
            'localhost',

            // mDNS
            'local',

            // Private DNS Namespaces
            'intranet',
            'internal',
            'private',
            'corp',
            'home',
            'lan',
        ];

        foreach ($reservedTopLevelDnsNames as $reserved) {
            if (preg_match('/' . $reserved . '$/', $host)) {
                return false;
            }
        }

        if (in_array($host, $reservedTopLevelDnsNames)) {
            return false;
        }

        $dns = checkdnsrr($host, 'MX');
        if (!$dns) {
            return false;
        }

        if (!checkdnsrr($host, 'A')) {
            return false;
        }

        // https://www.php.net/manual/en/function.getmxrr.php#42396
        exec('dig +short MX ' . escapeshellarg($host), $ips);
        if ($ips[0] == '') {
            // print "MX record found for $host not found!\n";
            return false;
        }

        $mail_servers = [];
        exec('nslookup -type=mx ' . escapeshellarg($host), $o);
        foreach ($o as $line) {
            if (strpos($line, 'mail exchanger') !== false) {
                $right = trim(explode('=', $line)[1]);
                $mx_host = explode(' ', $right)[1];
                $mx_ip = getHostByName($mx_host);
                if ($mx_ip != $mx_host) {
                    if (filter_var($mx_ip, FILTER_VALIDATE_IP)) {
                        if (checkdnsrr($mx_host, 'A')) {
                            $mail_servers[] = $mx_host;
                        }
                    }
                }
            }
        }

        if (count($mail_servers) >= 1) {
            return true;
        }

        // var_dump($mail_servers);
        // var_dump([$email, $host, $ip]);
    } catch (\Throwable $th) {
        // throw $th;
    }

    return false;
}
