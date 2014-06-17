<?php

namespace Numbers\Core;

interface MetricsHub
{
    /**
     * @param string $projectName
     * @param string $versionNumber
     * @param string $path
     * @param string $metricName
     * @param int $value
     * @return mixed
     */
    public function addMetric($projectName, $versionNumber, $path, $metricName, $value);

    /**
     * @return array
     */
    public function getMetrics();

    /**
     * @return array
     */
    public function getMetricTypes();
}
 