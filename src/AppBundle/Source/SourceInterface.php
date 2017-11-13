<?php
/**
 * Created by PhpStorm.
 * User: mark.smith
 * Date: 13/11/2017
 * Time: 08:10
 */

namespace AppBundle\Source;


use Psr\Log\LoggerInterface;

interface SourceInterface
{

    public function setData($data);

    public function setWhat($what);

    public function setLogger(LoggerInterface $logger);

    /**
     * Start method for interaction
     */
    public function get();

    public function post();

    public function put();

    public function delete();
}
