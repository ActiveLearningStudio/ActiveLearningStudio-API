<?php
namespace CurrikiTsugi\Repositories;
use Tsugi\Core\LTIX;
use Tsugi\Util\U;
use Tsugi\Util\LTI13;
use Tsugi\UI\CrudForm;

class TenantRepository
{
    public function getRecord($key_key, $issuer_id, $deploy_key)
    {
        global $CFG;
        $PDOX = LTIX::getConnection();        
        $row = $PDOX->rowDie("SELECT * FROM {$CFG->dbprefix}lti_key WHERE key_key = :key_key and issuer_id = :issuer_id and deploy_key = :deploy_key", array('key_key' => $key_key, 'issuer_id' => $issuer_id, 'deploy_key' => $deploy_key));
        return $row;
    }

    public function create($data, $issure_row)
    {
        $tenant_new = null;
        $tenant = $this->getRecord($data['key_key'], $issure_row['issuer_id'], $data['deploy_key']);

        if(!$tenant){
            $_POST = $data;
            global $CFG;
            $tablename = "{$CFG->dbprefix}lti_key";
            $fields = array('key_key', 'key_sha256', 'secret', 'deploy_key', 'deploy_sha256', 'issuer_id',
                'caliper_url', 'caliper_key', 'created_at', 'updated_at', 'user_id');
            
            $retval = CrudForm::handleInsert($tablename, $fields);            
            $tenant_new = $this->getRecord($data['key_key'], $issure_row['issuer_id'], $data['deploy_key']);
        }

        return is_array($tenant_new) ? $tenant_new : null;
    }
}
