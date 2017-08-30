<?php
/**
 * Created by
 * User: Natalia Y. Chilikina (chilikina.natalya@gmail.com)
 * Date: 23/08/2017
 * Time: 07:58
 */

class Transaction
{
    /** @var int */
    private $id;
    /** @var int */
    private $type;
    /** @var  string */
    private $from;
    /** @var  string */
    private $to;
    /** @var int */
    private $amount;
    /** @var  string */
    private $signature;

    const TYPE_EMISSION = 0;
    const TYPE_TRANSFER = 1;

    const MD5_LENGTH = 32;

    /**
     * Transaction constructor.
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
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $type
     *
     * @throws \Exception
     */
    public function setType(int $type)
    {
        switch ($type) {
            case self::TYPE_EMISSION:
                $this->type = $type;
                $this->setFrom(null);
                break;
            case self::TYPE_TRANSFER:
                $this->type = $type;
                break;
            default:
                throw new RuntimeException(
                    'Error reading field TYPE'
                );
        }
    }

    /**
     * @return string|null
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param string|null $from
     *
     * @throws \RuntimeException
     */
    public function setFrom($from)
    {
        if ($this->getType() === self::TYPE_EMISSION) {
            $this->from = null;
        } else {
            if (!Account::validAccount($from)) {
                throw new RuntimeException(
                    'Error reading field FROM'
                );
            }
            $this->from = $from;
        }
    }

    /**
     * @return string
     */
    public function getTo(): string
    {
        return $this->to;
    }

    /**
     * @param string $to
     *
     * @throws \RuntimeException
     */
    public function setTo(string $to)
    {
        if (!Account::validAccount($to)) {
            throw new RuntimeException(
                'Error reading field TO'
            );
        }
        if ($to === $this->getFrom()) {
            throw new RuntimeException(
                'Field TO is equal to field FROM'
            );
        }
        $this->to = $to;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     *
     * @throws \RuntimeException
     */
    public function setAmount(int $amount)
    {
        if ($amount < 0) {
            throw new RuntimeException(
                'Error reading field AMOUNT'
            );
        }
        $this->amount = $amount;
    }

    /**
     * @return string
     */
    public function getSignature(): string
    {
        return $this->signature;
    }

    /**
     * @param string $signature
     *
     * @throws \RuntimeException
     */
    public function setSignature(string $signature)
    {
        if (strlen($signature) !== self::MD5_LENGTH) {
            throw new RuntimeException(
                'Error reading field SIGNATURE'
            );
        }
        $this->signature = $signature;
    }

    /**
     * Checking signature of the transaction
     *
     * @return bool
     */
    public function checkSignature(): bool
    {
        $sign = md5(
            implode(':', [
                $this->getId(),
                $this->getType(),
                $this->getFrom(),
                $this->getTo(),
                $this->getAmount()
            ])
        );

        return !($sign !== $this->getSignature()) ? true : false;
    }
}
