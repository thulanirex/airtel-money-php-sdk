<?php

namespace Thulani\AirtelMoneyPhpSdk;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;

class AirtelService
{
    public string $clientId;
    public string $clientSecret;
    protected Client $httpClient;
    protected string $accessToken;
    protected string $country = 'KE';
    protected string $currency = 'KES';
    protected string $encryptedPin = '';
    protected string $publicKey = 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCkq3XbDI1s8Lu7SpUBP+bqOs/MC6PKWz
    6n/0UkqTiOZqKqaoZClI3BUDTrSIJsrN1Qx7ivBzsaAYfsB0CygSSWay4iyUcnMVEDrNVO
    JwtWvHxpyWJC5RfKBrweW9b8klFa/CfKRtkK730apy0Kxjg+7fF0tB4O3Ic9Gxuv4pFkbQ
    IDAQAB';

    /**
     * @param array $config Config options
     */
    public function __construct(array $config = [])
    {
        $this->clientId = $config['client_id'];
        $this->clientSecret = $config['client_secret'];
        $this->publicKey = $config['public_key'];
        $this->country = $config['country'];
        $this->currency = $config['currency'];
        $this->httpClient = new Client([
            'base_uri' => $config['env'] === 'staging'
                ? 'https://openapiuat.airtel.africa/'
                : 'https://openapi.airtel.africa/',
        ]);
    }

    /**
     * Generate or refresh the access token
     *
     * @param string|null $token
     * @param callable|null $callback
     *
     * @return $this
     */
    public function authenticate(string $token = null, callable $callback = null): self
    {
        if (is_null($token)) {
            try {
                $response = $this->httpClient->request('POST', '/auth/oauth2/token', [
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ],
                    'json' => [
                        'client_id' => $this->clientId,
                        'client_secret' => $this->clientSecret,
                        'grant_type' => 'client_credentials',
                    ],
                ]);

                $responseData = json_decode($response->getBody()->getContents(), true);
                $this->accessToken = $responseData['access_token'];
            } catch (BadResponseException $e) {
                throw $e;
            } catch (Exception $e) {
                throw $e;
            }
        } else {
            $this->accessToken = $token;
        }

        if ($callback !== null) {
            $callback($this->accessToken);
        }

        return $this;
    }

    public function encryptData(string $data): string
    {
        $publicKeyResource = openssl_pkey_get_public([$this->publicKey, '']);
        if (!$publicKeyResource) {
            throw new Exception('Public key NOT Correct');
        }
        if (!openssl_public_encrypt($data, $encrypted, $publicKeyResource)) {
            throw new Exception('Error encrypting with public key');
        }

        return base64_encode($encrypted);
    }

    public function setPin($data)
{
    $this->pin = base64_encode(openssl_public_encrypt($data, $encrypted, openssl_pkey_get_public(array($this->public_key, ''))) ? $encrypted : '');

    return $this;
}

public function userEnquiry($phone)
{
    try {
        $response = $this->client->request(
            'GET',
            "/standard/v1/users/{$phone}",
            array(
                'headers' => array(
                    'Content-Type'  => 'application/json',
                    'X-Country'     => $this->country,
                    'X-Currency'    => $this->currency,
                    'Authorization' => 'Bearer ' . $this->token,
                ),
                'json'    => array(),
            )
        );

        return json_decode($response->getBody()->getContents(), true);
    } catch (BadResponseException | Exception $e) {
        throw $e;
    }
}
}