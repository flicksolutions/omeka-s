<?php
namespace Omeka\Media\File\Renderer;

use Omeka\Api\Exception;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ConfigInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Manager extends AbstractPluginManager
{
    /**
     * Do not replace strings during canonicalization.
     *
     * This prevents distinct yet similarly named media handlers (such as
     * "foo_bar" and "foobar") from referencing the same handler instance.
     *
     * {@inheritDoc}
     */
    protected $canonicalNamesReplacements = array();

    /**
     * {@inheritDoc}
     */
    public function __construct(ConfigInterface $configuration = null)
    {
        parent::__construct($configuration);
        $this->addInitializer(array($this, 'injectAdapterDependencies'), false);
    }

    /**
     * Inject required dependencies into the handler.
     *
     * {@inheritDoc}
     */
    public function injectAdapterDependencies($handler,
        ServiceLocatorInterface $serviceLocator
    ) {
        $handler->setServiceLocator($serviceLocator->getServiceLocator());
    }

    /**
     * {@inheritDoc}
     */
    public function validatePlugin($plugin)
    {
        if (!is_subclass_of($plugin, 'Omeka\Media\File\Renderer\RendererInterface')) {
            throw new Exception\InvalidAdapterException(sprintf(
                'The file renderer class "%1$s" does not implement Omeka\Media\File\Renderer\RendererInterface.',
                get_class($plugin)
            ));
        }
    }
}
