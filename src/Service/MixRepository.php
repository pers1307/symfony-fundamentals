<?php

namespace App\Service;

use Knp\Bundle\TimeBundle\DateTimeFormatter;
use Psr\Cache\CacheItemInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MixRepository
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private CacheInterface $cache,
        private DateTimeFormatter $timeFormatter,
        #[Autowire('%kernel.debug%')]
        private bool $isDebug
    ) {
    }

    public function findAll(): array
    {
        $mixes = $this->cache->get('mix_data', function (CacheItemInterface $cacheItem) {
            $cacheItem->expiresAfter($this->isDebug ? 5 : 60);
            $response = $this->httpClient->request('GET', 'https://raw.githubusercontent.com/SymfonyCasts/vinyl-mixes/main/mixes.json');
            return $response->toArray();
        });

        array_walk($mixes, function (&$item) {
            $item['ago'] = $this->timeFormatter->formatDiff($item['createdAt']);
        });

        return $mixes;
    }
}
