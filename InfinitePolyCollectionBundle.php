<?php

namespace Infinite\PolyCollectionBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class InfinitePolyCollectionBundle extends Bundle
{
    // add compiler passes for plugins and store services
    public function build(ContainerBuilder $container){
        parent::build($container);
    }

}
