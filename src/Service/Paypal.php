<?php

namespace App\Service;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\ORM\EntityManagerInterface;

class Paypal implements PaypalInterface
{
    /**
     * @var HttpClientInterface
     */
    private $client;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(HttpClientInterface  $client, UrlGeneratorInterface $urlGenerator, EntityManagerInterface $entityManager) {
        $this->client = $client;
        $this->urlGenerator = $urlGenerator;
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $orderId
     * @param string $paypalApiUrl
     * @return void
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function capture(string $orderId, string $paypalApiUrl, string $paypaAuthorizationCode): void
    {
        if ($token = $this->getToken($paypalApiUrl, $paypaAuthorizationCode)) {
            $captureResponseJson = $this->client->request(
                "POST",
                $paypalApiUrl . "/v2/checkout/orders/" . $orderId .  "/capture",
                [
                    "headers" => [
                        "Content-Type" => "application/json",
                        "Authorization" => "Bearer " . $token,
                    ],
                ]
            );

            new ApiLogProvider(
                'Paypal',
                $paypalApiUrl . "/v2/checkout/orders/" . $orderId .  "/capture",
                ' Content-Type application/json' .
                ' Authorization Basic ' . $token,
                $captureResponseJson->getContent(),
                $captureResponseJson->getStatusCode(),
                $this->entityManager
            );
        }
    }

    /**
     * @param string $paypalApiUrl
     * @param string $paypaAuthorizationCode
     * @return mixed|string
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function getToken(string $paypalApiUrl, string $paypaAuthorizationCode)
    {
        $token = '';

        $tokenJsonResponse = $this->client->request(
            'POST',
            $paypalApiUrl . '/v1/oauth2/token?grant_type=client_credentials',
            [
                'headers' => [
                    'Authorization' => 'Basic ' . $paypaAuthorizationCode
                ]
            ]
        );

        $tokenResponse = json_decode($tokenJsonResponse->getContent(), true);

        if (!empty($tokenResponse['access_token'])) {
            $token = $tokenResponse['access_token'];
        }

        new ApiLogProvider(
            'Paypal',
            $paypalApiUrl . '/v1/oauth2/token?grant_type=client_credentials',
            'Authorization Basic ' . $paypaAuthorizationCode,
            $tokenJsonResponse->getContent(),
            $tokenJsonResponse->getStatusCode(),
            $this->entityManager
        );

        return $token;
    }

    /**
     * @param string $orderId
     * @param string $paypalUrl
     * @return string
     */
    public function getCheckoutNowUrl(string $orderId, string $paypalUrl): string
    {
        return  $paypalUrl . "/checkoutnow?token=" . $orderId;
    }

    /**
     * @param string $token
     * @param int $amount
     * @param string $paypalApiUrl
     * @return array|mixed
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function createOrder(string $token, int $amount, string $paypalApiUrl)
    {
        $result = [];
        $payPalRequestId = rand();
        $referenceId = rand();
        $orderResponseJson = $this->client->request(
            "POST",
             $paypalApiUrl . "/v2/checkout/orders",
            [
                "headers" => [
                    "Content-Type" => "application/json",
                    "Authorization" => "Bearer " . $token,
                    "PayPal-Request-Id" => $payPalRequestId,
                ],
                "json" =>
                    [
                        "intent" => "CAPTURE",
                        "purchase_units" => [[
                            "reference_id" => $referenceId,
                            "amount" => [
                                "value" => $amount,
                                "currency_code" => "USD"
                            ]
                        ]],
                        "payment_source" => [
                            "paypal" => [
                                "experience_context" => [
                                    "payment_method_preference" => "IMMEDIATE_PAYMENT_REQUIRED",
                                    "payment_method_selected" => "PAYPAL",
                                    "brand_name" => "EXAMPLE INC",
                                    "locale" => "en-US",
                                    "landing_page" => "LOGIN",
                                    "user_action" => "PAY_NOW",
                                    "cancel_url" => $this->urlGenerator->generate('paypal_error', [], 0),
                                    "return_url" => $this->urlGenerator->generate('paypal_success', [], 0)
                                ]
                            ]
                        ]
                    ]
            ]
        );

        if (!empty($orderResponseJson->getContent())) {
            $result = json_decode($orderResponseJson->getContent(), true);
        }

        new ApiLogProvider(
            'Paypal',
            $paypalApiUrl . "/v2/checkout/orders",
            ' Content-Type application/json' .
                    ' Authorization Bearer ' . $token .
                    ' PayPal-Request-Id ' . $payPalRequestId,
            $orderResponseJson->getContent(),
            $orderResponseJson->getStatusCode(),
            $this->entityManager
        );

        return $result;
    }
}