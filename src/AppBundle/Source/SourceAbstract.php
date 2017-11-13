<?php
/**
 * Created by PhpStorm.
 * User: mark.smith
 * Date: 13/11/2017
 * Time: 08:14
 */

namespace AppBundle\Source;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class SourceAbstract implements ContainerAwareInterface, SourceInterface
{
    /** @var  mixed */
    protected $data;

    /** @var  mixed */
    protected $what;

    /** @var  ContainerInterface */
    protected $container;

    /** @var  \Psr\Log\LoggerInterface */
    protected $logger;


    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param mixed $data
     *
     * @return SourceAbstract
     */
    public function setData($data): SourceAbstract
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @param mixed $what
     *
     * @return SourceAbstract
     */
    public function setWhat($what): SourceAbstract
    {
        $this->what = $what;

        return $this;
    }

    /**
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return SourceAbstract
     */
    public function setLogger(\Psr\Log\LoggerInterface $logger): SourceAbstract
    {
        $this->logger = $logger;

        return $this;
    }


}
