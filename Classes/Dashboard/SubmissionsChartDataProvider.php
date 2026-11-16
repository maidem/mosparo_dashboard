<?php

declare(strict_types=1);

namespace Maidem\MosparoDashboard\Dashboard;

use Maidem\MosparoDashboard\Service\MosparoStatisticService;
use TYPO3\CMS\Core\Localization\LanguageServiceFactory;
use TYPO3\CMS\Dashboard\Widgets\ChartDataProviderInterface;

final class SubmissionsChartDataProvider implements ChartDataProviderInterface
{
    private const LLL = 'LLL:EXT:mosparo_dashboard/Resources/Private/Language/locallang.xlf:';

    public function __construct(
        private readonly MosparoStatisticService $statisticService,
        private readonly LanguageServiceFactory $languageServiceFactory,
    ) {}

    /**
     * @return array{labels: list<int|string>, datasets: list<array<string, mixed>>}
     */
    public function getChartData(): array
    {
        $numbersByDate = $this->statisticService->getStatistic()?->getNumbersByDate() ?? [];
        ksort($numbersByDate);
        $languageService = $this->languageServiceFactory->createForBackendUser();

        return [
            'labels' => array_keys($numbersByDate),
            'datasets' => [
                [
                    'label' => $languageService->sL(self::LLL . 'chart.valid'),
                    'data' => array_column($numbersByDate, 'numberOfValidSubmissions'),
                    'backgroundColor' => '#2fb344',
                ],
                [
                    'label' => $languageService->sL(self::LLL . 'chart.spam'),
                    'data' => array_column($numbersByDate, 'numberOfSpamSubmissions'),
                    'backgroundColor' => '#dc3545',
                ],
            ],
        ];
    }
}
