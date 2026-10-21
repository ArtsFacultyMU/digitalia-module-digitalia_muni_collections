<?php

namespace Drupal\digitalia_muni_collections\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Provides a block displaying the collection query parameter.
 *
 * @Block(
 *   id = "current_collection_block",
 *   admin_label = @Translation("Current collection"),
 *   category = @Translation("Custom")
 * )
 */
class CurrentCollectionBlock extends BlockBase {

    /**
     * {@inheritdoc}
     */
    public function blockAccess(AccountInterface $account) {
        $collection = \Drupal::request()->query->get('collection');

        return AccessResult::allowedIf(
            is_string($collection) && $collection !== ''
        )->addCacheContexts(['url.query_args:collection']);
    }

    /**
     * {@inheritdoc}
     */
    public function build() {
        $config = \Drupal::config('digitalia_muni_collections.settings');
        $collections = $config->get('collections') ?? [];
        $currentCollection = \Drupal::request()->query->get('collection');
        if (!isset($collections[$currentCollection])) {
            return [];
        }

        $currentCollectionLabel = $collections[$currentCollection];

        return [
            '#plain_text' => (string) $this->t(
                'You are working in collection @collection',
                ['@collection' => $currentCollectionLabel]
            ),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheContexts() {
        return Cache::mergeContexts(
            parent::getCacheContexts(),
            ['url.query_args:collection']
        );
    }
}
