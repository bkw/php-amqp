<?php

if(!defined('SIMPLE_TEST'))
    define('SIMPLE_TEST', 'simpletest/');
require_once(SIMPLE_TEST . 'unit_tester.php');
require_once(SIMPLE_TEST . 'reporter.php');

require_once("../amqp_wire.inc");


class AMQPWriterTests extends UnitTestCase
{
    function TestOfLogging()
    {
        $this->UnitTestCase();
    }
    
    function testWriteLong()
    {
        $w = new AMQPWriter();
        $w->write_longlong('0');
        $this->assertEqual($w->getvalue(),"\0\0\0\0\0\0\0\0");

        // First test with values represented as strings
        $this->longlongWriteAndRead('0');
        $this->longlongWriteAndRead('123');
        $this->longlongWriteAndRead('4294967296');
        $this->longlongWriteAndRead('994294967296');

        // Now, with real int values
        $this->longlongWriteAndRead(0);
        $this->longlongWriteAndRead(123);
        $this->longlongWriteAndRead(4294967296);
    }

    function longlongWriteAndRead($v)
    {
        $w = new AMQPWriter();
        $w->write_longlong($v);
        
        $r = new AMQPReader($w->getvalue());
        $this->assertEqual($r->read_longlong(),$v);
    }
    
}
?>