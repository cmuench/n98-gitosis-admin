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

use Silex\WebTestCase;

class InterfaceTest extends WebTestCase
{
    public function createApplication()
    {
        $app = require __DIR__ . '/../../../../../src/web_bootstrap.php';

        $configFile = __DIR__ . '/../../../../test_config.yaml';
        $app['config'] = new N98\Gitosis\Admin\ConfigurationLoader($configFile);
        $app['session.test'] = true;
        $app['locale'] = 'en';

        // @TODO refactor config loading
        $app['gitosis_config'] = new N98\Gitosis\Config\Config(rtrim($app['config']->getGitosisAdminRootDirectory(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'gitosis.conf');

        return $app;
    }

    public function testInitialPage()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/');

        $this->assertTrue($client->getResponse()->isOk());
        $this->assertCount(1, $crawler->filter('h2:contains("Home")'));
    }

    /**
     * Repo list
     */
    public function testRepoList()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/repository/');
        $client->getResponse()->getContent();

        $this->assertTrue($client->getResponse()->isOk());
        $this->assertCount(1, $crawler->filter('h2:contains("Repositories")'));
        $this->assertCount(3, $crawler->filter('#repo_list li'));
    }

    /**
     * Repo create
     */
    public function testRepoCreate()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/repository/create');

        $this->assertTrue($client->getResponse()->isOk());
        $this->assertCount(1, $crawler->filter('form'));
        $this->assertEquals('form[name]', $crawler->filter('form input')->eq(0)->attr('name'));
        $this->assertEquals('form[owner]', $crawler->filter('form input')->eq(1)->attr('name'));
        $this->assertEquals('form[gitweb]', $crawler->filter('form input')->eq(2)->attr('name'));
        $this->assertEquals('form[daemon]', $crawler->filter('form input')->eq(3)->attr('name'));
    }

    /**
     * Group list
     */
    public function testGroupList()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/group/');

        $this->assertTrue($client->getResponse()->isOk());
        $this->assertCount(1, $crawler->filter('h2:contains("Groups")'));
        $this->assertCount(3, $crawler->filter('#group_list li'));
    }

    /**
     * Group create
     */
    public function testGroupCreate()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/group/create');

        $this->assertTrue($client->getResponse()->isOk());
        $this->assertCount(1, $crawler->filter('form'));
        $this->assertEquals('form[name]', $crawler->filter('form input')->eq(0)->attr('name'));
    }

    /**
     * User list
     */
    public function testUserList()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/user/');

        $this->assertTrue($client->getResponse()->isOk());
        $this->assertCount(1, $crawler->filter('h2:contains("Users")'));
        $this->assertCount(6, $crawler->filter('#user_list li'));
    }

    /**
     * User create
     */
    public function testUserCreate()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/user/create');

        $this->assertTrue($client->getResponse()->isOk());
        $this->assertCount(1, $crawler->filter('form'));
        $this->assertEquals('form[name]', $crawler->filter('form input')->eq(0)->attr('name'));
        $this->assertEquals('form[ssh_publickey_content]', $crawler->filter('form input')->eq(1)->attr('name'));
        $this->assertCount(4, $crawler->filter('form input'));
    }
}