<?php

namespace N98\Gitosis\Config;

class Gitosis implements ElementInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var bool
     */
    protected $gitweb;

    /**
     * @var string
     */
    protected $loglevel = '';

    /**
     * @param string $name
     * @param array $data
     */
    public function __construct($name, array $data = array())
    {
        $this->name = $name;
    }

    /**
     * @param boolean $gitweb
     * @return Gitosis
     */
    public function setGitweb($gitweb)
    {
        $this->gitweb = $gitweb;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getGitweb()
    {
        return $this->gitweb;
    }

    /**
     * @param string $loglevel
     * @return Gitosis
     */
    public function setLoglevel($loglevel)
    {
        $this->loglevel = $loglevel;

        return $this;
    }

    /**
     * @return string
     */
    public function getLoglevel()
    {
        return $this->loglevel;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $data = array();

        if (!empty($this->loglevel)) {
            $data['loglevel'] = $this->loglevel;
        }
        if (isset($this->gitweb)) {
            $data['gitweb'] = $this->gitweb ? 'yes' : 'no';
        }

        return $data;
    }
}