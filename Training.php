<?php
ini_set('memory_limit', '-1');
require_once 'vendor/autoload.php';


use Rubix\ML\Datasets\Unlabeled;
use Rubix\ML\Datasets\Labeled;
use Rubix\ML\Transformers\WordCountVectorizer;
use Rubix\ML\PersistentModel;
use Rubix\ML\Pipeline;
use Rubix\ML\Transformers\TextNormalizer;
use Rubix\ML\Tokenizers\NGram;
use Rubix\ML\Transformers\TfIdfTransformer;
use Rubix\ML\Transformers\ZScaleStandardizer;
use Rubix\ML\Classifiers\MultilayerPerceptron;
use Rubix\ML\NeuralNet\Layers\Dense;
use Rubix\ML\NeuralNet\Layers\Activation;
use Rubix\ML\NeuralNet\ActivationFunctions\LeakyReLU;
use Rubix\ML\NeuralNet\Layers\BatchNorm;
use Rubix\ML\NeuralNet\Layers\PReLU;
use Rubix\ML\NeuralNet\Optimizers\AdaMax;
use Rubix\ML\Persisters\Filesystem;
use Rubix\ML\CrossValidation\Reports\AggregateReport;
use Rubix\ML\CrossValidation\Reports\ConfusionMatrix;
use Rubix\ML\CrossValidation\Reports\MulticlassBreakdown;



$time_start = microtime(true);
$folderArtikel = "FolderArtikelRinciClean/";
$folderDirektori = scandir($folderArtikel);
$samples = [];
$labels = [];

foreach ($folderDirektori as $kategori) {
    if ($kategori == "." or $kategori == '..') {
    } else {
        $fileList = glob($folderArtikel . $kategori . '/*');
        foreach ($fileList as $filename) {
            if (is_file($filename)) {
                $artikels[] = [file_get_contents($filename), $kategori];
                $samples[] = file_get_contents($filename);
                $labels[] = $kategori;
                echo $kategori . " Proses  \n";
            }
        }
    }
}

$dataset = new Labeled($samples, $labels);
$datasetToPredict = new Unlabeled($samples);
$estimator = new PersistentModel(
    new Pipeline([
        new TextNormalizer(),
        new WordCountVectorizer(10000, 0.00008, 0.4, new NGram(1, 2)),
        new TfIdfTransformer(),
        new ZScaleStandardizer(),
    ], new MultilayerPerceptron([
        new Dense(100),
        new Activation(new LeakyReLU()),
        new Dense(100),
        new Activation(new LeakyReLU()),
        new Dense(100, 0.0, false),
        new BatchNorm(),
        new Activation(new LeakyReLU()),
        new Dense(50),
        new PReLU(),
        new Dense(50),
        new PReLU(),
    ], 256, new AdaMax(0.0001))),
    new Filesystem('model.rbx', true)
);

$estimator->train($dataset);
$estimator->save();
$predictions = $estimator->predict($datasetToPredict);
$report = new AggregateReport([
    new MulticlassBreakdown(),
    new ConfusionMatrix(),
]);
$results = $report->generate($predictions, $dataset->labels());
$results->toJSON()->saveTo(new Filesystem('report.json'));

$time_end = microtime(true);
$execution_time = ($time_end - $time_start) / 60;

echo "\n" . count($fileList) . "Total Execution Time: " . $execution_time . ' Mins';
