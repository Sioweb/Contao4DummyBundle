<?php

namespace Sioweb\DummyBundle;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Sioweb\DummyBundle\DependencyInjection\Extension;

/**
 * @author Sascha Weidner <http://www.sioweb.de>
 */
class SiowebDummyBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        return new Extension();
    }
}