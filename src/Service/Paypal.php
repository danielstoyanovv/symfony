<?php

namespace App\Service;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Paypal implements PaypalInterface
{
    /**
     * @var HttpClientInterface
     */
    private $client;

    /**
     * @param UrlGeneratorInterface
     */
    private $urlGenerator;

    public function __construct(HttpClientInterface  $client, UrlGeneratorInterface $urlGenerator) {
        $this->client = $client;
        $this->urlGenerator = $urlGenerator;
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
        $captureResponseJson = $this->client->request(
            "POST",
            $paypalApiUrl . "/v2/checkout/orders/" . $orderId .  "/capture",
            [
                "headers" => [
                    "Content-Type" => "application/json",
                    "Authorization" => "Bearer " . $this->getToken($paypalApiUrl, $paypaAuthorizationCode),
                ],
            ]
        );
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

        return $result;
    }
}