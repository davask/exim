<?php

namespace Dwl\Exim\CoreBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Loader;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class DwlEximCoreExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function prepend(ContainerBuilder $container)
    {
        $params = array ();
        $params['exim.theme.demo'] = false;
        $params['exim.theme.name'] = 'Exim';
        $params['exim.theme.assets.version.css'] = time();
        $params['exim.theme.assets.version.js'] = time();
        $params['exim.theme.assets.version.img'] = time();
        $params['exim.theme.web.root'] = '%kernel.root_dir%/../web';
        $params['exim.theme.path.web'] = '/themes/%exim.theme.name%';
        $params['exim.theme.path.root'] = $params['exim.theme.web.root'] . $params['exim.theme.path.web'];
        $params['exim.theme.path.public'] = 'bundles/dwleximcore' . $params['exim.theme.path.web'];
        $params['exim.theme.path.internal'] = $params['exim.theme.path.root'];
        $params['exim.theme.path.private'] = 'DwlEximCoreBundle:themes/%exim.theme.name%';
        $params['exim.theme.path.upload'] = $params['exim.theme.path.root'] . '/uploads/media';

        foreach ($params as $key => $value) {
            if ($container->hasParameter($key)) {
                $params[$key] = $value;
            };
        }

        foreach ($params as $key => $value) {
            if ($container->hasParameter($key)) {
                $params[$key] = $container->getParameter($key);
            };
        }

        foreach ($params as $key => $value) {
            if (!$container->hasParameter($key)) {
                $container->setParameter($key, $value);
            };
        }

    }

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

}
