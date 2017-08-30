<?php
/**
 * Created by
 * User: Natalia Y. Chilikina (chilikina.natalya@gmail.com)
 * Date: 23/08/2017
 * Time: 09:13
 */

include __DIR__ . '/Transaction.php';
include __DIR__ . '/Block.php';
include __DIR__ . '/BlockChain.php';
include __DIR__ . '/Account.php';

test();

function test()
{

    $trx = new Transaction();
    $trx->setId(1);
    $trx->setType(Transaction::TYPE_EMISSION);
    $trx->setTo('bob');
    $trx->setAmount(100);
    $sign = md5(
        implode(':', [
            $trx->getId(),
            $trx->getType(),
            $trx->getFrom(),
            $trx->getTo(),
            $trx->getAmount()
        ])
    );
    $trx->setSignature($sign);

    $block = new Block();
    $block->setId(1);
    $block->addTransactions($trx);

    $blockChain = new BlockChain();
    $blockChain->addBlock(null, $block);
    var_dump($blockChain->getBalance('alice'));
    var_dump($blockChain->getBalance('bob'));

    $trx = new Transaction();
    $trx->setId(2);
    $trx->setType(Transaction::TYPE_TRANSFER);
    $trx->setFrom('bob');
    $trx->setTo('alice');
    $trx->setAmount(50);
    $sign = md5(
        implode(':', [
            $trx->getId(),
            $trx->getType(),
            $trx->getFrom(),
            $trx->getTo(),
            $trx->getAmount()
        ])
    );
    $trx->setSignature($sign);

    $block = new Block();
    $block->setId(2);
    $block->addTransactions($trx);

    $blockChain->addBlock(1, $block);
    var_dump($blockChain->getBalance('alice'));
    var_dump($blockChain->getBalance('bob'));

    $trx = new Transaction();
    $trx->setId(3);
    $trx->setType(Transaction::TYPE_TRANSFER);
    $trx->setFrom('bob');
    $trx->setTo('alice');
    $trx->setAmount(10);
    $sign = md5(
        implode(':', [
            $trx->getId(),
            $trx->getType(),
            $trx->getFrom(),
            $trx->getTo(),
            $trx->getAmount()
        ])
    );
    $trx->setSignature($sign);

    $block = new Block();
    $block->setId(3);
    $block->addTransactions($trx);
    $blockChain->addBlock(2, $block);
    var_dump($blockChain->getBalance('alice'));
    var_dump($blockChain->getBalance('bob'));

    print_r($blockChain->getBlockChain());
}
