<?php

namespace App\Tests\Controller\User\Account\Career\Skills;

use App\Repository\CareerRepository;
use App\Tests\Controller\UserAuthenticationTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\Career;

class SkillsManagerControllerTest extends WebTestCase
{
    use UserAuthenticationTrait;

    public function testAccessDeniedForAnonymousUser()
    {
        $client = static::createClient();
        $client->request('GET', '/account/skills/skills-manager');

        $this->assertResponseRedirects('/login/password');
        $this->assertResponseStatusCodeSame(302);
    }

    public function testFormIsDisplayedForAuthenticatedUser()
    {
        $client = $this->createAuthenticatedClient();
        $client->request('GET', '/account/skills/skills-manager');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorTextContains('h1', 'Compétences');
        $this->assertSelectorExists('form[name="skills_manager"]');
        $this->assertSelectorExists('button[type="submit"]');
    }

    public function testSubmitValidSkills()
    {
        $client = $this->createAuthenticatedClient();
        $crawler = $client->request('GET', '/account/skills/skills-manager');

        $form = $crawler->selectButton('Enregistrer')->form([
            'skills_manager[skills]' => ['PHP', 'Symfony', 'Docker', 'PostgreSQL', 'Git']
        ]);

        $client->submit($form);

        // Vérifications de la réponse
        $this->assertResponseRedirects('/account/skills');
        $this->assertResponseStatusCodeSame(302);

        // Vérifications en base de données
        $careerRepository = static::getContainer()->get(CareerRepository::class);
        $career = $careerRepository->findOneByUser($this->getTestUser());

        $this->assertNotNull($career);
        $this->assertInstanceOf(Career::class, $career);
        $this->assertNotNull($career->getSkills());
        $this->assertContains('PHP', $career->getSkills());
        $this->assertContains('Symfony', $career->getSkills());
        $this->assertCount(5, $career->getSkills());
    }

    public function testSubmitEmptySkills()
    {
        $client = $this->createAuthenticatedClient();
        $crawler = $client->request('GET', '/account/skills/skills-manager');

        $form = $crawler->selectButton('Enregistrer')->form([
            'skills_manager[skills]' => []
        ]);

        $client->submit($form);

        // Le formulaire devrait être accepté même vide (cas de réinitialisation des compétences)
        $this->assertResponseRedirects('/account/skills');
        
        // Vérification en base de données
        $careerRepository = static::getContainer()->get(CareerRepository::class);
        $career = $careerRepository->findOneByUser($this->getTestUser());
        
        $this->assertNotNull($career);
        $this->assertEmpty($career->getSkills());
    }

    public function testSubmitDuplicateSkills()
    {
        $client = $this->createAuthenticatedClient();
        $crawler = $client->request('GET', '/account/skills/skills-manager');

        $form = $crawler->selectButton('Enregistrer')->form([
            'skills_manager[skills]' => ['PHP', 'PHP', 'Symfony', 'symfony']
        ]);

        $client->submit($form);

        // Vérification en base de données
        $careerRepository = static::getContainer()->get(CareerRepository::class);
        $career = $careerRepository->findOneByUser($this->getTestUser());
        
        $this->assertNotNull($career);
        $uniqueSkills = array_unique($career->getSkills());
        $this->assertSame(count($uniqueSkills), count($career->getSkills()), 'Les compétences ne devraient pas contenir de doublons');
    }

    public function testSkillsWithSpecialCharacters()
    {
        $client = $this->createAuthenticatedClient();
        $crawler = $client->request('GET', '/account/skills/skills-manager');

        $skillsWithSpecialChars = ['C++', '.NET Core', 'Node.js', 'Gestion d\'équipe', "Base de données"];
        
        $form = $crawler->selectButton('Enregistrer')->form([
            'skills_manager[skills]' => $skillsWithSpecialChars
        ]);

        $client->submit($form);

        // Vérification en base de données
        $careerRepository = static::getContainer()->get(CareerRepository::class);
        $career = $careerRepository->findOneByUser($this->getTestUser());
        
        $this->assertNotNull($career);
        foreach ($skillsWithSpecialChars as $skill) {
            $this->assertContains($skill, $career->getSkills(), "La compétence '$skill' devrait être sauvegardée correctement");
        }
    }
}

    /* RÉSUMÉ DES TESTS :
     * ✓ Test d'accès anonyme - vérifie la redirection vers la page de login
     * ✓ Test d'affichage du formulaire - vérifie la présence de tous les éléments
     * ✓ Test de soumission valide - vérifie l'enregistrement des compétences
     * ✓ Test de soumission vide - vérifie la gestion d'une liste vide
     * ✓ Test de doublons - vérifie l'unicité des compétences
     * ✓ Test caractères spéciaux - vérifie le traitement des caractères spéciaux
     *
     * RECOMMENDATIONS :
     * 1. Implémenter une validation des compétences (longueur min/max, caractères autorisés)
     * 2. Ajouter une limite au nombre total de compétences
     * 3. Considérer l'ajout d'une normalisation des compétences (casse, espaces)
     * 4. Ajouter des suggestions de compétences courantes
     * 5. Implémenter un système de catégorisation des compétences
     */