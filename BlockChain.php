<?php
/**
 * Created by
 * User: Natalia Y. Chilikina (chilikina.natalya@gmail.com)
 * Date: 23/08/2017
 * Time: 08:00
 */

class BlockChain
{
    /** @var  array */
    private $blockTree = [];

    /**
     * BlockChain constructor.
     */
    public function __construct()
    {

    }

    /**
     * @return array
     */
    public function getBlockChain(): array
    {
        return $this->blockTree;
    }

    /**
     * Validation the block of the chain
     *
     * @param Block $block
     *
     * @return bool
     */
    public function validateBlock(Block $block): bool
    {
        return !(array_key_exists($block->getId(), $this->blockTree) || count($block->getTransactions()) === 0);
    }

    /**
     * Adding new block to block chain
     *
     * @param $parentBlockId
     * @param Block $block
     */
    public function addBlock($parentBlockId, Block $block)
    {
        if ($this->validateBlock($block)) {
            if ($parentBlockId === null) {
                $check = true;
                foreach ($this->blockTree as $blockTreeItem) {
                    if (isset($blockTreeItem['block'])) {
                        $check = false;
                    }
                }
                if ($check) {
                    $this->blockTree[$block->getId()]['id'] = $block->getId();
                    $this->blockTree[$block->getId()]['block'] = $block;
                    $this->blockTree[$block->getId()]['parent'] = null;
                }
            } else {
                $blockParent = $this->findBlock($parentBlockId);
                if ($blockParent) {
                    $this->blockTree[$block->getId()]['id'] = $block->getId();
                    $this->blockTree[$block->getId()]['block'] = $block;
                    $this->blockTree[$block->getId()]['parent'] = $parentBlockId;
                }
            }
        }
    }

    /**
     * Finding existing block in the chain
     *
     * @param int $id
     *
     * @return bool
     */
    public function findBlock(int $id): bool
    {
        foreach ($this->blockTree as $keyItem => $blockTreeItem) {
            if ($keyItem === $id) {
                return true;
            }
        }

        return false;
    }

    /**
     * Getting balance of the account
     *
     * @param string|null $account
     *
     * @return int
     * @throws \RuntimeException
     */
    public function getBalance($account): int
    {
        $balance = 0;
        if(Account::validAccount($account)) {
            foreach ($this->blockTree as $blockItem) {
                /** @var Block $block */
                $block = $blockItem['block'];
                $transactions = $block->getTransactions();
                /** @var Transaction $transaction */
                foreach ($transactions as $transaction) {
                    if ($account === $transaction->getTo()) {
                        $balance += $transaction->getAmount();
                    }
                    if ($account === $transaction->getFrom()) {
                        $balance -= $transaction->getAmount();
                    }
                }
            }
        }
        else {
            throw new RuntimeException(
                'Error reading field ACCOUNT'
            );
        }

        return $balance;
    }
}
