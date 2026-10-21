<?php

namespace Drupal\digitalia_muni_collections\PageCache;

use Drupal\Core\PageCache\RequestPolicyInterface;
use Symfony\Component\HttpFoundation\Request;

class CollectionPageCachePolicy implements RequestPolicyInterface {

    /**
     * {@inheritdoc}
     */
    public function check(Request $request) {
        $path = $request->getPathInfo();

        if (
            str_contains($path, '/collections/')
            || str_contains($path, '/search')
            || str_contains($path, '/browse')
        ) {
            return static::DENY;
        }

        return NULL;
    }

}