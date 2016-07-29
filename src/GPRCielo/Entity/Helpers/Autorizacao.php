<?php
/**
 * Created by PhpStorm.
 * User: guilherme
 * Date: 18/07/16
 * Time: 11:21
 */

namespace GPRCielo\Entity\Helpers;


class Autorizacao
{
    /**
     * @var int
     */
    const ONLY_AUTHENTICATE = 0;

    /**
     * @var int
     */
    const AUTHORIZE_IF_AUTHENTICATED = 1;

    /**
     * @var int
     */
    const AUTHORIZE = 2;

    /**
     * @var int
     */
    const AUTHORIZE_WITHOUT_AUTHENTICATION = 3;

    /**
     * @var int
     */
    const RECURRENCE = 4;

}