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

class blockchain
{
	public 		$chain         = [];
	protected 	$totalBlocks   = 0;
	protected 	$wallet;
	protected 	$block;
	protected 	$lastBlockHash = 'fistBlockHash';

	public function __construct()
	{
		$this->block  = new block();
		$this->wallet = new wallet();
	}


	/**
	 * Create first block (Genesis block)
	 * @return object Return this class
	 */
	public function Genesis(int $walletAmount = 100, array $walletInfo =[])
	{
		$walletEncryptInfo = $this->wallet->createWallet();

		$firstBlock = $this->newBlock($walletEncryptInfo['walletKey'], 'firstWallet', $walletAmount);
		
		return $walletEncryptInfo;
	}


	/**
	 * Create an new block
	 * @param  string         $to    	Wallet adress of receiver
	 * @param  string         $from  	Wallet address of sender
	 * @param  int            $amount   Amount that needs to be sended
	 * @param  string         $desc     Optional: description for the transaction
	 * @param  string         $tx       Optional: Transaction id
	 * @return object                   Return this class
	 */
	public function newBlock(string $to, string $from, int $amount, string $desc = '', string $tx = '')
	{

		// Check if we received an Transaction ID
		if (!$tx) {
			// Create one
			// @todo: create on with transaction Class
			$tx = rand(1, 1000);
		}

		// Get new block to chain
		$newBlock = $this->block->getNewBlock($to, $from, $amount, $desc, $tx, $this->lastBlockHash);

		$this->chain[]       = $newBlock;
		$this->lastBlockHash = $newBlock['hash'];
		$this->totalBlocks++;

		return $this;
	}


	/**
	 * Check if the block chain is valid and not modified
	 * @return boolean Return an boolean to check if the blockchain is valid
	 */
	public function verifyBlockChain()
	{

		$valid = false;

		for ($b = 1; $b < $this->totalBlocks; $b++) {
			$currentBlock  = $this->chain[$b];
			$previousBlock = $this->chain[$b - 1];

			// Calculate hash of current block
			if ($this->block->checkBlock($currentBlock['hash'],
										 $currentBlock['preHash'],
										 $currentBlock['time'],
										 $currentBlock['to'],
										 $currentBlock['from'],
										 $currentBlock['amount'],
										 $currentBlock['desc'],
										 $currentBlock['tx'],
										 $previousBlock['hash']))
			{
				$valid = true;
			} else break;
		}

		return $valid;
	}
}
