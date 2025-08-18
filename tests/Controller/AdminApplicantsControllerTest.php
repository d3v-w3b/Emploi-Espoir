<?php

namespace App\Tests\Controller;

use App\Tests\BaseWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AdminApplicantsControllerTest extends BaseWebTestCase
{
    public function testIndexWithoutAuthentication(): void
    {
        $this->client->request('GET', '/admin/applicants');
        
        $this->assertResponseRedirects('/connexion');
    }

    public function testIndexWithNonAdminUser(): void
    {
        $this->loginAsUser();
        
        $this->client->request('GET', '/admin/applicants');
        
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testIndexWithAdminUser(): void
    {
        $this->loginAsAdmin();
        
        $this->client->request('GET', '/admin/applicants');
        
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('h1', 'Applicants');
    }

    public function testIndexResponseContent(): void
    {
        $this->loginAsAdmin();
        
        $this->client->request('GET', '/admin/applicants');
        
        $this->assertResponseIsSuccessful();
        $content = $this->client->getResponse()->getContent();
        $this->assertStringContainsString('<html><body><h1>Applicants</h1></body></html>', $content);
    }

    public function testIndexRouteIsCorrect(): void
    {
        $this->loginAsAdmin();
        
        $crawler = $this->client->request('GET', '/admin/applicants');
        
        $this->assertResponseIsSuccessful();
        $this->assertEquals('/admin/applicants', $this->client->getRequest()->getPathInfo());
    }
}