<?php

namespace Tavsec\LaravelDotdigitalMailDriver\Transport;

use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;

class DotdigitalTransport extends AbstractTransport
{

    protected function doSend(SentMessage $message): void
    {
        // TODO: Implement doSend() method.
    }

    public function __toString(): string
    {
        // TODO: Implement __toString() method.
    }
}
