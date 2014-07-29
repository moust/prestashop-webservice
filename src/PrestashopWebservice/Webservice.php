<?php
namespace PrestashopWebservice;

use PrestashopWebservice\Ressource;

use PrestaShopWebservice;
use PrestaShopWebserviceException;

use Psr\Log\LoggerInterface;

use Doctrine\Common\Cache\CacheProvider;

class Webservice
{
    private $webservice;

    private $cache;

    private $ttl = 60;

    private $logger;

    public function __construct($url, $key, $debug = true)
    {
        $this->webservice = new PrestaShopWebservice($url, $key, $debug);
    }

    public function setCacheProvider(CacheProvider $provider)
    {
        $this->cache = $provider;
    }

    public function getCacheProvider()
    {
        return $this->cache;
    }

    public function setTtl($ttl)
    {
        $this->ttl = (int) $ttl;
    }

    public function getTtl()
    {
        return $this->ttl;
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function getLogger()
    {
        return $this->logger;
    }

    public function add($options)
    {
        return $this->webservice->add($options);
    }

    public function get($options)
    {
        $id = md5(serialize($options));

        if ($this->getCacheProvider() && $this->getCacheProvider()->contains($id)) {
            $data = simplexml_load_string($this->getCacheProvider()->fetch($id));
            return $data;
        }

        try {
            $data = $this->webservice->get($options);
        }
        catch (PrestaShopWebserviceException $e) {
            if ($this->getLogger()) {
                $this->getLogger()->error($e->getMessage(), $options);
            }
            return false;
        }

        if ($this->getCacheProvider()) {
            $this->getCacheProvider()->save($id, $data->asXML(), $this->getTtl());
        }

        return $data;
    }

    public function head($options)
    {
        return $this->webservice->head($options);
    }

    public function edit($options)
    {
        return $this->webservice->edit($options);
    }

    public function delete($options)
    {
        if ($this->getCacheProvider()) {
            $id = md5(serialize($options));
            $this->getCacheProvider()->delete($id);
        }

        return $this->webservice->delete($options);
    }

    public function __call($methodName, $args)
    {
        if (preg_match('/^(get)([A-Z])(.*)$/', $methodName, $matches)) {
            $property = strtolower($matches[2]) . $matches[3];
        }

        switch($matches[1]) {
            case 'get':
                if (preg_match('/s$/', $methodName)) {
                    return $this->getAll($property);
                }
                else {
                    return $this->getOne($property, $args[0]);
                }
            default:
                throw new \BadMethodCallException('Method ' . $methodName . ' not exists');
        }
    }

    public function getAll($resource)
    {
        $xml = $this->get(array('resource' => $resource, 'display' => 'full'));

        $collection = array();

        foreach ($xml->{$resource}->children() as $node) {
            $ressource = new Ressource($node);
            $collection[$ressource->id] = $ressource;
        }

        return $collection;
    }

    public function getOne($resource, $id)
    {
        if ($xml = $this->get(array('resource' => $resource.'s', 'id' => $id))) {
            return new Ressource($xml->{$resource});
        }
        return false;
    }


    /**
     * Alias for transform 'category' to 'categorie'
     **/
    public function getCategory($id)
    {
        if ($xml = $this->get(array('resource' => 'categories', 'id' => $id))) {
            return new Ressource($xml->category);
        }
        return false;
    }
}