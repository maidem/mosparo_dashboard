<?php

declare(strict_types=1);

namespace Maidem\MosparoDashboard\Dashboard;

use Maidem\MosparoDashboard\Service\MosparoStatisticService;
use TYPO3\CMS\Dashboard\Widgets\ChartDataProviderInterface;

class SubmissionsChartDataProvider implements ChartDataProviderInterface
{
    public function __construct(private readonly MosparoStatisticService $statisticService) {}

    public function getChartData(): array
    {
        $numbersByDate = $this->statisticService->getStatistic()?->getNumbersByDate() ?? [];
        ksort($numbersByDate);

        return [
            'labels' => array_keys($numbersByDate),
            'datasets' => [
                [
                    'label' => 'Gültig',
                    'data' => array_column($numbersByDate, 'numberOfValidSubmissions'),
                    'backgroundColor' => '#2fb344',
                ],
                [
                    'label' => 'Spam',
                    'data' => array_column($numbersByDate, 'numberOfSpamSubmissions'),
                    'backgroundColor' => '#dc3545',
                ],
            ],
        ];
    }
}
