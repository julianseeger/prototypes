<?php

require_once '../../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app = new Silex\Application();
$app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/../views',
    )
);

$app->post('/{project}/{version}/{materialType}', function (Request $request, $project, $version, $materialType) {
        $material = $request->get('material');

        $interactor = new \Numbers\Interactor\AnalyzeMaterialInteractor();
        try {
            $interactor->execute($project, $version, $materialType, $material);
            return new Response("", 201);
        } catch (\Exception $e) {
            return new Response($e->getMessage() . "<br/>" . $e->getTraceAsString(), 500);
        }
    }
);
$app->get('/loadmaterial', function () {
        $material1 = file_get_contents('clover.xml');
        $material2 = file_get_contents('clover2.xml');
        $material3 = file_get_contents('clover3.xml');

        $interactor = new \Numbers\Interactor\AnalyzeMaterialInteractor();
        try {
            $interactor->execute('Testproject', 'v0.1', 'clover', $material1);
            $interactor->execute('Testproject', 'v0.2', 'clover', $material2);
            $interactor->execute('Testproject', 'v0.3', 'clover', $material3);
            return new Response("DONE", 201);
        } catch (\Exception $e) {
            return new Response($e->getMessage() . "<br/>" . $e->getTraceAsString(), 500);
        }
    }
);
$app->get('/', function () use ($app) {
        $interactor = new \Numbers\Interactor\SummaryInteractor();
        $summary = $interactor->execute();

        return $app['twig']->render('index.twig', $summary);
    }
);

$app->run();