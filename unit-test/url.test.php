<?php

class Test extends PHPUnit_Framework_TestCase {

    public function setUp() {
        $this->rules = array(
            'test' => array('url')
        );
    }

    public function tearDown() {
        
    }

    public function testHttpURLInput() {
        $inputs = array(
            'test' => "http://www.google.com"
        );
        $validator = SimpleValidator\Validator::validate($inputs, $this->rules);
        $this->assertEquals($validator->isSuccess(), true);
    }

    public function testHttpsURLInput() {
        $inputs = array(
            'test' => "https://www.google.com"
        );
        $validator = SimpleValidator\Validator::validate($inputs, $this->rules);
        $this->assertEquals($validator->isSuccess(), true);
    }

    public function testMailtoInput() {
        $inputs = array(
            'test' => "mailto:geliscan@gmail.com"
        );
        $validator = SimpleValidator\Validator::validate($inputs, $this->rules);
        $this->assertEquals($validator->isSuccess(), true);
    }

    public function testDomainInput() {
        $inputs = array(
            'test' => "www.google.com"
        );
        $validator = SimpleValidator\Validator::validate($inputs, $this->rules);
        $this->assertEquals($validator->isSuccess(), false);
    }

    public function testEmailInput() {
        $inputs = array(
            'test' => "geliscan@gmail.com"
        );
        $validator = SimpleValidator\Validator::validate($inputs, $this->rules);
        $this->assertEquals($validator->isSuccess(), false);
    }

    public function testFtpInput() {
        $inputs = array(
            'test' => "ftp://ftp.is.co.za.example.org/rfc/rfc1808.txt"
        );
        $validator = SimpleValidator\Validator::validate($inputs, $this->rules);
        $this->assertEquals($validator->isSuccess(), true);
    }

    public function testTelnetInput() {
        $inputs = array(
            'test' => "telnet://melvyl.ucop.example.edu/"
        );
        $validator = SimpleValidator\Validator::validate($inputs, $this->rules);
        $this->assertEquals($validator->isSuccess(), true);
    }

    public function testLdapInput() {
        $inputs = array(
            'test' => "ldap://[2001:db8::7]/c=GB?objectClass?one"
        );
        $validator = SimpleValidator\Validator::validate($inputs, $this->rules);
        $this->assertEquals($validator->isSuccess(), true);
    }

    /*
      public function testPhoneInput() {

      $inputs = array(
      'test' => "tel:+1-816-555-1212"
      );
      $validator = SimpleValidator\Validator::validate($inputs, $this->rules);
      $this->assertEquals($validator->isSuccess(), true);
      }

      public function testUrnInput() {
      $inputs = array(
      'test' => "urn:oasis:names:specification:docbook:dtd:xml:4.1.2"
      );
      $validator = SimpleValidator\Validator::validate($inputs, $this->rules);
      $this->assertEquals($validator->isSuccess(), true);
      }
     */

    public function testAnyStringInput() {
        $inputs = array(
            'test' => "simple validator"
        );
        $validator = SimpleValidator\Validator::validate($inputs, $this->rules);
        $this->assertEquals($validator->isSuccess(), false);
    }

}

?>
