<?php
namespace CurrikiTsugi\Controllers;
use CurrikiTsugi\Interfaces\ControllerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use CurrikiTsugi\Repositories\IssuerRepository;
use CurrikiTsugi\Repositories\TenantRepository;

class RegisterPlatform implements ControllerInterface
{
    public function __construct(Request $request, Response $response) {
        $this->request = $request;
        $this->response = $response;
    }

    public function index()
    {
        
        $data = [];
        $data = [
            "issuer_key" => "http://moodlemac.local:8282",
            "issuer_client" => "q00IMucTe2uSuzc",
            "lti13_keyset_url" => "http://moodlemac.local:8282/mod/lti/certs.php",
            "lti13_token_url" => "http://moodlemac.local:8282/mod/lti/token.php",
            "lti13_token_audience" => "",
            "lti13_oidc_auth" => "http://moodlemac.local:8282/mod/lti/auth.php",
            "lti13_pubkey" => "",
            "lti13_privkey" => "",
            "key_key" => "chsbdduhR87LNdB",
            "secret" => "",
            "deploy_key" => "3",
            "issuer_id" => "",
            "caliper_url" => "",
            "caliper_key" => "",
            "user_id" => "",
            "doSave" => "Save"
        ];        
        
        if( isset($data['issuer_key']) && strlen($data['issuer_key']) > 0 ){
            $issureRespository = new IssuerRepository();
            $tenantRepository = new TenantRepository();
            $tenant = null;
            
            $issure = $issureRespository->create($data);
            if(is_null($issure)){
                $issure = $issureRespository->getByKey($data['issuer_key']);
            }

            if(!is_null($issure)){
                $data['issuer_id'] = $issure['issuer_id'];
                $tenant = $tenantRepository->create($data,$issure);
            }
            
            var_dump($issure);
            var_dump($tenant);
            /*
            if(!is_null($issure)){
                $data['issuer_id'] = $issure['issuer_id'];
                $tenant = $tenantRepository->create($data,$issure);                
            }else {
                $issure = $issureRespository->getByKey($data['issuer_key']);
                $data['issuer_id'] = $issure['issuer_id'];
                $tenant = $tenantRepository->create($data,$issure);
            }
            */
            //var_dump($tenant);
            echo "Platform Registered!";
           
        }else {
            //$this->resp
            $this->response->setContent('issur_key not defined');
            $this->response->setStatusCode($this->response::HTTP_NOT_FOUND);
            $this->response->send();
        }
        
    }    

    public function store()
    {
        echo 'me store....';
    }
}

