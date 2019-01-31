<?php

namespace TrueLayer;

use DateTime;
use GuzzleHttp\Client;
use TrueLayer\Authorize\Token;

class Connection
{
    /**
     * TrueLayer settings
     * 
     * @const mixed
     */
    const AUTH_PATH = "https://auth.truelayer.com";
    const API_PATH = "https://api.truelayer.com";
    const DATA_PATH = "data";
    const API_VERSION = "v1";

    /**
     * Hold our API connection
     * 
     * @var Guzzle
     */
    protected $connection;

    /**
     * Hold our token and secret
     * 
     * @var string
     */
    protected $client_id;
    protected $client_secret;
    protected $redirect_uri;

    /** 
     * Set values and start a guzzle
     * 
     * @param $client_id string
     * @param $client_secret string
     * @return void
     */
    public function __construct($client_id, $client_secret, $request_uri)
    {
        $this->connection = new Client;
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->request_uri = $request_uri;
    }

    /**
     * Get client id
     * 
     * @return string
     */
    public function getClientId()
    {
        return $this->client_id;
    }

    /**
     * Get client id
     * 
     * @return string
     */
    public function getClientSecret()
    {
        return $this->client_secret;
    }

    /**
     * Get request uri
     * 
     * @return string
     */
    public function getRequestUri()
    {
        return $this->request_uri;
    }

    /**
     * Get token url
     * 
     * @return string
     */
    public function getTokenUrl()
    {
        return self::AUTH_PATH . "/connect/token";
    }

    /**
     * Get connection
     * 
     * @return string
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Get a data path
     * 
     * @return string
     */
    public function getUrl($path = "/")
    {
        return self::API_PATH . $path;
    }

    /**
     * Get a Bearer header
     * 
     * @param string $token
     * @return string
     */
    public function getBearer($token)
    {
        return [
            'Authorization' => "Bearer " . $token
        ];
    }

    /**
     * Generate a nonce
     * 
     * @return string
     */
    public function getNonce()
    {
        return hash(
            'sha256',
            (new DateTime())->format(DateTime::ATOM)
        );
    }

    /**
     * Get our state
     * 
     * @return string
     */
    public function getState()
    {
        $this->state = $this->state ? $this->state : uniqid("tl", true);
        return $this->state;
    }

    /**
     * Get our scope
     * 
     * @return string
     */
    public function getScope()
    {
        return implode("%20", [
            "info",
            "accounts",
            "balance",
            "cards",
            "transactions",
            "offline_access"
        ]);
    }


    /**
     * Build an Authorizartion Link
     * 
     * @return string
     */
    public function getAuthorizartionLink()
    {

        return self::AUTH_PATH . "/" .
            "?response_type=code" .
            "&client_id=" . $this->getClientId() .
            "&nonce=" . $this->getNonce() .
            "&scope=" . $this->getScope() .
            "&redirect_uri=" . $this->getRequestUri() .
            "&state=" . urlencode($this->getState()) .  
            "&enable_mock=true" .
            "&enable_oauth_providers=true" .
            "&enable_open_banking_providers=false" . 
            "&enable_credentials_sharing_providers=true" .
            "&response_mode=form_post";
    }

    /**
     * Get oauth token from code
     * 
     * @param code
     * @return Token
     */
    public function getOauthToken($code)
    {
        
        $result = $this->connection->request(
            'POST',
            $this->getTokenUrl(),
            [
                'form_params' => [
                    'grant_type' => 'authorization_code',
                    'client_id'  => $this->getClientId(),
                    'client_secret' => $this->getClientSecret(),
                    'redirect_uri' => $this->getRequestUri(),
                    'code' => $code
                ]
            ]
        );
        
        if((int)$result->getStatusCode() > 400) { 
            throw new InvalidCodeExchange;
        }

        $token = json_decode($result->getBody(), true);
        return new Token($token);
    }
}