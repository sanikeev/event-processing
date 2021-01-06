<?php

namespace App\Message;

use DateTimeInterface;

final class EventMessage
{

    private $msg;

    private $accountId;

    private $createdAt;

    public function __construct(int $accountId, string $msg, DateTimeInterface $createdAt)
    {
        $this->msg = $msg;
        $this->accountId = $accountId;
        $this->createdAt = $createdAt;
    }

    public function getMsg(): string
    {
        return $this->msg;
    }

    public function getAccountId(): int
    {
        return $this->accountId;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }
}
