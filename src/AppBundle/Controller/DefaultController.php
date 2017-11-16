<?php

namespace AppBundle\Controller;

use AppBundle\Source\Source;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="api")
     * @Method({"GET", "POST", "PUT", "DELETE"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $returnedData = [];
        $returnedData['method'] = $request->getRealMethod();
        $returnedData['storage'] = $request->get('storage');
        $returnedData['what'] = $request->get('what');

        /** @var \AppBundle\Source\SourceAbstract $sourceClass */
        $sourceClass = Source::factory($request);

        $sourceClass->setContainer($this->container);
        if ($request->get('what')) {
            $sourceClass->setWhat($request->get('what'));
        }
        if ($request->get('data')) {
            $sourceClass->setData(unserialize(base64_decode($request->get('data'))));
        }
        $returnedSourceData = $sourceClass->{strtolower($request->getRealMethod())}();

        if (is_array($returnedSourceData)) {
            $returnedData = $returnedData + $returnedSourceData;
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
