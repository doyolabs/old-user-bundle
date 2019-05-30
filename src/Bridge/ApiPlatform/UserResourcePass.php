<?php


namespace Doyo\UserBundle\Bridge\ApiPlatform;


use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class UserResourcePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if(!$container->getParameter('doyo_user.api_platform')){
            return;
        }
        $this->generateApiResourceCache($container, 'user-resource.yaml','User.yaml');
        if($container->hasParameter('doyo_user.model.group.class')){
            $this->generateApiResourceCache($container, 'group-resource.yaml','Group.yaml');
        }
    }

    private function generateApiResourceCache(ContainerBuilder $container, $template, $path)
    {
        $cacheDir = __DIR__.'/../../Resources/config/api_resources';
        $debug = $container->getParameter('kernel.debug');
        $path  = $cacheDir.'/'.$path;
        $cache = new ConfigCache($path, $debug);

        if (!$cache->isFresh()) {
            $template = __DIR__.'/../../Resources/config/template/'.$template;
            $contents = file_get_contents($template);
            $contents = strtr($contents, [
                '%doyo_user.model.user.class%' => $container->getParameter('doyo_user.model.user.class'),
                '%doyo_user.model.group.class%' => $container->getParameter('doyo_user.model.group.class')
            ]);

            //file_put_contents($dir.'/user-resource.yaml', $contents, LOCK_EX);
            $resources = [new FileResource($template)];
            $cache->write($contents, $resources);
        }
        return $path;
    }
}