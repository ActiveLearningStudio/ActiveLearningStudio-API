<?php
namespace CurrikiTsugi\Controllers;
use CurrikiTsugi\Interfaces\ControllerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use CurrikiTsugi\Repositories\IssureRepository;

class RegisterPlatform implements ControllerInterface
{
    public function __construct(Request $request, Response $response) {
        $this->request = $request;
        $this->response = $response;
    }

    public function index()
    {
        $issureRespository = new IssureRepository();
        $data = [];
        $issureRespository->create($data);
        echo "me index....";
        var_dump($this->request->query->get('ctrl'));
    }    

    public function store()
    {
        echo 'me store....';
    }
}

