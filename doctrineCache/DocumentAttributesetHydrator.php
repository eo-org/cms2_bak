<?php

namespace DoctrineMongoHydrator;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use Doctrine\ODM\MongoDB\Hydrator\HydratorInterface;
use Doctrine\ODM\MongoDB\UnitOfWork;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ODM. DO NOT EDIT THIS FILE.
 */
class DocumentAttributesetHydrator implements HydratorInterface
{
    private $dm;
    private $unitOfWork;
    private $class;

    public function __construct(DocumentManager $dm, UnitOfWork $uow, ClassMetadata $class)
    {
        $this->dm = $dm;
        $this->unitOfWork = $uow;
        $this->class = $class;
    }

    public function hydrate($document, $data, array $hints = array())
    {
        $hydratedData = array();

        /** @Field(type="id") */
        if (isset($data['_id'])) {
            $value = $data['_id'];
            $return = $value instanceof \MongoId ? (string) $value : $value;
            $this->class->reflFields['id']->setValue($document, $return);
            $hydratedData['id'] = $return;
        }

        /** @Field(type="string") */
        if (isset($data['label'])) {
            $value = $data['label'];
            $return = (string) $value;
            $this->class->reflFields['label']->setValue($document, $return);
            $hydratedData['label'] = $return;
        }

        /** @Field(type="string") */
        if (isset($data['type'])) {
            $value = $data['type'];
            $return = (string) $value;
            $this->class->reflFields['type']->setValue($document, $return);
            $hydratedData['type'] = $return;
        }

        /** @Many */
        $mongoData = isset($data['attributeList']) ? $data['attributeList'] : null;
        $return = new \Doctrine\ODM\MongoDB\PersistentCollection(new \Doctrine\Common\Collections\ArrayCollection(), $this->dm, $this->unitOfWork, '$');
        $return->setHints($hints);
        $return->setOwner($document, $this->class->fieldMappings['attributeList']);
        $return->setInitialized(false);
        if ($mongoData) {
            $return->setMongoData($mongoData);
        }
        $this->class->reflFields['attributeList']->setValue($document, $return);
        $hydratedData['attributeList'] = $return;

        /** @Field(type="boolean") */
        if (isset($data['isActive'])) {
            $value = $data['isActive'];
            $return = (bool) $value;
            $this->class->reflFields['isActive']->setValue($document, $return);
            $hydratedData['isActive'] = $return;
        }
        return $hydratedData;
    }
}