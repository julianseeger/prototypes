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
$app->get('/loadmaterial', function (Request $request) {
        $material = file_get_contents('clover.xml');

        $interactor = new \Numbers\Interactor\AnalyzeMaterialInteractor();
        try {
            $interactor->execute('Testproject', 'v0.1', 'clover', $material);
            return new Response("DONE", 201);
        } catch (\Exception $e) {
            return new Response($e->getMessage() . "<br/>" . $e->getTraceAsString(), 500);
        }
    }
);
$app->get('/', function (Request $request) use ($app) {
        $interactor = new \Numbers\Interactor\SummaryInteractor();
        $summary = $interactor->execute();

        return $app['twig']->render('index.twig', $summary);
    }
);

$app->run();