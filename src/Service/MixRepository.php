<?php

namespace App\Service;

use Knp\Bundle\TimeBundle\DateTimeFormatter;
use Psr\Cache\CacheItemInterface;
use Symfony\Bridge\Twig\Command\DebugCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MixRepository
{
    public function __construct(
        private HttpClientInterface $githubContentClient,
        private CacheInterface $cache,
        private DateTimeFormatter $timeFormatter,
        #[Autowire('%kernel.debug%')]
        private bool $isDebug,
//        #[Autowire(service: 'twig.command.debug')]
//        private DebugCommand $twigDebugCommand
    ) {
    }

    public function findAll(): array
    {
//        $output = new BufferedOutput();
//        $this->twigDebugCommand->run(new ArrayInput([]), $output);
//        dd($output);


        $mixes = $this->cache->get('mix_data', function (CacheItemInterface $cacheItem) {
            $cacheItem->expiresAfter($this->isDebug ? 5 : 60);
            $response = $this->githubContentClient->request('GET', '/SymfonyCasts/vinyl-mixes/main/mixes.json');
            return $response->toArray();
        });

        array_walk($mixes, function (&$item) {
            $item['ago'] = $this->timeFormatter->formatDiff($item['createdAt']);
        });

        return $mixes;
    }
}
