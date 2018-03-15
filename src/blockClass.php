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

class block
{

	/**
	 * GET NEW BLOCK
	 *
	 * @param  string   $to    		Wallet address of receiver
	 * @param  string   $from  		Wallet address of sender
	 * @param  int      $amount     Amount that needs to be sended
	 * @param  string   $desc       Optional: description for the transaction
	 * @param  string   $tx         Transaction id
	 * @param  string   $preHash 	Hash of previous hash
	 */
	public function getNewBlock(string 	$to, 
								string 	$from, 
								int 	$amount, 
								string 	$desc = '', 
								string 	$tx = '', 
								string 	$preHash = ''): array
	{

		$time = time();

		return [
			'time'         => $time,
			'toAddress'    => $to,
			'fromAddress'  => $from,
			'amount'       => $amount,
			'desc'         => $desc,
			'trx'          => $tx,
			'previousHash' => $presHash,
			'hash'         => self::calcHash($time, $to, $from, $amount, $desc, $tx, $preHash),
		];
	}

	/**
	 * CHECK IF BLOCK IS VALID
	 *
	 * @param  string   $currentBlockHash 			Current block hash
	 * @param  string   $currentBlockpreviousHash 	Current block previous hash
	 * @param  int      time          				Time of current created block
	 * @param  string   $to    						Wallet address of receiver
	 * @param  string   $from  						Wallet address of sender
	 * @param  int      $amount       				Amount that needs to be sended
	 * @param  string   $desc         				Optional: description for the transaction
	 * @param  string   $tx          				Transaction id
	 * @param  string   $preHash 					Hash of previous hash
	 */
	public function checkBlock (string 	$currentBlockHash, 
								string 	$currentBlockpreviousHash, 
								int 	$time, 
								string 	$to, 
								string 	$from, 
								int 	$amount, 
								string 	$desc = '', 
								string 	$tx = '', 
								string 	$preHash = ''): bool
	{

		// Check if current block hash is different then the recalculated hash
		if ($currentBlockHash !== self::calcHash($time, $to, $from, $amount, $desc, $tx, $preHash)) return false;
		elseif ($currentBlockpreviousHash !== $preHash) return false;	// previous hash is different then the previous block hash?
		else return true;
	}

	/**
	 * CALCULATE THE BLOCK HASH
	 *
	 * @return string Return the block hash
	 */
	private static function calcHash(int 	$time, 
									 string $to, 
									 string $from, 
									 int 	$amount, 
									 string $desc = '', 
									 string $tx = '', 
									 string $preHash = ''): string
	{
		$blockHeader = (string) $time.'-'.$to.'-'.$from.'-'.$amount.'-'.$desc.'-'.$tx.'-'.$preHash;

		return hash('sha256', $blockHeader);
	}
}
