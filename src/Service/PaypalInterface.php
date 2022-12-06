<?php

namespace App\Service;

interface PaypalInterface
{
    /**
     * @param string $orderId
     * @param string $paypalApiUrl
     * @param string $token
     * @return void
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function capture(string $orderId, string $paypalApiUrl, string $token): array;

    /**
     * @param string $paypalApiUrl
     * @param string $paypaAuthorizationCode
     * @return mixed|string
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function getToken(string $paypalApiUrl, string $paypaAuthorizationCode);

    /**
     * @param string $orderId
     * @param string $paypalUrl
     * @return string
     */
    public function getCheckoutNowUrl(string $orderId, string $paypalUrl): string;

    /**
     * @param string $token
     * @param float $amount
     * @param string $paypalApiUrl
     * @return array|mixed
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function createOrder(string $token, float $amount, string $paypalApiUrl);
}