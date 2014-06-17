<?php

namespace Numbers\Core;

class FileBasedMetricsHub implements MetricsHub
{
    const METRICS_FILE = 'metrics.json';

    /**
     * @param string $projectName
     * @param string $versionNumber
     * @param string $path
     * @param string $metricName
     * @param int $value
     * @return mixed
     */
    public function addMetric($projectName, $versionNumber, $path, $metricName, $value)
    {
        $metrics = $this->loadMetrics();

        if (!isset($metrics[$projectName])) {
            $metrics[$projectName] = [];
        }
        if (!isset($metrics[$projectName][$versionNumber])) {
            $metrics[$projectName][$versionNumber] = [];
        }
        if (!isset($metrics[$projectName][$versionNumber][$path])) {
            $metrics[$projectName][$versionNumber][$path] = [];
        }

        $metrics[$projectName][$versionNumber][$path][$metricName] = $value;

        $this->saveMetrics($metrics);
    }

    /**
     * @return array
     */
    public function getMetrics() {
        return $this->loadMetrics();
    }

    /**
     * @return array
     */
    private function loadMetrics()
    {
        if (!file_exists(self::METRICS_FILE)) {
            return [];
        }

        return json_decode(file_get_contents(self::METRICS_FILE), true);
    }

    /**
     * @param array $metrics
     */
    private function saveMetrics(array $metrics)
    {
        file_put_contents(self::METRICS_FILE, json_encode($metrics));
    }

    public function getMetricTypes()
    {
        $metricTypes = [];
        $projects = $this->loadMetrics();
        foreach ($projects as $versions) {
            foreach ($versions as $files) {
                foreach ($files as $metrics) {
                    foreach ($metrics as $metricType => $value) {
                        $metricTypes[$metricType] = $metricType;
                    }
                }
            }
        }
        return $metricTypes;
    }
}
