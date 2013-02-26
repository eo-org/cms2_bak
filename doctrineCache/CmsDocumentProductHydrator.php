<?php

namespace DoctrineMongoHydrator;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use Doctrine\ODM\MongoDB\Hydrator\HydratorInterface;
use Doctrine\ODM\MongoDB\UnitOfWork;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ODM. DO NOT EDIT THIS FILE.
 */
class CmsDocumentProductHydrator implements HydratorInterface
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
        if (isset($data['attributesetId'])) {
            $value = $data['attributesetId'];
            $return = (string) $value;
            $this->class->reflFields['attributesetId']->setValue($document, $return);
            $hydratedData['attributesetId'] = $return;
        }

        /** @Field(type="string") */
        if (isset($data['groupId'])) {
            $value = $data['groupId'];
            $return = (string) $value;
            $this->class->reflFields['groupId']->setValue($document, $return);
            $hydratedData['groupId'] = $return;
        }

        /** @Field(type="string") */
        if (isset($data['label'])) {
            $value = $data['label'];
            $return = (string) $value;
            $this->class->reflFields['label']->setValue($document, $return);
            $hydratedData['label'] = $return;
        }

        /** @Field(type="string") */
        if (isset($data['name'])) {
            $value = $data['name'];
            $return = (string) $value;
            $this->class->reflFields['name']->setValue($document, $return);
            $hydratedData['name'] = $return;
        }

        /** @Field(type="string") */
        if (isset($data['sku'])) {
            $value = $data['sku'];
            $return = (string) $value;
            $this->class->reflFields['sku']->setValue($document, $return);
            $hydratedData['sku'] = $return;
        }

        /** @Field(type="float") */
        if (isset($data['price'])) {
            $value = $data['price'];
            $return = (float) $value;
            $this->class->reflFields['price']->setValue($document, $return);
            $hydratedData['price'] = $return;
        }

        /** @Field(type="string") */
        if (isset($data['fulltext'])) {
            $value = $data['fulltext'];
            $return = (string) $value;
            $this->class->reflFields['fulltext']->setValue($document, $return);
            $hydratedData['fulltext'] = $return;
        }

        /** @Field(type="string") */
        if (isset($data['introicon'])) {
            $value = $data['introicon'];
            $return = (string) $value;
            $this->class->reflFields['introicon']->setValue($document, $return);
            $hydratedData['introicon'] = $return;
        }

        /** @Field(type="string") */
        if (isset($data['introtext'])) {
            $value = $data['introtext'];
            $return = (string) $value;
            $this->class->reflFields['introtext']->setValue($document, $return);
            $hydratedData['introtext'] = $return;
        }

        /** @Field(type="string") */
        if (isset($data['metakey'])) {
            $value = $data['metakey'];
            $return = (string) $value;
            $this->class->reflFields['metakey']->setValue($document, $return);
            $hydratedData['metakey'] = $return;
        }

        /** @Field(type="int") */
        if (isset($data['sort'])) {
            $value = $data['sort'];
            $return = (int) $value;
            $this->class->reflFields['sort']->setValue($document, $return);
            $hydratedData['sort'] = $return;
        }

        /** @Field(type="hash") */
        if (isset($data['attachment'])) {
            $value = $data['attachment'];
            $return = $value;
            $this->class->reflFields['attachment']->setValue($document, $return);
            $hydratedData['attachment'] = $return;
        }

        /** @Field(type="hash") */
        if (isset($data['attributes'])) {
            $value = $data['attributes'];
            $return = $value;
            $this->class->reflFields['attributes']->setValue($document, $return);
            $hydratedData['attributes'] = $return;
        }

        /** @Field(type="hash") */
        if (isset($data['attributesLabel'])) {
            $value = $data['attributesLabel'];
            $return = $value;
            $this->class->reflFields['attributesLabel']->setValue($document, $return);
            $hydratedData['attributesLabel'] = $return;
        }

        /** @Field(type="string") */
        if (isset($data['status'])) {
            $value = $data['status'];
            $return = (string) $value;
            $this->class->reflFields['status']->setValue($document, $return);
            $hydratedData['status'] = $return;
        }
        return $hydratedData;
    }
}