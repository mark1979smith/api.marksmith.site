<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="api")
     */
    public function indexAction(Request $request)
    {
        $returnedData = [];
        switch ($request->getRealMethod()) {
            case $request::METHOD_GET: // Used for basic read requests to the server

                break;

            case $request::METHOD_PUT: // Used to modify an existing object on the server

                break;

            case $request::METHOD_POST: // Used to create a new object on the server

                break;

            case $request::METHOD_DELETE: // Used to remove an object on the server

                break;

            default:
                $returnedData['message'] = 'Invalid Request Method detected. Only GET, PUT, POST or DELETE are accepted';
        }

        $response = new Response(
            '',
            Response::HTTP_OK
        );
        $response->headers->set('Content-Type', 'application/json', true);
        $response->sendHeaders();

        return $this->render('default/api.json.twig', ['data' => $returnedData]);
    }
}
