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

include ('src/autoload.php');
autoLoader::register();

$blockchain = new blockChain\blockchain();

$timeini = microtime(true);

echo 'Creamos Bloque Génesis'.PHP_EOL;
$genesisWallet = $blockchain->Genesis(20000000,
											   ['email' => 'hola@ajmelian.info',]);
print_r($genesisWallet);

//execution time of the script
echo 'Duración de Ejecución: ' . ((microtime(true) - $timeini)) . ' Mins';

$timeini = microtime(true);

echo 'Inicio de Test Blockchain' . PHP_EOL;
for ($i = 0; $i < 1000000; $i++) {
    $blockchain->newBlock ( rand(1, 2), 
    						rand(2, 1), 
    						rand(10, 1000), 
    						'Transaccion: '.$i);
}

//execution time of the script
echo 'Duración de Ejecución: '.((microtime(true) - $timeini)).' Mins';
echo 'Test Blockchain finalizado'.PHP_EOL;
print_r($blockchain->chain);

var_dump($blockchain->verifyBlockChain());

echo 'Blockchain Verificado'.PHP_EOL;