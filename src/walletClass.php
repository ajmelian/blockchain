<?php

/**
 * BLOCKCHAIN BY PHP7
 *
 * An development of Blockchain in PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2017 - 2018, AJ Melián
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	Blockchain-PHP
 * @author	AJ Melián 
 * @copyright	(c) 2017 - 2018, AJ Melián (https://www.ajmelian.info)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @since	Version 0.7.0
 *
 */

namespace blockChain;

class wallet
{
	public $wallets         = [];
	private $cipher         = 'AES-256-CBC';
	private $privateKeySize = 16; // 128 bits
	private $walletKeySize  = 16; // 128 bits

	public function __construct(){}

	private function pkcs7Pad($data, $size): string
	{
		$length = $size - strlen($data) % $size;
		return $data . str_repeat(chr($length), $length);
	}

	private function generatePrivateKey(): string
	{
		$privateKey = openssl_random_pseudo_bytes($this->privateKeySize, $strong);
		if (!$strong) return $this->generatePrivateKey();
		else return $privateKey;
	}

	private function generateWalletKey(): string
	{
		$walletKey = openssl_random_pseudo_bytes($this->walletKeySize, $strong);
		if (!$strong) return $this->generateWalletKey();
		else return $walletKey;
	}

	private function encrypt(string $walletKey = '', string $privateKey = '', array $walletInfo = []): array
	{
		if (empty($walletKey)) $walletKey = $this->generatePrivateKey();
		if (empty($privateKey)) $privateKey = $this->generatePrivateKey();

		$walletEncryptedMessage = json_encode($walletInfo);

		$encryptedWallet = openssl_encrypt ($this->pkcs7Pad($walletEncryptedMessage, 256), 	// padded data
											$this->cipher, 									// cipher and mode
											$privateKey, 									// secret key
											0, 												// options (not used)
											$walletKey);									// initialisation vector
		

		$this->wallets[$walletKey] = $encryptedWallet;

		return ['walletKey'  => str_replace('=', '', base64_encode($walletKey)),
				'privateKey' => str_replace('=', '', base64_encode($privateKey)),];
	}

	private function pkcs7Unpad($data): string
	{
		return substr($data, 0, -ord($data[strlen($data) - 1]));
	}

	public function decrypt(string $walletKey = '', string $privateKey = ''): array
	{
	  $walletKey  = base64_decode($walletKey);
	  $privateKey = base64_decode($privateKey);
	  
		$walletEncryptedMessage = $this->wallets[$walletKey];

		$walletInfo = $this->pkcs7Unpad(openssl_decrypt($walletEncryptedMessage,
														$this->cipher,
														$privateKey,
														0,
														$walletKey));

		return json_decode($walletInfo, 1);
	}

	public function createWallet(array $walletInfo = []): array
	{
		$walletEncryptInfo = $this->encrypt('', '', $walletInfo);

		return $walletEncryptInfo;
	}
}
