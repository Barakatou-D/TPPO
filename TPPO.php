<?php
// ==============================
// TP POO PHP - Gestion Utilisateurs
// Réalisé par : Barah
// ==============================


// ======================
// INTERFACES
// ======================

interface Authentifiable {
    public function seConnecter();
}

interface Affichable {
    public function afficher();
}


// ======================
// CLASSE PERSONNE
// ======================

class Personne {
    private $id;
    private $nom;
    private $email;

    public function __construct($id, $nom, $email) {
        $this->id = $id;
        $this->nom = $nom;
        $this->email = $email;
    }

    public function getId() { return $this->id; }
    public function getNom() { return $this->nom; }
    public function getEmail() { return $this->email; }

    public function setNom($nom) { $this->nom = $nom; }
    public function setEmail($email) { $this->email = $email; }

    public function afficherInfos() {
        return "ID: $this->id | Nom: $this->nom | Email: $this->email";
    }
}


// ======================
// CLASSE UTILISATEUR (ABSTRAITE)
// ======================

abstract class Utilisateur extends Personne implements Authentifiable, Affichable {

    protected $login;
    protected $motDePasse;

    public static $nombreUtilisateurs = 0;

    public function __construct($id, $nom, $email, $login, $motDePasse) {
        parent::__construct($id, $nom, $email);
        $this->login = $login;
        $this->motDePasse = $motDePasse;

        self::$nombreUtilisateurs++;
    }

    public function seConnecter() {
        return "Connexion réussie pour l'utilisateur : $this->login";
    }

    public static function afficherNombre() {
        return "Total des utilisateurs créés : " . self::$nombreUtilisateurs;
    }

    abstract public function afficherRole();
}


// ======================
// CLASSE CLIENT
// ======================

class Client extends Utilisateur {

    private $typeClient;

    const TAUX_SIMPLE = 0.05;
    const TAUX_PREMIUM = 0.15;

    public function __construct($id, $nom, $email, $login, $motDePasse, $typeClient) {
        parent::__construct($id, $nom, $email, $login, $motDePasse);
        $this->typeClient = $typeClient;
    }

    public function calculerReduction($montant) {
        if ($this->typeClient == "premium") {
            return $montant * self::TAUX_PREMIUM;
        } else {
            return $montant * self::TAUX_SIMPLE;
        }
    }

    public function afficherInfos() {
        return parent::afficherInfos() . " | Type client : $this->typeClient";
    }

    public function afficherRole() {
        return "Rôle : Client de l'application";
    }

    public function afficher() {
        return $this->afficherInfos();
    }
}


// ======================
// CLASSE EMPLOYE
// ======================

class Employe extends Utilisateur {

    private $salaire;

    public function __construct($id, $nom, $email, $login, $motDePasse, $salaire) {
        parent::__construct($id, $nom, $email, $login, $motDePasse);
        $this->salaire = $salaire;
    }

    public function calculerSalaireAnnuel() {
        return $this->salaire * 12;
    }

    public function afficherRole() {
        return "Rôle : Employé de l'entreprise";
    }

    public function afficher() {
        return $this->afficherInfos();
    }
}


// ======================
// CLASSE ADMINISTRATEUR
// ======================

class Administrateur extends Utilisateur {

    public function supprimerUtilisateur($user) {
        return "Un utilisateur a été supprimé par l'administrateur";
    }

    public function afficherRole() {
        return "Rôle : Administrateur système";
    }

    public function afficher() {
        return $this->afficherInfos();
    }
}


// ======================
// POLYMORPHISME
// ======================

function afficherUtilisateur(Affichable $u) {
    echo $u->afficher() . "<br>";
}


// ======================
// TESTS
// ======================

$client1 = new Client(1, "Fatou", "fatou@gmail.com", "fatou01", "pass123", "premium");
$employe1 = new Employe(2, "Ibrahima", "ibra@gmail.com", "ibra01", "pass456", 180000);
$admin1 = new Administrateur(3, "Cheikh", "cheikh@gmail.com", "admin01", "adminpass");

echo "<h3>=== TEST CLIENT ===</h3>";
echo $client1->afficher() . "<br>";
echo $client1->seConnecter() . "<br>";
echo "Réduction sur 10 000 : " . $client1->calculerReduction(10000) . "<br>";

echo "<h3>=== TEST EMPLOYE ===</h3>";
echo $employe1->afficher() . "<br>";
echo "Salaire annuel : " . $employe1->calculerSalaireAnnuel() . "<br>";

echo "<h3>=== TEST ADMIN ===</h3>";
echo $admin1->afficher() . "<br>";
echo $admin1->supprimerUtilisateur($client1) . "<br>";

echo "<h3>=== POLYMORPHISME ===</h3>";
afficherUtilisateur($client1);
afficherUtilisateur($employe1);
afficherUtilisateur($admin1);

echo "<h3>=== STATIQUE ===</h3>";
echo Utilisateur::afficherNombre();

?>