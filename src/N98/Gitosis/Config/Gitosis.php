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