<?php

declare(strict_types=1);

namespace Maidem\MosparoDashboard\Service;

use Mosparo\ApiClient\Client;
use Mosparo\ApiClient\StatisticResult;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;

class MosparoStatisticService
{
    public function __construct(
        private readonly ExtensionConfiguration $extensionConfiguration,
        private readonly FrontendInterface $cache,
    ) {}

    public function getStatistic(): ?StatisticResult
    {
        $cacheIdentifier = 'mosparo_dashboard_statistic';
        $cached = $this->cache->get($cacheIdentifier);
        if ($cached instanceof StatisticResult) {
            return $cached;
        }

        $client = $this->createClient();
        if ($client === null) {
            return null;
        }

        try {
            $statistic = $client->getStatisticByDate(60 * 60 * 24 * 14);
        } catch (\Throwable) {
            return null;
        }

        // ponytail: 5min cache, avoids hammering the mosparo API on every dashboard reload
        $this->cache->set($cacheIdentifier, $statistic, [], 300);

        return $statistic;
    }

    private function createClient(): ?Client
    {
        $config = $this->extensionConfiguration->get('mosparo_dashboard');

        if (empty($config['host']) || empty($config['publicKey']) || empty($config['privateKey'])) {
            return null;
        }

        return new Client($config['host'], $config['publicKey'], $config['privateKey']);
    }
}
