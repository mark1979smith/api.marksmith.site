<?php
/**
 * Created by PhpStorm.
 * User: mark.smith
 * Date: 13/11/2017
 * Time: 08:06
 */

namespace AppBundle\Source;

class Redis extends SourceAbstract implements SourceInterface
{
    public function get()
    {
        /** @var \Symfony\Component\Cache\Adapter\RedisAdapter $cache */
        $cache = $this->container->get('app.redis')->get();

        $returnedData = [];

        $returnedData['result'] = $cache->hasItem($this->what);
        if ($returnedData['result']) {
            $returnedData['contents'] = $cache->getItem($this->what)->get();
        }

        return $returnedData;
    }

    public function post()
    {
        /** @var \Symfony\Component\Cache\Adapter\RedisAdapter $cache */
        $cache = $this->container->get('app.redis')->get();
        $item = $cache->getItem($this->what);
        $item->set((array) $this->data);

        return ['result' => $cache->save($item)];
    }

    public function put()
    {
        // TODO: Implement put() method.
    }

    public function delete()
    {
        // TODO: Implement delete() method.
    }
}
