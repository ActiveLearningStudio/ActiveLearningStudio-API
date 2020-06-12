<?php
namespace CurrikiTsugi\Repositories;
use Tsugi\Core\LTIX;

class IssureRepository
{
    public function getByKey($key)
    {
        $PDOX = LTIX::getConnection();        
        $row = $PDOX->rowDie("SELECT issuer_id, issuer_key, issuer_client, issuer_sha256, lti13_keyset_url, lti13_token_url, lti13_token_audience, lti13_oidc_auth, lti13_platform_pubkey, lti13_pubkey, lti13_privkey, created_at, updated_at FROM lti_issuer WHERE issuer_key = :KEY", array('KEY' => $key));
        return $row;
    }

    public function create($data)
    {
        $issure = $this->getByKey("http://moodlemac.local:82820");
        if(!$issure){
            die('do create');
        }        
    }
}
