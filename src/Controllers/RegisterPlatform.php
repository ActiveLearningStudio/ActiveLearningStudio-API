<?php
namespace CurrikiTsugi\Controllers;
use CurrikiTsugi\Interfaces\ControllerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use CurrikiTsugi\Repositories\IssuerRepository;
use CurrikiTsugi\Repositories\TenantRepository;
use Tsugi\Core\LTIX;

class RegisterPlatform implements ControllerInterface
{
    public function __construct(Request $request, Response $response) {
        $this->request = $request;
        $this->response = $response;
    }

    public function index()
    {
        
        $is_valid_input_request = $this->request->request->get('issuer_key')
            && $this->request->request->get('issuer_client')
            && $this->request->request->get('lti13_keyset_url')
            && $this->request->request->get('lti13_token_url')
            && $this->request->request->get('lti13_oidc_auth')
            && $this->request->request->get('deploy_key');
        
        if ($is_valid_input_request) {
            
            $data = [
                "issuer_key" => $this->request->request->get('issuer_key'),
                "issuer_client" => $this->request->request->get('issuer_client'),
                "issuer_guid" => createGUID(),
                "lti13_keyset_url" => $this->request->request->get('lti13_keyset_url'),
                "lti13_token_url" => $this->request->request->get('lti13_token_url'),
                "lti13_token_audience" => "",
                "lti13_oidc_auth" => $this->request->request->get('lti13_oidc_auth'),
                "lti13_pubkey" => "",
                "lti13_privkey" => "",
                "key_key" => $this->request->request->get('issuer_client'),
                "secret" => "",
                "deploy_key" => $this->request->request->get('deploy_key'),
                "issuer_id" => "",
                "caliper_url" => "",
                "caliper_key" => "",
                "user_id" => "",
                "doSave" => "Save",
            ];

            $issureRespository = new IssuerRepository();
            $tenantRepository = new TenantRepository();
            $tenant = null;
            
            $issure = $issureRespository->create($data);
            if(is_null($issure)){
                $issure = $issureRespository->getByKeyAndClient($data['issuer_key'], $data['issuer_client']);
            }

            if(!is_null($issure)){
                $data['issuer_id'] = $issure['issuer_id'];
                $tenant = $tenantRepository->create($data,$issure);
            }
            
            $this->response->setContent( json_encode(['issure' => $issure, 'tenant' => $tenant]) );            
            $this->response->headers->set('Content-Type', 'application/json');
            $this->response->setStatusCode($this->response::HTTP_OK);
            $this->response->send();
        } else {
            $this->response->setContent('Invalid request parameters');
            $this->response->setStatusCode($this->response::HTTP_NOT_FOUND);
            $this->response->send();
        }
        
    }    

}

