<?php
/**
 * Created by
 * User: Natalia Y. Chilikina (chilikina.natalya@gmail.com)
 * Date: 28/08/2017
 * Time: 17:04
 */

class Account
{
    const LENGTH_MIN = 2;
    const LENGTH_MAX = 10;

    /**
     * Validation the length of the account
     *
     * @param string|null $account
     *
     * @return bool
     */
    public static function validAccount($account): bool
    {
        $size = strlen($account);

        return !(($account === null) || ($size > self::LENGTH_MAX) || ($size < self::LENGTH_MIN));
    }
}
