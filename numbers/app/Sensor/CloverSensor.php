<?php

namespace Numbers\Sensor;

use Numbers\Core\MetricsHub;

class CloverSensor implements Sensor
{
    /**
     * @param string $materialType
     * @return bool
     */
    public function supports($materialType)
    {
        return $materialType === 'clover';
    }

    /**
     * @param MetricsHub $metricsHub
     * @param $projectName
     * @param $versionNumber
     * @param $materialType
     * @param $material
     * @throws \Exception
     * @return mixed
     */
    public function analyze(MetricsHub $metricsHub, $projectName, $versionNumber, $materialType, $material)
    {
        if (!$this->supports($materialType)) {
            throw new \Exception('Unsupported materialType ' . $materialType);
        }

        $coverage = simplexml_load_string($material);
        /** @var \SimpleXMLElement $project */
        $project = $coverage->project;
        /** @var \SimpleXMLElement $metrics */
        $metrics = $project->metrics;

        $attributes = $metrics->attributes();
        $metricsHub->addMetric(
            $projectName,
            $versionNumber,
            '/',
            'coverage',
            ((float)$attributes['coveredelements'] / (float)$attributes['elements'])
        );
        $metricsHub->addMetric(
            $projectName,
            $versionNumber,
            '/',
            'ncloc',
            (int)$attributes['ncloc']
        );



        foreach ($project->package as $package) {
            foreach ($package->file as $file) {
                if (!isset($file->class))
                    continue;
                $namespace = (string)$file->class->attributes()['namespace'];
                $className = (string)$file->class->attributes()['name'];

                $metrics = $file->class->metrics;

                $attributes = $metrics->attributes();

                if (((float)$attributes['elements']) == 0) {
                    continue;
                }
                $metricsHub->addMetric(
                    $projectName,
                    $versionNumber,
                    $namespace . '\\' . $className,
                    'coverage',
                    ((float)$attributes['coveredelements'] / (float)$attributes['elements'])
                );
            }
        }
    }
}
