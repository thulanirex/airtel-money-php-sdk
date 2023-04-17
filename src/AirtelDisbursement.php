<?php

namespace Thulani\AirtelMoneyPhpSdk;

use Exception;
use GuzzleHttp\Exception\BadResponseException;

class AirtelDisbursement extends AirtelService
{
    public function __construct(array $config)
    {
        parent::__construct($config);
    }

    public function initiateDisbursement($phoneNumber, $amount, $transactionRef, $transactionId = null, $currency = null, $country = null, $callback = null)
    {
        try {
            $response = $this->client->request('POST', '/standard/v1/disbursements/', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => '*/*',
                    'X-Country' => $country ?? $this->country,
                    'X-Currency' => $currency ?? $this->currency,
                    'Authorization' => 'Bearer ' . $this->token,
                ],
                'json' => [
                    'payee' => [
                        'msisdn' => $phoneNumber,
                    ],
                    'reference' => $transactionRef,
                    'pin' => $this->pin,
                    'transaction' => [
                        'amount' => $amount,
                        'id' => $transactionId ?? random_bytes(8),
                    ],
                ],
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            return $callback ? $callback($result) : $result;
        } catch (BadResponseException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function requestDisbursementRefund($transactionId, $callback = null)
    {
        try {
            $response = $this->client->request('POST', '/standard/v1/disbursements/refund', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => '*/*',
                    'X-Country' => $this->country,
                    'X-Currency' => $this->currency,
                    'Authorization' => 'Bearer ' . $this->token,
                ],
                'json' => [],
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            return $callback ? $callback($result) : $result;
        } catch (BadResponseException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function queryDisbursementStatus($transactionId, $callback = null)
    {
        try {
            $response = $this->client->request('GET', "/standard/v1/disbursements/{$transactionId}", [
                'headers' => [
                    'Accept' => '*/*',
                    'X-Country' => $this->country,
                    'X-Currency' => $this->currency,
                    'Authorization' => 'Bearer ' . $this->token,
                ],
                'json' => [],
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            return $callback ? $callback($result) : $result;
        } catch (BadResponseException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
