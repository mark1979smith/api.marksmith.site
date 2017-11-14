<?php
/**
 * Created by PhpStorm.
 * User: mark.smith
 * Date: 13/11/2017
 * Time: 13:32
 */

namespace AppBundle\Source;


class S3 extends SourceAbstract implements SourceInterface
{
    public function delete()
    {
        // TODO: Implement delete() method.
    }

    public function put()
    {
        // TODO: Implement put() method.
    }

    public function get()
    {
        /** @var \AppBundle\Utils\S3Client $s3Service */
        $s3Service = $this->container->get('app.aws.s3');
        $s3Client = $s3Service->get();

        $response = $s3Client->listObjects([
            'Bucket' => $s3Service->getBucket(),
        ]);

        return ['result' => $response];
    }

    public function post()
    {
        /** @var \AppBundle\Utils\S3Client $s3Service */
        $s3Service = $this->container->get('app.aws.s3');
        /** @var \Aws\S3\S3Client $s3Client */
        $s3Client = $s3Service->get();
        $response = $s3Client->putObject([
            'Bucket' => $s3Service->getBucket(),
            'Key' => $this->what,
            'Body' => $this->data
        ]);

        return ['result' => $response];
    }
}
