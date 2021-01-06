<?php

namespace App\Message;

use DateTimeInterface;

final class EventMessage
{
    /**
     * @var string
     */
    private string $msg;

    /**
     * @var int
     */
    private int $accountId;

    /**
     * @var DateTimeInterface
     */
    private DateTimeInterface $createdAt;

    /**
     * EventMessage constructor.
     * @param int $accountId
     * @param string $msg
     * @param DateTimeInterface $createdAt
     */
    public function __construct(int $accountId, string $msg, DateTimeInterface $createdAt)
    {
        $this->msg = $msg;
        $this->accountId = $accountId;
        $this->createdAt = $createdAt;
    }

    /**
     * @return string
     */
    public function getMsg(): string
    {
        return $this->msg;
    }

    /**
     * @return int
     */
    public function getAccountId(): int
    {
        return $this->accountId;
    }

    /**
     * @return DateTimeInterface
     */
    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }
}
