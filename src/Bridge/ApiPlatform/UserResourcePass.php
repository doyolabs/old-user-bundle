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
        $path = $this->generateApiResourceCache($container);
        $container->prependExtensionConfig('api_platform',[
            'mapping' => [
                'paths' => [
                    $path
                ]
            ]
        ]);
    }

    private function generateApiResourceCache(ContainerBuilder $container)
    {
        $cacheDir = $container->getParameter('kernel.cache_dir').'/doyo-user';
        if(!is_dir($cacheDir)){
            mkdir($cacheDir, 0775, true);
        }

        $debug = $container->getParameter('kernel.debug');
        $path  = $cacheDir.'/user-resource.yaml';
        $cache = new ConfigCache($path, $debug);

        if (!$cache->isFresh()) {
            $template = __DIR__.'/../../Resources/config/template/user-resource.yaml';
            $contents = file_get_contents($template);
            $contents = strtr($contents, [
                '%doyo_user.user_class%' => $container->getParameter('doyo_user.user_class'),
            ]);

            //file_put_contents($dir.'/user-resource.yaml', $contents, LOCK_EX);
            $resources = [new FileResource($template)];
            $cache->write($contents, $resources);
        }

        return $path;
    }
}