<?php
/**
 * Created by
 * User: Natalia Y. Chilikina (chilikina.natalya@gmail.com)
 * Date: 23/08/2017
 * Time: 07:59
 */

class Block
{
    /** @var int */
    private $id;
    /** @var  array */
    private $transactions = [];

    const MAX_COUNT = 10;

    /**
     * Block constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return array
     */
    public function getTransactions(): array
    {
        return $this->transactions;
    }

    /**
     * Validation the transaction
     *
     * @param Transaction $transaction
     *
     * @return bool
     */
    public function validateTransaction(Transaction $transaction): bool
    {
        return $transaction->checkSignature();
    }

    /**
     * Adding new transaction
     *
     * @param Transaction $transaction
     */
    public function addTransactions(Transaction $transaction)
    {
        if (
            $this->validateTransaction($transaction) &&
            count($this->transactions) < self::MAX_COUNT &&
            !array_key_exists($transaction->getId(), $this->transactions)
        ) {
            $this->transactions[$transaction->getId()] = $transaction;
        }
    }

}
