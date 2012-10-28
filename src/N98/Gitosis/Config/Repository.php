<?php

namespace N98\Gitosis\Config;

class Repository
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $description = 'No description';

    /**
     * @var string
     */
    protected $owner;

    /**
     * @var bool
     */
    protected $gitweb = false;

    /**
     * @var bool
     */
    protected $daemon = false;

    /**
     * @param string $name
     * @param array $data
     */
    public function __construct($name, array $data = array())
    {
        $this->name = $name;
        if (isset($data['owner'])) {
            $this->owner = $data['owner'];
        }
        if (isset($data['description'])) {
            $this->description = $data['description'];
        }
        if (isset($data['gitweb'])) {
            $this->gitweb = $data['gitweb'] == '1' ? true : false;
        }
        if (isset($data['daemon'])) {
            $this->daemon = $data['daemon'] == '1' ? true : false;
        }
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @return boolean
     */
    public function getDaemon()
    {
        return $this->daemon;
    }

    /**
     * @return boolean
     */
    public function getGitweb()
    {
        return $this->gitweb;
    }
}