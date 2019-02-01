<?php

namespace TrueLayer\Authorize;

use DateTime;
use DateTinterval;

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
    protected $issued_at;

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

        $this->refresh_token = isset($token['refresh_token']) ? 
            $token['refresh_token'] : 
            null;

        $this->issued_at = isset($token['issued_at']) ?  
            (new DateTime($token['issued_at']))->format(DateTime::ATOM) : 
            (new DateTime)->format(DateTime::ATOM);
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
     * Get refresh token
     * 
     * @return string
     */
    public function getRefreshToken()
    {
        return $this->refresh_token;
    }

    /**
     * Is our token expired
     * 
     * @return bool
     */
    public function isExpired()
    {
        $interval = new DateInterval("PT" + $this->expires_in);
        $expires = (clone $this->issued_at)
            ->add($interval);
        
        return $expires < (new DateTime);
    }

    /**
     * Are we refreshable?
     * 
     * @return bool
     */
    public function isRefreshable()
    {
        return (bool) $this->refresh_token;
    }
}
