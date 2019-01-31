<?php

namespace TrueLayer\Authorize;

class Token
{
    /**
     * Hold the parts of our token
     * 
     * @var string
     */
    protected $access_token;
    protected $expires_in;
    protected $token_type;
    protected $refresh_token;

    /**
     * Build our token
     * 
     * @param $json_string
     * @return void
     */
    public function __construct(array $token)
    {
        $this->access_token = $token['access_token'];
        $this->expires_in = $token['expires_in'];
        $this->token_type = $token['token_type'];
        $this->refresh_token = $token['refresh_token'];
    }

    /**
     * Get access token
     * 
     * @return string
     */
    public function getAccessToken()
    {
        return $this->access_token;
    }

    /**
     * Is our token expired
     * 
     * @return bool
     */
    public function isExpired()
    {
        return false;
    }

    /**
     * Refresh our token
     * 
     * @return true
     */
    public function refresh()
    {
        return true;
    }
}
