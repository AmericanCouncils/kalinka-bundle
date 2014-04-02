<?php

namespace AC\KalinkaBundle\EventDispatcher;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\PreSerializeEvent;
use JMS\Serializer\EventDispatcher\PreDeserializeEvent;

class KalinkaAuthorizationSubscriber implements EventSubscriberInterface
{
    private $auth;
    private $reader;

    protected function normalizeString($inputString)
    {
        $output = strtolower($inputString);
        $output = str_replace("_", "", $output);

        return $output;
    }

    // todo typehinting
    public function __construct($auth, $reader)
    {
        $this->auth = $auth;
        $this->reader = $reader;
    }

    public static function getSubscribedEvents()
    {
        return [
            [
                'event' => 'serializer.pre_serialize',
                'method' => 'onPreSerialize'
            ],
            [
                'event' => 'serializer.pre_deserialize',
                'method' => 'onPreDeserialize'
            ],
        ];
    }

    public function onPreSerialize(PreSerializeEvent $event)
    {
        $object = $event->getObject();
        $factory = $event->getContext()->getMetadataFactory();
        $classMetadata = $factory->getMetadataForClass(get_class($object));
        $reflObject = new \ReflectionObject($event->getObject());
        $properties = ($reflObject->getProperties());
        $defaultGuardAnnotation = $this->reader->getClassAnnotation(
            $reflObject,
            'AC\KalinkaBundle\Annotation\DefaultGuard'
        );
        if ($defaultGuardAnnotation) {
            $defaultGuard = $defaultGuardAnnotation->guard['value'];
        }

        foreach ($properties as $property) {
            $SerializeAnno = $this->reader->getPropertyAnnotation(
                $property,
                'AC\KalinkaBundle\Annotation\Serialize'
            );
            $serializeAndDeserializeAnno = $this->reader->getPropertyAnnotation(
                $property,
                'AC\KalinkaBundle\Annotation\SerializeAndDeserialize'
            );

            if (!is_null($SerializeAnno) && !is_null($serializeAndDeserializeAnno)) {
                throw new \RuntimeException(
                    "$property has both a Serialize and a SerializeAndDeserialize annotation set - remove one."
                );
            }

            $propAnnotation = $SerializeAnno ? $SerializeAnno : $serializeAndDeserializeAnno;

            if ($propAnnotation) {
                // TODO why is it sometimes 'action' and sometimes 'value'?
                if (isset($propAnnotation->action['action'])) {
                    $action = $propAnnotation->action['action'];
                } else {
                    $action = $propAnnotation->action['value'];
                }
                $guard = $defaultGuard;
                // TODO: use property guard if one is set in the annotation
                $allowed = $this->auth->can($action, $guard, $object);
            } else {
                // what to do if there is no property annotation? Default to show, or hide?
                // currently defaulting to show. It might make more sense to make
                // this configurable in the app config.
                $allowed = true;
            }
            if (!$allowed) {
                $propertyMetadata = $classMetadata->propertyMetadata[$property->name];
                // print_r($propertyMetadata);
                // TODO true if there is a configured setter
                // print_r(is_null($propertyMetadata->setter));
                if (isset($propertyMetadata->setter)) {
                    $setter = $propertyMetadata->setter(null);
                    $object->$setter(null);
                } else {
                    // use reflection
                    $property->setAccessible(true);
                    $property->setValue($object, null);
                }
            }
        }
    }

    public function onPreDeserialize(PreDeserializeEvent $event)
    {
        $eventData = $event->getData();
        $target = $event->getType()['name'];
        $reflObject = new \ReflectionObject(new $target);
        $properties = ($reflObject->getProperties());
        $defaultGuardAnnotation = $this->reader->getClassAnnotation(
            $reflObject,
            'AC\KalinkaBundle\Annotation\DefaultGuard'
        );
        if ($defaultGuardAnnotation) {
            $defaultGuard = $defaultGuardAnnotation->guard['value'];
        }

        foreach ($properties as $property) {
            $deserializeAnno = $this->reader->getPropertyAnnotation(
                $property,
                'AC\KalinkaBundle\Annotation\Deserialize'
            );
            $serializeAndDeserializeAnno = $this->reader->getPropertyAnnotation(
                $property,
                'AC\KalinkaBundle\Annotation\SerializeAndDeserialize'
            );

            if (!is_null($deserializeAnno) && !is_null($serializeAndDeserializeAnno)) {
                throw new \RuntimeException(
                    "$property has both a Deserialize and a SerializeAndDeserialize annotation set - remove one."
                );
            }

            $propAnnotation = $deserializeAnno ? $deserializeAnno : $serializeAndDeserializeAnno;

            $propAnnotation = $this->reader->getPropertyAnnotation(
                $property,
                'AC\KalinkaBundle\Annotation\Deserialize'
            );
            if ($propAnnotation) {
                // TODO why is it sometimes 'action' and sometimes 'value'?
                if (isset($propAnnotation->action['action'])) {
                    $action = $propAnnotation->action['action'];
                } else {
                    $action = $propAnnotation->action['value'];
                }
                $guard = $defaultGuard;
                // TODO: use property guard if one is set in the annotation
                $allowed = $this->auth->can($action, $guard);
            } else {
                // what to do if there is no property annotation? Default to allow, or deny?
                // currently defaulting to allow. It might make more sense to
                // make this configurable in the app config.
                $allowed = true;
            }
            if (!$allowed) {
                foreach ($eventData as $key => $value) {
                    if ($this->normalizeString($key) == $this->normalizeString($property->name)) {
                        // it shouldn't be deserialized, so strip the argument
                        // out of the event data.
                        unset($eventData[$key]);
                    }
                }
            }
        }
        // set the updated data for serializer to use
        $event->setData($eventData);
    }
}
