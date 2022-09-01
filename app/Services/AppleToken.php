<?php

namespace App\Services;
use Exception;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Ecdsa\Sha256;
use Lcobucci\JWT\Signer\Key;
use DateTimeImmutable;

/**
 * Class AppleToken
 * 
 * @package App\login\AppleToken
 */
class AppleToken
{
    /**
     * Method used to generate secret key for apple login
     */
    function generateClientSecret(){
        try{
            $applePrivateKeyFilePath = storage_path(config('constants.applePrivateKeyFilePath'));
            if (file_exists($applePrivateKeyFilePath)) {
                $clientSecretPrepared = 'file://' . $applePrivateKeyFilePath; 
            }
           
            $signer = new Sha256();
            $privateKey = new Key($clientSecretPrepared);

            $now = new DateTimeImmutable();

            $token = (new Builder())
                 ->issuedBy(config('services.apple.team_id'))
                ->withHeader('kid', config('services.apple.key_id'))
                ->withHeader('type', 'JWT')
                ->withHeader('alg', 'ES256')
                ->issuedAt($now)
                ->expiresAt($now->modify('+31 days'))
                ->permittedFor('https://appleid.apple.com')
                ->relatedTo(config('services.apple.client_id'))
                ->getToken($signer, $privateKey); // Retrieves the generated token
    
            if(!empty($token)){
                $this->putPermanentEnv('APPLE_CLIENT_SECRET', (string) $token);
            }

        }catch(Exception $ex){
            throw $ex; 
        }
        
    }

    /**
     * Method Put Permanent
     * @param string $key
     * @param string $value
     */
    public function putPermanentEnv($key, $value)
    {
        $path = app()->environmentFilePath();
        file_put_contents(
            $path,
            preg_replace(
                "/^{$key}=.*/m",
                "{$key}={$value}",
                file_get_contents($path)
            )
        );
    }

}
