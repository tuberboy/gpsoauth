<?php
require 'RSA.php';
require 'BigInteger.php';
require 'Hash.php';
require 'Random.php';

	function EncryptedPasswd($gmail, $passwd)
	{
		define('GOOGLE_KEY_B64', 'AAAAgMom/1a/v0lblO2Ubrt60J2gcuXSljGFQXgcyZWveWLEwo6prwgi3iJIZdodyhKZQrNWp5nKJ3srRXcUW+F1BD3baEVGcmEgqaLZUNBjm057pKRI16kB0YppeGx5qIQ5QjKzsR8ETQbKLNWgRY0QRNVz34kMJR3P/LgHax/6rmf5AAAAAwEAAQ==');

    $google_key_bin = base64_decode(GOOGLE_KEY_B64);
    $modulus_len = unpack('Nl', $google_key_bin)['l'];
    $modulus_bin = substr($google_key_bin, 4, $modulus_len);
    $exponent_len = unpack('Nl', substr($google_key_bin, 4 + $modulus_len, 4))['l'];
    $exponent_bin = substr($google_key_bin, 4 + $modulus_len + 4, $exponent_len);
    $modulus = new BigInteger($modulus_bin, 256);
    $exponent = new BigInteger($exponent_bin, 256);

    $rsa = new RSA();
    $rsa->loadKey(['n' => $modulus, 'e' => $exponent], RSA::PUBLIC_FORMAT_RAW);
    $rsa->setEncryptionMode(RSA::ENCRYPTION_OAEP);
    $rsa->setHash('sha1');
    $rsa->setMGFHash('sha1');
    $encrypted = $rsa->encrypt("{$gmail}\x00{$passwd}");

    $hash = substr(sha1($google_key_bin, true), 0, 4);
    return strtr(base64_encode("\x00{$hash}{$encrypted}"), '+/', '-_');
  }