<?php
/**
 * Created by PhpStorm.
 * User: mark.smith
 * Date: 13/11/2017
 * Time: 13:36
 */

namespace AppBundle\Utils;


class S3Client
{
    /** @var  string */
    protected $version;

    /** @var  string */
    protected $region;

    /** @var  string */
    protected $bucket;

    /**
     * S3Client constructor.
     *
     * @param $version
     * @param $region
     * @param $bucket
     */
    public function __construct($version, $region, $bucket)
    {
        $this->setVersion($version);
        $this->setRegion($region);
        $this->setBucket($bucket);
    }

    public function get()
    {
        $s3 = new \Aws\S3\S3Client([
            'version' => $this->getVersion(),
            'region'  => $this->getRegion()
        ]);

        return $s3;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @param string $version
     *
     * @return S3Client
     */
    public function setVersion(string $version): S3Client
    {
        $this->version = $version;

        return $this;
    }

    /**
     * @return string
     */
    public function getRegion(): string
    {
        return $this->region;
    }

    /**
     * @param string $region
     *
     * @return S3Client
     */
    public function setRegion(string $region): S3Client
    {
        $this->region = $region;

        return $this;
    }

    /**
     * @return string
     */
    public function getBucket(): string
    {
        return $this->bucket;
    }

    /**
     * @param string $bucket
     *
     * @return S3Client
     */
    public function setBucket(string $bucket): S3Client
    {
        $this->bucket = $bucket;

        return $this;
    }


}
