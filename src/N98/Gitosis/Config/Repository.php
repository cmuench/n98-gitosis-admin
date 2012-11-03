<?php
/**
 * Copyright (c) 2012 Chistian Münch
 *
 * https://github.com/cmuench/n98-gitosis-admin
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE
 *
 * @author Christian Münch <christian@muench-worms.de>
 */

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
            $this->gitweb = $data['gitweb'] == 'yes' ? true : false;
        }
        if (isset($data['daemon'])) {
            $this->daemon = $data['daemon'] == 'yes' ? true : false;
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

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}