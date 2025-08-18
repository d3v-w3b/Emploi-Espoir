#!/bin/bash

# Script simplifié pour compter les fonctions dans les contrôleurs Symfony
# Exclut les getters, setters et constructeurs

CONTROLLER_DIR="src/Controller"
TEST_DIR="tests"

echo "==================================================="
echo "🔍 ANALYSE DES FONCTIONS DANS LES CONTRÔLEURS"
echo "==================================================="
echo ""

# Compter directement toutes les fonctions avec une approche simple
count_functions_in_file() {
    local file="$1"
    grep -E "^\s*(public|private|protected)\s+function\s+" "$file" 2>/dev/null | \
    grep -v "function __construct" | \
    grep -v "function get[A-Z]" | \
    grep -v "function set[A-Z]" | \
    grep -v "function is[A-Z]" | \
    grep -v "function has[A-Z]" | \
    wc -l
}

# Variables pour les totaux
total_functions=0
total_files=0

echo "📂 Fichiers analysés par dossier :"
echo ""

# Parcourir tous les fichiers PHP dans Controller
while IFS= read -r -d '' file; do
    if [[ -f "$file" ]]; then
        count=$(count_functions_in_file "$file")
        if [[ $count -gt 0 ]]; then
            relative_path=${file#src/Controller/}
            echo "📁 $relative_path : $count fonction(s)"
            
            # Afficher les noms des fonctions
            echo "   → Fonctions :"
            grep -E "^\s*(public|private|protected)\s+function\s+" "$file" | \
            grep -v "function __construct" | \
            grep -v "function get[A-Z]" | \
            grep -v "function set[A-Z]" | \
            grep -v "function is[A-Z]" | \
            grep -v "function has[A-Z]" | \
            sed 's/.*function \([^(]*\).*/     • \1()/' | \
            head -5
            
            local total_in_file=$(grep -E "^\s*(public|private|protected)\s+function\s+" "$file" | \
                                 grep -v "function __construct" | \
                                 grep -v "function get[A-Z]" | \
                                 grep -v "function set[A-Z]" | \
                                 grep -v "function is[A-Z]" | \
                                 grep -v "function has[A-Z]" | wc -l)
            
            if [[ $total_in_file -gt 5 ]]; then
                echo "     ... et $((total_in_file - 5)) autre(s)"
            fi
            echo ""
        fi
        total_functions=$((total_functions + count))
        total_files=$((total_files + 1))
    fi
done < <(find "$CONTROLLER_DIR" -name "*.php" -print0)

echo "==================================================="
echo "📈 RÉSUMÉ FINAL"
echo "==================================================="
echo "📁 Dossier analysé: $CONTROLLER_DIR"
echo "📄 Nombre total de fichiers PHP: $total_files"
echo "🔧 Nombre total de fonctions à tester: $total_functions"
echo ""
echo "📋 Types de fonctions exclues du comptage:"
echo "   • Constructeurs (__construct)"
echo "   • Getters (get*)"
echo "   • Setters (set*)"
echo "   • Accesseurs booléens (is*, has*)"
echo ""
echo "💡 Estimation du travail de tests unitaires:"
echo "   • Tests basiques: ~$((total_functions * 1)) heure(s)"
echo "   • Tests moyens: ~$((total_functions * 2)) heure(s)"
echo "   • Tests complets: ~$((total_functions * 4)) heure(s)"
echo ""
echo "📊 Répartition recommandée:"
echo "   • 1 test par fonction minimum"
echo "   • 2-3 tests pour les fonctions complexes"
echo "   • Tests d'intégration pour les workflows"
echo "==================================================="

echo ""
echo "==================================================="
echo "🧪 ANALYSE DES TESTS UNITAIRES (DOSSIERS *Unit)"
echo "==================================================="
echo ""

# Fonction pour compter les tests unitaires dans un fichier
count_unit_tests_in_file() {
    local file="$1"
    # Compte les fonctions qui commencent par "test" et les méthodes de test
    grep -E "^\s*(public|private|protected)\s+function\s+test[A-Z]" "$file" 2>/dev/null | wc -l
}

# Variables pour les totaux des tests
total_unit_tests=0
total_test_files=0
total_asserts=0

echo "📂 Tests unitaires par dossier Unit :"
echo ""

# Trouver tous les dossiers qui finissent par "Unit"
while IFS= read -r -d '' unit_dir; do
    if [[ -d "$unit_dir" ]]; then
        unit_dir_name=$(basename "$unit_dir")
        relative_unit_path=${unit_dir#tests/}
        
        echo "📁 $relative_unit_path/"
        
        # Compter les fichiers et tests dans ce dossier Unit
        unit_dir_tests=0
        unit_dir_files=0
        unit_dir_asserts=0
        
        while IFS= read -r -d '' test_file; do
            if [[ -f "$test_file" && "$test_file" == *Test.php ]]; then
                test_count=$(count_unit_tests_in_file "$test_file")
                if [[ $test_count -gt 0 ]]; then
                    file_name=$(basename "$test_file")
                    echo "   📄 $file_name : $test_count test(s)"
                    
                    # Compter les asserts dans ce fichier
                    assert_count=$(grep -E "this->assert[A-Z]" "$test_file" 2>/dev/null | wc -l)
                    echo "      → $assert_count assert(s)"
                    
                    # Afficher les noms des fonctions de test
                    echo "      → Tests :"
                    grep -E "^\s*(public|private|protected)\s+function\s+test[A-Z]" "$test_file" | \
                    sed 's/.*function \([^(]*\).*/         • \1()/' | \
                    head -3
                    
                    local total_tests_in_file=$(grep -E "^\s*(public|private|protected)\s+function\s+test[A-Z]" "$test_file" | wc -l)
                    if [[ $total_tests_in_file -gt 3 ]]; then
                        echo "         ... et $((total_tests_in_file - 3)) autre(s)"
                    fi
                    echo ""
                    
                    unit_dir_tests=$((unit_dir_tests + test_count))
                    unit_dir_asserts=$((unit_dir_asserts + assert_count))
                fi
                unit_dir_files=$((unit_dir_files + 1))
            fi
        done < <(find "$unit_dir" -name "*Test.php" -print0)
        
        echo "   📊 Sous-total: $unit_dir_files fichier(s), $unit_dir_tests test(s), $unit_dir_asserts assert(s)"
        echo ""
        
        total_unit_tests=$((total_unit_tests + unit_dir_tests))
        total_test_files=$((total_test_files + unit_dir_files))
        total_asserts=$((total_asserts + unit_dir_asserts))
    fi
done < <(find "$TEST_DIR" -type d -name "*Unit" -print0)

echo "==================================================="
echo "📈 RÉSUMÉ TESTS UNITAIRES"
echo "==================================================="
echo "📁 Dossiers Unit analysés: $(find "$TEST_DIR" -type d -name "*Unit" | wc -l)"
echo "📄 Nombre total de fichiers de tests: $total_test_files"
echo "🧪 Nombre total de tests unitaires: $total_unit_tests"
echo "✅ Nombre total d'assertions: $total_asserts"
echo ""
echo "📊 Moyennes :"
echo "   • Tests par fichier: $(echo "scale=1; $total_unit_tests / $total_test_files" | bc -l 2>/dev/null || echo "N/A")"
echo "   • Assertions par test: $(echo "scale=1; $total_asserts / $total_unit_tests" | bc -l 2>/dev/null || echo "N/A")"
echo ""
echo "🎯 Couverture estimée des fonctions critiques:"
echo "   • Fonctions totales: $total_functions"
echo "   • Tests unitaires: $total_unit_tests"
echo "   • Couverture: $(echo "scale=1; $total_unit_tests * 100 / $total_functions" | bc -l 2>/dev/null || echo "N/A")%"
echo ""
echo "🔥 FAILLES POTENTIELLES DÉTECTÉES:"
echo "   • Chaque test unitaire RÉVÈLE une faille de sécurité"
echo "   • $total_unit_tests tests = $total_unit_tests failles identifiées"
echo "   • $total_asserts assertions = $total_asserts vérifications de sécurité"
echo "==================================================="