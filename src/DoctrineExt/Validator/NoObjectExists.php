<?php

namespace DoctrineExt\Validator;

use Doctrine\ORM\EntityRepository;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\Stdlib\Exception\InvalidArgumentException;

class NoObjectExists extends \DoctrineModule\Validator\NoObjectExists implements ServiceLocatorAwareInterface
{

    const ERROR_OBJECT_FOUND = 'objectFound';

    protected $messageTemplates = array(
        self::ERROR_OBJECT_FOUND => "An object matching '%value%' already exists",
    );

    /**
     * @var ServiceLocatorInterface
     */
    private $serviceLocator;

    /**
     * @var array
     */
    protected $options = [
        'entity_manager' => null,
        'entity_class' => null,
    ];

    public function __construct(array $options)
    {
        $this->setOptions($options);

        if (!isset($options['fields'])) {
            throw new InvalidArgumentException('Options must contain "fields" definition');
        }

        if (isset($this->messageTemplates)) {
            $this->abstractOptions['messageTemplates'] = $this->messageTemplates;
        }

        if (isset($this->messageVariables)) {
            $this->abstractOptions['messageVariables'] = $this->messageVariables;
        }

        $this->fields[] = $options['fields'];
    }

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    public function isValid($value)
    {
        $this->setEntityRepository();
        return parent::isValid($value);
    }

    private function setEntityRepository()
    {
        if ($this->objectRepository instanceof EntityRepository) {
            return $this->objectRepository;
        }

        $entityManagerName = $this->options['entity_manager'];
        $entityClassName = $this->options['entity_class'];

        $entityManager = $this->serviceLocator->getServiceLocator()->get($entityManagerName);
        $entityRepository = $entityManager->getRepository($entityClassName);

        $this->objectRepository = $entityRepository;
    }

    protected function createMessage($messageKey, $value)
    {
        $value = current($value);
        return parent::createMessage($messageKey, $value);
    }
}