<?php
namespace PrestashopWebservice\Tests;

use PrestashopWebservice\Webservice;

class PrestashopWebserviceTest extends \PHPUnit_Framework_TestCase
{
    private $webservice;

    protected function setUp()
    {
        $this->webservice = new Webservice('http://192.168.0.42:8888/aurelieb/', '1BSZHTAZ1PVVRP4186AX9KQP6QB2GLFL', false);
    }

    public function testInstance()
    {
        $this->assertInstanceOf('PrestashopWebservice\Webservice', $this->webservice);
    }

    public function testGetProducts()
    {
        $products = $this->webservice->getProducts();

        // result must be an array of Ressource
        $this->assertInternalType('array', $products);
        $this->assertContainsOnlyInstancesOf('PrestashopWebservice\Ressource', $products);
    }

    public function testGetProduct()
    {
        $product = $this->webservice->getProduct(42);

        $this->assertInstanceOf('PrestashopWebservice\Ressource', $product);

        // property like "id" must be scalar type
        if ($product->id) {
            $this->assertInternalType('string', $product->id);
        }

        // property translated like "name" must be an array containing strings
        if ($product->name) {
            $this->assertInternalType('array', $product->name);
            $this->assertContainsOnly('string', $product->name);
        }

        // "associations" property must be an object collection
        if ($product->associations) {
            $this->assertInternalType('object', $product->associations);

            // associated "categories" must be an array containing strings
            if ($product->associations->categories) {
                $this->assertInternalType('array', $product->associations->categories);
                $this->assertContainsOnly('string', $product->associations->categories);
            }

            // associated "images" must be an array containing strings
            if ($product->associations->images) {
                $this->assertInternalType('array', $product->associations->images);
                $this->assertContainsOnly('string', $product->associations->images);
            }
        }
    }

    /**
     * @expectedException PrestaShopWebserviceException
     **/
    public function testGetProductNotFound()
    {
        $product = $this->webservice->getProduct(99999);

        $this->assertFalse($product);
    }

    /**
     * @expectedException PrestaShopWebserviceException
     **/
    public function testGetConfigurations()
    {
        $configurations = $this->webservice->getConfigurations();

        // result must be an array of Ressource
        $this->assertInternalType('array', $configurations);
        $this->assertContainsOnlyInstancesOf('PrestashopWebservice\Ressource', $configurations);
    }

    /**
     * @expectedException PrestaShopWebserviceException
     **/
    public function testGetConfiguration()
    {
        $configuration = $this->webservice->getConfiguration(1);

        $this->assertInstanceOf('PrestashopWebservice\Ressource', $configuration);
    }

    public function testGetCategories()
    {
        $categories = $this->webservice->getCategories();

        // result must be an array of Ressource
        $this->assertInternalType('array', $categories);
        $this->assertContainsOnlyInstancesOf('PrestashopWebservice\Ressource', $categories);
    }

    public function testGetCategory()
    {
        $category = $this->webservice->getCategory(1);

        $this->assertInstanceOf('PrestashopWebservice\Ressource', $category);
    }

    /**
     * @expectedException PrestaShopWebserviceException
     **/
    public function testGetCustomers()
    {
        $customers = $this->webservice->getCustomers();

        // result must be an array of Ressource
        $this->assertInternalType('array', $customers);
        $this->assertContainsOnlyInstancesOf('PrestashopWebservice\Ressource', $customers);
    }

    /**
     * @expectedException PrestaShopWebserviceException
     **/
    public function testGetCustomer()
    {
        $customer = $this->webservice->getCustomer(1);

        $this->assertInstanceOf('PrestashopWebservice\Ressource', $customer);
    }

    /**
     * @expectedException PrestaShopWebserviceException
     **/
    public function testGetManufacturers()
    {
        $manufacturers = $this->webservice->getManufacturers();

        // result must be an array of Ressource
        $this->assertInternalType('array', $manufacturers);
        $this->assertContainsOnlyInstancesOf('PrestashopWebservice\Ressource', $manufacturers);
    }

    /**
     * @expectedException PrestaShopWebserviceException
     **/
    public function testGetManufacturer()
    {
        $manufacturer = $this->webservice->getManufacturer(1);

        $this->assertInstanceOf('PrestashopWebservice\Ressource', $manufacturer);
    }

    /**
     * @expectedException PrestaShopWebserviceException
     **/
    public function testGetStates()
    {
        $states = $this->webservice->getStates();

        // result must be an array of Ressource
        $this->assertInternalType('array', $states);
        $this->assertContainsOnlyInstancesOf('PrestashopWebservice\Ressource', $states);
    }

    /**
     * @expectedException PrestaShopWebserviceException
     **/
    public function testGetState()
    {
        $state = $this->webservice->getState(1);

        $this->assertInstanceOf('PrestashopWebservice\Ressource', $state);
    }
}