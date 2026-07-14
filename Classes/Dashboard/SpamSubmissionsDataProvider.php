<?php

declare(strict_types=1);

namespace Maidem\MosparoDashboard\Dashboard;

use Maidem\MosparoDashboard\Service\MosparoStatisticService;
use TYPO3\CMS\Dashboard\Widgets\NumberWithIconDataProviderInterface;

class SpamSubmissionsDataProvider implements NumberWithIconDataProviderInterface
{
    public function __construct(private readonly MosparoStatisticService $statisticService) {}

    public function getNumber(): int
    {
        return $this->statisticService->getStatistic()?->getNumberOfSpamSubmissions() ?? 0;
    }
}
