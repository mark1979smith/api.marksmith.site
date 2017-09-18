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
        $returnedData['method'] = $request->getRealMethod();
        $returnedData['storage'] = $request->get('storage');
        $returnedData['what'] = $request->get('what');
        switch ($request->getRealMethod()) {
            case $request::METHOD_GET: // Used for basic read requests to the server
                if ($request->get('storage') == 'redis') {
                    /** @var \Symfony\Component\Cache\Adapter\RedisAdapter $cache */
                    $cache = $this->get('app.redis')->get();

                    $returnedData['result'] = $cache->hasItem($request->get('what'));
                    if ($returnedData['result']) {
                        $returnedData['contents'] = $cache->getItem($request->get('what'))->get();
                    }
                }
                break;

            case $request::METHOD_PUT: // Used to modify an existing object on the server

                break;

            case $request::METHOD_POST: // Used to create a new object on the server
                if ($request->get('storage') == 'redis') {
                    /** @var \Symfony\Component\Cache\Adapter\RedisAdapter $cache */
                    $cache = $this->get('app.redis')->get();
                    $item = $cache->getItem($request->get('what'));
                    $item->set((array) unserialize(base64_decode($request->get('data'))));

                    $returnedData['result'] = $cache->save($item);
                }
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
