<?php

namespace Numbers\Sensor;

use Numbers\Core\MetricsHub;

interface Sensor
{
    /**
     * @param string $materialType
     * @return bool
     */
    public function supports($materialType);

    /**
     * @param MetricsHub $metricsHub
     * @param $projectName
     * @param $versionNumber
     * @param $materialType
     * @param $material
     * @return mixed
     */
    public function analyze(MetricsHub $metricsHub, $projectName, $versionNumber, $materialType, $material);
}
