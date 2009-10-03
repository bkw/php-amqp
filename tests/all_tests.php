<?php

if(!defined('SIMPLE_TEST'))
    define('SIMPLE_TEST', 'simpletest/');
require_once(SIMPLE_TEST . 'unit_tester.php');
require_once(SIMPLE_TEST . 'reporter.php');

$arch_bits = (PHP_INT_SIZE*8)."bit";

$test = &new TestSuite("PHP-AMQP tests ($arch_bits)");
$test->addTestFile('wire_format_test.php');

if(TextReporter::inCli())
    exit($test->run(new TextReporter()) ? 0 : 1);
else
    $test->run(new HtmlReporter());

?>