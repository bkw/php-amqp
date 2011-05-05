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
    
    function testWriteLongLong()
    {
        $w = new AMQPWriter();
        $w->write_longlong('0');
        $this->assertEqual($w->getvalue(),"\0\0\0\0\0\0\0\0");

        $w = new AMQPWriter();
        $w->write_longlong('17129380943709185430');
        $this->assertEqual($this->bindump($w->getvalue()),
          '11101101:10110111:11001001:00110110:10000100:00100001:10100101:10010110');

        $w = new AMQPWriter();
        $w->write_longlong('9223372036854775807');
        $this->assertEqual($this->bindump($w->getvalue()),
          '01111111:11111111:11111111:11111111:11111111:11111111:11111111:11111111');

        $w = new AMQPWriter();
        $w->write_longlong('18446744073709551615');
        $this->assertEqual($this->bindump($w->getvalue()),
          '11111111:11111111:11111111:11111111:11111111:11111111:11111111:11111111');



        // First test with values represented as strings
        $this->longlongWriteAndRead('0');
        $this->longlongWriteAndRead('123');
        $this->longlongWriteAndRead('4294967296');
        $this->longlongWriteAndRead('994294967296');

        // Now, with real int values
        $this->longlongWriteAndRead(0);
        $this->longlongWriteAndRead(123);
        $this->longlongWriteAndRead(4294967296);
        $this->longlongWriteAndRead(994294967296);
    }

    function testWriteLong()
    {
        $w = new AMQPWriter();
        $w->write_long('0');
        $this->assertEqual($w->getvalue(),"\0\0\0\0");

        $w = new AMQPWriter();
        $w->write_long(2607669776);
        $this->assertEqual($this->bindump($w->getvalue()),
          '10011011:01101101:11100010:00010000');

        $w = new AMQPWriter();
        $w->write_long(2147483647);
        $this->assertEqual($this->bindump($w->getvalue()),
          '01111111:11111111:11111111:11111111');


        // First test with values represented as strings
        $this->longWriteAndRead('0');
        $this->longWriteAndRead('123');
        $this->longWriteAndRead('2147483647');


        // Now, with real int values
        $this->longWriteAndRead(0);
        $this->longWriteAndRead(123);
        $this->longWriteAndRead(2147483647);

    }

    protected function bindump($val) {
      $bits=array();
      foreach( str_split( $val ) as $byte ) {
        $bits[] = str_pad(decbin(ord($byte)),8,'0',STR_PAD_LEFT);
      }
      return implode(':',$bits);
    }


    function longlongWriteAndRead($v)
    {
        $w = new AMQPWriter();
        $w->write_longlong($v);

        $r = new AMQPReader($w->getvalue());
        $this->assertEqual($r->read_longlong(),$v);
    }

    function longWriteAndRead($v)
    {
        $w = new AMQPWriter();
        $w->write_long($v);

        $r = new AMQPReader($w->getvalue());
        $this->assertEqual($r->read_long(),$v);
    }

}
?>
