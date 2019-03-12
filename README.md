# TrueLayer SDK

__Start a token request__
```

$tl = new TrueLayer\Connection(
    $client_id,
    $client_secret,
    $redirect_uri
);

header('Location: ' . $tl->getAuthorizartionLink());
```

__Exchange the token and get account info__
```
$token = $tl->getOauthToken($code);

$accounts = (new TrueLayer\Bank\Accounts($tl, $token))
            ->getAllAccounts();

var_dump($accounts);
```

## Contributors

Kevin Diem - [kgdiem](https://github.com/kgdiem)