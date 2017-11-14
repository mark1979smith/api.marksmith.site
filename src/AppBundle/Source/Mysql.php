<?php
/**
 * Created by PhpStorm.
 * User: mark.smith
 * Date: 13/11/2017
 * Time: 08:06
 */
namespace AppBundle\Source;

class Mysql extends SourceAbstract implements SourceInterface
{
    public function get()
    {
        /** @var \Doctrine\Bundle\DoctrineBundle\Registry $db */
        $db = $this->container->get('doctrine');

        $repository = $db->getRepository($this->what);
        $queryData = [];
        if ($this->data) {
            $entity = $this->data;

            /** @var \Doctrine\ORM\Mapping\ClassMetadata $mappings */
            $mappings = $db->getManager()->getClassMetadata(get_class($entity));
            /** @var array $fieldNames */
            $fieldNames = $mappings->getFieldNames();
            /** @var array $columnNames */
            $columnNames = $mappings->getColumnNames();
            /** @var array $dbMapping */
            $dbMapping = array_combine($columnNames, $fieldNames);

            foreach ($dbMapping as $dbColumn => $entityMethodName) {
                $value = $entity->{'get'.ucfirst($entityMethodName)}() ?: '';
                if ($value) {
                    $queryData[$entityMethodName] = $value;
                }
            }
        }

        return ['result' => base64_encode(serialize($repository->findBy($queryData)))];
    }

    public function post()
    {
        /** @var \Doctrine\Bundle\DoctrineBundle\Registry $db */
        $db = $this->container->get('doctrine');

        $em = $db->getManager();

        $em->persist($this->data);
        $em->flush();

        $returnedData['result'] = $this->data->getId();

        return $returnedData;
    }

    public function put()
    {
        /** @var \Doctrine\Bundle\DoctrineBundle\Registry $db */
        $db = $this->container->get('doctrine');

        $entity = $this->data;

        $em = $db->getManager();

        $em->merge($entity);
        $em->flush();

        return ['result' => $entity->getId()];

    }

    public function delete()
    {
        // TODO: Implement delete() method.
    }
}
