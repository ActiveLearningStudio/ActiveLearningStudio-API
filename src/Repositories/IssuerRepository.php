<?php
namespace CurrikiTsugi\Repositories;
use Tsugi\Core\LTIX;
use Tsugi\Util\U;
use Tsugi\Util\LTI13;
use Tsugi\UI\CrudForm;

class IssuerRepository
{

    public function getAll()
    {
        global $CFG;
        $PDOX = LTIX::getConnection();        
        $sql = "SELECT * FROM {$CFG->dbprefix}lti_issuer";
        $rows = $PDOX->allRowsDie($sql);
        return $rows;
    }

    public function getByKey($key)
    {
        global $CFG;
        $PDOX = LTIX::getConnection();        
        $row = $PDOX->rowDie("SELECT issuer_id, issuer_key, issuer_client, issuer_sha256, lti13_keyset_url, lti13_token_url, lti13_token_audience, lti13_oidc_auth, lti13_platform_pubkey, lti13_pubkey, lti13_privkey, created_at, updated_at, issuer_guid FROM {$CFG->dbprefix}lti_issuer WHERE issuer_key = :KEY", array('KEY' => $key));
        return $row;
    }

    public function getByKeyAndClient($key, $client_id)
    {
        global $CFG;
        $PDOX = LTIX::getConnection();        
        $row = $PDOX->rowDie("SELECT issuer_id, issuer_key, issuer_client, issuer_sha256, lti13_keyset_url, lti13_token_url, lti13_token_audience, lti13_oidc_auth, lti13_platform_pubkey, lti13_pubkey, lti13_privkey, created_at, updated_at, issuer_guid FROM {$CFG->dbprefix}lti_issuer 
                                WHERE issuer_key = :KEY AND issuer_client = :issuer_client", 
                                array('KEY' => $key, 'issuer_client' => $client_id));
        return $row;
    }

    public function create($data)
    {
        $issure_new = null;
        $issure = $this->getByKeyAndClient($data['issuer_key'], $data['issuer_client']);
        if(!$issure){
            $_POST = $data;
            if ( U::get($_POST,'issuer_key') ) {
                // $_POST['issuer_sha256'] = LTI13::extract_issuer_key_string(U::get($_POST,'issuer_key'));
                if ( strlen(U::get($_POST,'lti13_pubkey')) < 1 && strlen(U::get($_POST,'lti13_privkey')) < 1 ) {
                    LTI13::generatePKCS8Pair($publicKey, $privateKey);
                    $_POST['lti13_pubkey'] = $publicKey;
                    $_POST['lti13_privkey'] = $privateKey;
                }

                global $CFG;
                $tablename = "{$CFG->dbprefix}lti_issuer";
                $fields = array("issuer_key", "issuer_client", "issuer_guid", "issuer_sha256",
                    "lti13_keyset_url", "lti13_token_url", "lti13_token_audience", "lti13_oidc_auth",
                    "lti13_pubkey", "lti13_privkey",
                    "created_at", "updated_at");                
                $retval = CrudForm::handleInsert($tablename, $fields);    
                $issure_new = $this->getByKeyAndClient($data['issuer_key'], $data['issuer_client']);            
            }
        }    
        
        return is_array($issure_new) ? $issure_new : null;
    }

}
