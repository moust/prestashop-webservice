<?php
namespace PrestashopWebservice;

use SimpleXMLElement;

class Ressource
{
    protected $ressource;

    public function __construct(SimpleXMLElement $xml)
    {
        $this->ressource = $xml;
    }

    public function __get($name)
    {
        $node = $this->ressource->{$name};

        return $this->parseNode($node);
    }

    protected function parseNode($node)
    {
        if (count($node->children())) {

            // $children = array();
            $children = new \stdClass();

            foreach ($node->children() as $child) {
                // echo $child->getName() .'<br />';

                if ($child->attributes()->node_type) {
                    $children->{$child->getName()} = array();
                    $node_type = (string) $child->attributes()->node_type;
                    foreach ($child->{$node_type} as $subchild) {
                        $children->{$child->getName()}[(integer) $subchild->id] = (string) $subchild->attributes('xlink', true)->href;
                    }
                }
                else {
                    $children = (array) $children;
                    $id = (integer) $child->attributes()->id;
                    $children[$id] = $this->parseNode($child);
                }
            }

            return $children;
        }

        $value = (string) $node;

        if (empty($value) && $node->attributes()->id) {
            $value = (string) $node->attributes()->id;
        }

        return $value;
    }
}