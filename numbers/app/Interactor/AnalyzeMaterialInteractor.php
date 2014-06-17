<?php

namespace Numbers\Interactor;

use Numbers\Core\FileBasedMetricsHub;
use Numbers\Sensor\CloverSensor;

class AnalyzeMaterialInteractor
{
    public function execute($projectName, $versionNumber, $materialType, $material)
    {
        $sensor = new CloverSensor();
        $sensor->analyze(new FileBasedMetricsHub(), $projectName, $versionNumber, $materialType, $material);
    }
}
