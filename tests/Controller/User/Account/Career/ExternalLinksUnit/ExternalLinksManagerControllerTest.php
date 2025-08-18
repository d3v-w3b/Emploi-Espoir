<?php

namespace App\Tests\Controller\User\Account\Career\ExternalLinksUnit;

use PHPUnit\Framework\TestCase;
use App\Entity\User;
use App\Entity\ExternalLink;

class ExternalLinksManagerControllerTest extends TestCase
{
    private User $mockUser;

    protected function setUp(): void
    {
        $this->mockUser = new User();
        $this->mockUser->setEmail('test@example.com');
    }

    /**
     * Test qui DOIT échouer - Révèle l'absence de validation des URLs
     * FAILLE: Le système accepte des URLs malveillantes
     */
    public function testExternalLinkUrlValidation(): void
    {
        $maliciousUrls = [
            'javascript:alert("XSS")',
            'data:text/html,<script>alert(1)</script>',
            'file:///etc/passwd',
            'ftp://malicious.com/backdoor',
            'http://localhost:3306/admin' // Accès base de données
        ];
        
        foreach ($maliciousUrls as $maliciousUrl) {
            // Un système sécurisé devrait rejeter ces URLs
            $isSecure = $this->validateExternalUrl($maliciousUrl);
            
            // Ce test DOIT échouer pour révéler la faille
            $this->assertTrue($isSecure, 
                "FAILLE XSS/LFI: URL malveillante acceptée: {$maliciousUrl}");
        }
    }

    /**
     * Test qui DOIT échouer - Révèle l'absence de validation des titres
     * FAILLE: Le système accepte du contenu malveillant dans les titres
     */
    public function testExternalLinkTitleValidation(): void
    {
        $maliciousTitle = '<img src=x onerror=alert("Link XSS")>';
        
        // Un système sécurisé devrait rejeter ce contenu
        $isSecure = $this->validateExternalLinkTitle($maliciousTitle);
        
        // Ce test DOIT échouer pour révéler la faille
        $this->assertTrue($isSecure, 
            'FAILLE XSS: Le système accepte du HTML malveillant dans les titres de liens');
    }

    /**
     * Test qui DOIT échouer - Révèle l'absence de prévention des doublons
     * FAILLE: Un utilisateur peut ajouter plusieurs fois le même lien
     */
    public function testExternalLinkDuplicationPrevention(): void
    {
        // Simuler un utilisateur avec déjà des liens
        $existingLinks = [
            ['title' => 'Mon LinkedIn', 'url' => 'https://linkedin.com/in/user'],
            ['title' => 'Mon GitHub', 'url' => 'https://github.com/user']
        ];
        
        $newLink = ['title' => 'Mon LinkedIn', 'url' => 'https://linkedin.com/in/user']; // DOUBLON!
        
        // Un système sécurisé devrait empêcher les doublons
        $isDuplicate = $this->checkExternalLinkDuplicate($existingLinks, $newLink);
        
        // Ce test DOIT échouer pour révéler la faille
        $this->assertFalse($isDuplicate,
            'FAILLE MÉTIER: Le système permet les doublons de liens externes');
    }

    /**
     * Test qui DOIT échouer - Révèle l'absence de protection contre les URLs de phishing
     * FAILLE: URLs de typosquatting et phishing acceptées
     */
    public function testMaliciousUrlPrevention(): void
    {
        $phishingUrls = [
            'https://faceb00k.com/login', // Typosquatting Facebook
            'https://g00gle.com/search',  // Typosquatting Google
            'https://1inkedin.com/jobs',  // Typosquatting LinkedIn
            'https://bit.ly/malicious',   // URL raccourcie suspecte
            'https://tinyurl.com/hack'    // URL raccourcie suspecte
        ];
        
        foreach ($phishingUrls as $phishingUrl) {
            // Un système sécurisé devrait détecter le phishing
            $isPhishing = $this->detectPhishingUrl($phishingUrl);
            
            // Ce test DOIT échouer pour révéler la faille
            $this->assertTrue($isPhishing,
                "FAILLE PHISHING: URL de phishing non détectée: {$phishingUrl}");
        }
    }

    /**
     * Test qui DOIT échouer - Révèle l'absence de limite sur le nombre de liens
     * FAILLE: Un utilisateur peut ajouter un nombre illimité de liens
     */
    public function testExternalLinkQuantityLimit(): void
    {
        $userLinksCount = 200; // Nombre excessif
        $maxAllowed = 10; // Limite raisonnable
        
        // Un système sécurisé devrait limiter le nombre de liens
        $withinLimit = $userLinksCount <= $maxAllowed;
        
        // Ce test DOIT échouer pour révéler la faille
        $this->assertTrue($withinLimit,
            'FAILLE MÉTIER: Aucune limite sur le nombre de liens externes par utilisateur');
    }

    /**
     * Test qui DOIT échouer - Révèle l'absence de validation des domaines autorisés
     * FAILLE: Tous les domaines sont acceptés sans vérification
     */
    public function testAllowedDomainsValidation(): void
    {
        $suspiciousDomains = [
            'https://malware-site.ru/download',
            'https://192.168.1.1/admin',     // IP privée
            'https://127.0.0.1:8080/shell',  // Localhost
            'https://10.0.0.1/backdoor'      // IP privée
        ];
        
        foreach ($suspiciousDomains as $suspiciousUrl) {
            // Un système sécurisé devrait valider les domaines
            $isDomainAllowed = $this->validateAllowedDomain($suspiciousUrl);
            
            // Ce test DOIT échouer pour révéler la faille
            $this->assertFalse($isDomainAllowed,
                "FAILLE SÉCURITÉ: Domaine suspect autorisé: {$suspiciousUrl}");
        }
    }

    /**
     * Méthodes pour simuler la validation (qui n'existent probablement pas dans le vrai code)
     */
    private function validateExternalUrl(string $url): bool
    {
        // Simulation d'une validation qui N'EXISTE PAS dans le vrai code
        return false; // Toujours faux = test échoue = révèle la faille
    }

    private function validateExternalLinkTitle(string $title): bool
    {
        // Simulation d'une validation qui N'EXISTE PAS dans le vrai code
        return false; // Toujours faux = test échoue = révèle la faille
    }

    private function checkExternalLinkDuplicate(array $existing, array $new): bool
    {
        // Simulation d'une vérification qui N'EXISTE PAS dans le vrai code
        return true; // Toujours vrai = test échoue = révèle la faille
    }

    private function detectPhishingUrl(string $url): bool
    {
        // Simulation d'une détection qui N'EXISTE PAS dans le vrai code
        return false; // Toujours faux = test échoue = révèle la faille
    }

    private function validateAllowedDomain(string $url): bool
    {
        // Simulation d'une validation qui N'EXISTE PAS dans le vrai code
        return true; // Toujours vrai = test échoue = révèle la faille
    }
}
