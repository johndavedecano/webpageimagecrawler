<?php
require(dirname(dirname(__FILE__))."/libraries/Img.php");
class ImgTest extends PHPUnit_Framework_TestCase
{
    public function test_is_valid_url()
    {
        // Arrange
        $a = new Img();

        // Act
        $b = $a->is_valid_url();
        $c = $a->is_valid_url('http://google.com');
        // Assert
        $this->assertEquals(false, $b);
        $this->assertEquals(true, $c);
    }

    public function test_get_image_urls()
    {
        // Arrange
        $a = new Img();
        // Act
        $b = $a->get_image_urls();
        $c = $a->get_image_urls('http://www.dumpaday.com/random-pictures/funny-pictures/random-funny-pictures-40-pics-3/');
        // Assert
        $this->assertEmpty($b);
        $this->assertNotEmpty($c);
    }

    public function test_request()
    {
        // Arrange
        $a = new Img();
        // Act
        $b = $a->request();
        $c = $a->request('http://www.dumpaday.com/random-pictures/funny-pictures/random-funny-pictures-40-pics-3/');
        // Assert
        $this->assertEquals(false, $b);
        $this->assertNotEmpty($c);
    }

}
