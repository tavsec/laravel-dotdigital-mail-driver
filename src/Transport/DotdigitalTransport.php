<?php

namespace Tavsec\LaravelDotdigitalMailDriver\Transport;


use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Arr;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\MessageConverter;

class DotdigitalTransport extends AbstractTransport
{

    private $client;
    private $region;
    private $username;
    private $password;

    public function __construct(ClientInterface $client, string $region, string $username, string $password)
    {
        $this->client = $client;
        $this->username = $username;
        $this->region = $region;
        $this->password = $password;
        parent::__construct();
    }

    /**
     * @throws GuzzleException
     */
    protected function doSend(SentMessage $message): void
    {
        $email = MessageConverter::toEmail($message->getOriginalMessage());

        $data = [
            "fromAddress" => (Arr::get(collect($email->getFrom())->toArray(), 0))->getAddress(),
            "subject" => $email->getSubject()
        ];

        if ($rawContent = $email->getTextBody())
            $data["plainTextContent"] = $rawContent;
        if ($htmlContent = $email->getHtmlBody())
            $data["htmlContent"] = $htmlContent;
        if ($attachments = $email->getAttachments()) {
            foreach ($attachments as $attachment) {
                $data["attachments"][] = [
                    "fileName" => $attachment->getPreparedHeaders()->getHeaderParameter('Content-Disposition', 'filename'),
                    "mimeType" => $attachment->getMediaType() . "/" . $attachment->getMediaSubtype(),
                    "content" => base64_encode($attachment->getBody())
                ];
            }
        }

        $data["toAddresses"] = collect($email->getTo())->map(fn($el) => $el->getAddress())->toArray();

        $payload = [
            'headers' => [
                'Authorization' => "Basic " . base64_encode($this->username . ":" . $this->password),
                'Content-Type' => 'application/json',
                "Accept" => "text/plain"
            ],
            'json' => $data,
        ];

        $req = $this->post($payload);
    }


    public function __toString(): string
    {
        return "dotdigital";
    }

    /**
     * @throws GuzzleException
     */
    private function post($payload)
    {
        return $this->client->request("POST", $this->getEndpoint(), $payload);
    }

    private function getEndpoint()
    {
        return "https://" . $this->region . "-api.dotdigital.com/v2/email";
    }
}
