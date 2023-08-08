<?php
require __DIR__ . '/../vendor/autoload.php';

use Varvaruk\PaymentsFeeCalculator\Service\Commission\CommissionCalculatorFactory;
use Varvaruk\PaymentsFeeCalculator\Service\CSVProcessor;

if (file_exists(__DIR__ . '/../.env')) {
    $repository = Dotenv\Repository\RepositoryBuilder::createWithNoAdapters()
        ->addAdapter(Dotenv\Repository\Adapter\EnvConstAdapter::class)
        ->addWriter(Dotenv\Repository\Adapter\PutenvAdapter::class)
        ->immutable()
        ->make();

    $dotenv = Dotenv\Dotenv::create($repository, __DIR__ . '/../');
    $dotenv->load();
}

$calculatorFactory = new CommissionCalculatorFactory();

// Initialize the CSV processor
$csvProcessor = new CSVProcessor($calculatorFactory);

// Check if the CSV file path is provided as a command line argument
if (isset($argv[1])) {
    $csvFilePath = $argv[1];
    try {
        $result = $csvProcessor->processCSV(__DIR__ . '/data/' . $csvFilePath);
        // Output the calculated commissions
        foreach ($result as $commission) {
            echo $commission . PHP_EOL;
        }
    } catch (Exception $e) {
        echo "Error occurred: " . $e->getMessage() . " in file " . $e->getFile() . " on line " . $e->getLine() . PHP_EOL;
        echo "Stack trace:" . PHP_EOL;
        echo $e->getTraceAsString() . PHP_EOL;
    }
} else {
    echo "Please provide the CSV file path as a command line argument." . PHP_EOL;
}
