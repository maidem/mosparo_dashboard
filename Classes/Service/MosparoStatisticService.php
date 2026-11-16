<?php

declare(strict_types=1);

namespace Maidem\MosparoDashboard\Service;

use Mosparo\ApiClient\Client;
use Mosparo\ApiClient\StatisticResult;
use Psr\Log\LoggerInterface;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;

final class MosparoStatisticService
{
    private const CACHE_IDENTIFIER = 'mosparo_dashboard_statistic';
    private const FAILURE_MARKER = 'failed';
    private const LIFETIME_SUCCESS = 300;
    private const LIFETIME_FAILURE = 60;

    public function __construct(
        private readonly ExtensionConfiguration $extensionConfiguration,
        private readonly FrontendInterface $cache,
        private readonly LoggerInterface $logger,
    ) {}

    public function getStatistic(): ?StatisticResult
    {
        $cached = $this->cache->get(self::CACHE_IDENTIFIER);
        if ($cached instanceof StatisticResult) {
            return $cached;
        }
        if ($cached === self::FAILURE_MARKER) {
            // a recent request failed, do not retry on every dashboard reload
            return null;
        }

        $client = $this->createClient();
        if ($client === null) {
            return null;
        }

        try {
            $statistic = $client->getStatisticByDate(60 * 60 * 24 * 14);
        } catch (\Throwable $exception) {
            $this->logger->error('mosparo statistic request failed: ' . $exception->getMessage(), ['exception' => $exception]);
            $this->cache->set(self::CACHE_IDENTIFIER, self::FAILURE_MARKER, [], self::LIFETIME_FAILURE);
            return null;
        }

        $this->cache->set(self::CACHE_IDENTIFIER, $statistic, [], self::LIFETIME_SUCCESS);

        return $statistic;
    }

    private function createClient(): ?Client
    {
        $config = $this->extensionConfiguration->get('mosparo_dashboard');
        $host = $this->environment(['MOSPARO_HOST', 'MOSPARO_PUBLIC_SERVER']) ?? (string)($config['host'] ?? '');
        $publicKey = $this->environment(['MOSPARO_PUBLIC_KEY']) ?? (string)($config['publicKey'] ?? '');
        $privateKey = $this->environment(['MOSPARO_PRIVATE_KEY']) ?? (string)($config['privateKey'] ?? '');

        if ($host === '' || $publicKey === '' || $privateKey === '') {
            return null;
        }

        return new Client($host, $publicKey, $privateKey);
    }

    /**
     * @param list<string> $names
     */
    private function environment(array $names): ?string
    {
        foreach ($names as $name) {
            $value = getenv($name);
            if (is_string($value) && $value !== '') {
                return $value;
            }
        }

        return null;
    }
}
