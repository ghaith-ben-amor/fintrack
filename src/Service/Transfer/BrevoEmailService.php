<?php

namespace App\Service\Transfer;

use Symfony\Contracts\HttpClient\HttpClientInterface;

final class BrevoEmailService
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private string $brevoApiKey,
        private string $senderEmail,
        private string $senderName,
    ) {
    }

    public function sendVerificationCode(string $recipientEmail, string $verificationCode): void
    {
        if ($this->brevoApiKey === '') {
            throw new \RuntimeException('Brevo API key is not configured.');
        }

        if ($recipientEmail === '' || !filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) {
            throw new \RuntimeException('Invalid recipient email address.');
        }

        $response = $this->httpClient->request('POST', 'https://api.brevo.com/v3/smtp/email', [
            'headers' => [
                'api-key' => $this->brevoApiKey,
                'accept' => 'application/json',
                'content-type' => 'application/json',
            ],
            'json' => [
                'sender' => [
                    'name' => $this->senderName,
                    'email' => $this->senderEmail,
                ],
                'to' => [
                    [
                        'email' => $recipientEmail,
                    ],
                ],
                'subject' => 'FinTrack - Code de verification',
                'textContent' => sprintf(
                    'Votre code de verification est: %s. Ce code expire dans 10 minutes.',
                    $verificationCode
                ),
                'htmlContent' => sprintf(
                    '<html><body><p>Votre code de verification est: <strong>%s</strong>.</p><p>Ce code expire dans 10 minutes.</p></body></html>',
                    $verificationCode
                ),
            ],
            'timeout' => 10,
        ]);

        if ($response->getStatusCode() >= 300) {
            throw new \RuntimeException('Brevo email request failed with status ' . $response->getStatusCode());
        }
    }

    private function encodePayload(array $payload): ?string
    {
        try {
            return json_encode($payload, JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            return null;
        }
    }
}
