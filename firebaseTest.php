<?php

namespace Firebase;

require_once "firebaseLib.php";

use Exception;

class FirebaseTest
{
    protected $_firebase;
    protected $_todoMilk = array(
        'name' => 'Pick the milk',
        'priority' => 1
    );

    protected $_todoBeer = array(
        'name' => 'Pick the beer',
        'priority' => 2
    );

    protected $_todoLEGO = array(
        'name' => 'Pick the LEGO',
        'priority' => 3
    );

    // --- set up your own database here
    const DEFAULT_URL = 'Your URL of firebase';
    const DEFAULT_TOKEN = 'Your secret token';
    const DEFAULT_TODO_PATH = '/Temperature';
    const DELETE_PATH = '/sample';
    const DEFAULT_SET_RESPONSE = '{"name":"Pick the milk","priority":1}';
    const DEFAULT_UPDATE_RESPONSE = '{"name":"Pick the beer","priority":2}';
    const DEFAULT_PUSH_RESPONSE = '{"name":"Pick the LEGO","priority":3}';
    const DEFAULT_DELETE_RESPONSE = 'null';
    const DEFAULT_URI_ERROR = 'You must provide a baseURI variable.';

    public function setUp()
    {
        $this->_firebase = new FirebaseLib(self::DEFAULT_URL, self::DEFAULT_TOKEN);
    }

    public function testNoBaseURI()
    {
        $errorMessage = null;
        try {
            new FirebaseLib();
        } catch (Exception $e) {
            $errorMessage = $e->getMessage();
        }

        $this->assertEquals(self::DEFAULT_URI_ERROR, $errorMessage);
    }

#    public function testSet($dataaa)
#    {
#        $response = $this->_firebase->set(self::DEFAULT_TODO_PATH, $dataaa);
#        //$this->assertEquals(self::DEFAULT_SET_RESPONSE, $response);
#    }
    
    public function testSet($dataaa,$pathh)
    {
        $response = $this->_firebase->set($pathh, $dataaa);
        //$this->assertEquals(self::DEFAULT_SET_RESPONSE, $response);
    }

    public function testGetAfterSet($pathhh)
    {
        $response = $this->_firebase->get($pathhh);
        echo "Value recieved from firebase.. ".$response;
        return $response;
        //$this->assertEquals(self::DEFAULT_SET_RESPONSE, $response);
    }

    public function testUpdate()
    {
        $response = $this->_firebase->update(self::DEFAULT_TODO_PATH, $this->_todoBeer);
        $this->assertEquals(self::DEFAULT_UPDATE_RESPONSE, $response);
    }

    public function testGetAfterUpdate()
    {
        $response = $this->_firebase->get(self::DEFAULT_TODO_PATH);
        $this->assertEquals(self::DEFAULT_UPDATE_RESPONSE, $response);
    }

    public function testPush()
    {
        $response = $this->_firebase->push(self::DEFAULT_TODO_PATH, $this->_todoLEGO);
        $this->assertRegExp('/{"name"\s?:\s?".*?}/', $response);
        return $this->_parsePushResponse($response);
    }

    /**
     * @depends testPush
     */
    public function testGetAfterPush($responseName)
    {
        $response = $this->_firebase->get(self::DEFAULT_TODO_PATH . '/' . $responseName);
        $this->assertEquals(self::DEFAULT_PUSH_RESPONSE, $response);
    }

    public function testDelete()
    {
        $response = $this->_firebase->delete(self::DELETE_PATH);
        $this->assertEquals(self::DEFAULT_DELETE_RESPONSE, $response);
    }

    public function testGetAfterDELETE()
    {
        $response = $this->_firebase->get(self::DEFAULT_TODO_PATH);
        $this->assertEquals(self::DEFAULT_DELETE_RESPONSE, $response);
    }

    /**
     * @param $response
     * @return mixed
     */
    private function _parsePushResponse($response)
    {
        $responseObj = json_decode($response);
        return $responseObj->name;
    }
}

$arduino_data = 80;
$arduino_data = $_GET['arduino_data'];
$sensor_values = explode("|",$arduino_data);
print_r($sensor_values);
echo "\tGot the temperature - ".$sensor_values[0];
echo "\nGot the humidity - ".$sensor_values[1];
echo "\nGot the soil moisture - ".$sensor_values[2];
$fb = new FirebaseTest();
$fb->setUp();
$fb->testSet($sensor_values[0],"/Temperature");
$fb->testSet($sensor_values[1],"/Humidity");
$fb->testSet($sensor_values[2],"/Moisture");
echo "\nValue set to firebase!\n\n";

$flag_irrigate = $fb->testGetAfterSet("/Irrigate");
$flag_irrigate_auto = $fb->testGetAfterSet("/AutoIrrigate");
$flag_wifi_password = $fb->testGetAfterSet("/wifi_password");
$flag_wifi_name = $fb->testGetAfterSet("/wifi_name");
$testingtemp = "onfdfdfdfd";

$flag_irrigate = $flag_irrigate_auto.$flag_irrigate;
//$flag_irrigate = $flag_irrigate_auto."|".$flag_irrigate."|".$flag_wifi_password."|".$flag_wifi_name;
$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
fwrite($myfile, $flag_irrigate);
fclose($myfile);
?>
