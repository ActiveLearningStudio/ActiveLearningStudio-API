<?php
namespace CurrikiTsugi\Controllers;
use CurrikiTsugi\Interfaces\ControllerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use CurrikiTsugi\Repositories\IssuerRepository;

class Issure implements ControllerInterface
{

    public function __construct(Request $request, Response $response) {
        $this->request = $request;
        $this->response = $response;
    }

    public function index() { }

    public function get_all()
    {
        $issureRespository = new IssuerRepository();
        $rows = $issureRespository->getAll();
        $this->response->setContent( json_encode($rows) );            
        $this->response->headers->set('Content-Type', 'application/json');
        $this->response->setStatusCode($this->response::HTTP_OK);
        $this->response->send();
    }
}
