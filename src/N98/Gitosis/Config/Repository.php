<?php

namespace N98\Gitosis\Config;

class Repository implements ElementInterface
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
     * @param boolean $daemon
     * @return Repository
     */
    public function setDaemon($daemon)
    {
        $this->daemon = $daemon;

        return $this;
    }

    /**
     * @param string $description
     * @return Repository
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @param boolean $gitweb
     * @return Repository
     */
    public function setGitweb($gitweb)
    {
        $this->gitweb = $gitweb;

        return $this;
    }

    /**
     * @param string $name
     * @return Repository
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param string $owner
     * @return Repository
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;

        return $this;
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

    /**
     * @return array
     */
    public function toArray()
    {
        $data = array();

        if (isset($this->owner)) {
            $data['owner'] = $this->owner;
        }
        if (isset($this->description)) {
            $data['description'] = $this->description;
        }
        if (isset($this->daemon)) {
            $data['daemon'] = $this->daemon ? 'yes' : 'no';
        }
        if (isset($this->gitweb)) {
            $data['gitweb'] = $this->gitweb ? 'yes' : 'no';
        }

        return $data;
    }
}