<?php

namespace Drupal\digitalia_muni_collections\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Drupal\node\NodeInterface;
/**
 * Adds the Mihoo collection filter to Search API Views.
 */
class CollectionEventSubscriber implements EventSubscriberInterface {
    
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array {
        return [
            KernelEvents::REQUEST => ['onRequest', 30],
        ];
    }

    /**
     * Sets the collection session and applies it to search/browse URLs.
     */
    public function onRequest(RequestEvent $event): void {
        
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        $session = $request->getSession();
        $route_match = \Drupal::routeMatch();

        $path = $request->getPathInfo();
        $path_alias = \Drupal::service('path_alias.manager')->getAliasByPath($path);
        $route_name = $route_match->getRouteName();

        \Drupal::logger('digitalia_muni_collections')->notice('Current path: ' . $path .', Path Alias: ' . $path_alias . ', Route name: ' . $route_name);

        /*
         * Remove the collection when visiting the homepage.
         */
        if ($path === '/' || preg_match('#^/[a-z]{2}/?$#', $path)) {
            $session->remove('collection');
            return;
        }

        /*
         * Set the collection when visiting /collections/{collection}.
         */
        if ($path_alias && preg_match('#^/(?:[a-z]{2}/)?collections/([^/]+)/?$#', $path_alias, $matches)) {
            \Drupal::logger('digitalia_muni_collections')->notice('Setting collection to: ' . $matches[1]);
            $session->set('collection', $matches[1]);
            return;
        }

        /*
         * Nothing further to do if no collection is active.
         */
        if (!$session->get('collection', FALSE)) {
            return;
        }

        /*
         * Only apply the session collection to search, browse and node URLs.
         */
        if (
            !str_contains($path, '/search')
            && !str_contains($path, '/browse')
            && !str_contains($path, '/node/')
        ) {
            return;
        }

         /*
         * Allow an explicit query parameter to change or clear the collection.
         */
        if ($request->query->has('collection')) {
            $collection_filter = $request->query->get('collection');

            if (is_string($collection_filter) && $collection_filter !== '') {
                $session->set('collection', $collection_filter);
            } else {
                $session->remove('collection');
            }
        }

        /*
         * Preserve existing query parameters and synchronize the collection.
         */
        $current_query = $request->query->all();
        $query = $current_query;
        $collection = $session->get('collection');

        if (is_string($collection) && $collection !== '') {
            $query['collection'] = $collection;
        } else {
            unset($query['collection']);
        }

        /*
         * Redirect only if the query parameters actually changed.
         */
        if ($query === $current_query) {
            return;
        }

        $url = $path;

        if ($query) {
            $url .= '?' . http_build_query($query);
        }

        $event->setResponse(new RedirectResponse($url, 302));
    }
}