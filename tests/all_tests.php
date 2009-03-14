<?php

if(!defined('SIMPLE_TEST'))
    define('SIMPLE_TEST', 'simpletest/');
require_once(SIMPLE_TEST . 'unit_tester.php');
require_once(SIMPLE_TEST . 'reporter.php');

if(((int)4294967296)!=0)
    $arch_bits = "64bit";
else
    $arch_bits = "32bit";

$test = &new TestSuite("PHP-AMQP tests ($arch_bits)");
$test->addTestFile('wire_format_test.php');

if(TextReporter::inCli())
    exit($test->run(new TextReporter()) ? 0 : 1);
else
    $test->run(new HtmlReporter());

?>