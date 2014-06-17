<?php

namespace Numbers\Interactor;

use Numbers\Core\FileBasedMetricsHub;

class SummaryInteractor
{
    public function execute()
    {
        $hub = new FileBasedMetricsHub();
        return [
            'metricTypes' => $hub->getMetricTypes(),
            'metricResults' => $hub->getMetrics()
        ];
    }
}
 