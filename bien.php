<?php
namespace location\dao;
require('proprietaire.php');
use \PDO;
use \location\dao as Prop;
    class Bien
    {
        var $nom;
        var $adresse;
        var $montantLoc;
        var $commission;
        var $etat;
        var $idTypeBien;
        private $idProprietaire;
        private $proprietaire;
        private $bdd;

        private function getConnexion()
        {
            try
            {
                if($this->bdd == null)
                {
                    $this->bdd = new PDO('mysql:host=;dbname=BDLocation;charset=utf8', 'root', '@umones',
                    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
                }
            }
            catch(Exception $e)
            {
                die('Erreur : ' . $e->getMessage());
            }
        }
        public function add($propCNI, $propNom, $propTel)   //-----------------------------------------------------Ajouter un bien
        {
            $this->getConnexion();
            $this->proprietaire = new Prop\Proprietaire;
            $verifs = $this->proprietaire->find($propCNI);
            $exist=false;
            while($verif = $verifs->fetch())
            {
                $exist=true;
                $this->idProprietaire = $verif['idProprietaire'];
            }
            if($exist) // Si le proprietaire de cette existe on ajoute le bien
            {
                $sql = "INSERT INTO BIEN VALUES(null,:nom, :adresse, :montantLoc, :commission, :etat, :idTypeBien, :idProprietaire)";
                $req = $this->bdd->prepare($sql);
                $data = $req->execute(array(
                    'nom'=>$this->nom,
                    'adresse'=>$this->adresse,
                    'montantLoc'=>$this->montantLoc,
                    'commission'=>$this->commission,
                    'etat'=>'0',
                    'idTypeBien'=>$this->idTypeBien,
                    'idProprietaire'=>$this->idProprietaire
                ));
                return $data;
            }
            else    // Si le proprietaire n'existe pas alors on le crée et on reverifie s'il existe apres on ajoute le bien
            {
                $this->proprietaire->add($propCNI, $propNom, $propTel);
                $reverifs = $this->proprietaire->find($propCNI);
                while($reverif = $reverifs->fetch())
                {
                    $this->idProprietaire = $verif['idProprietaire'];
                }
                $sql = "INSERT INTO BIEN VALUES(null,:nom, :adresse, :montantLoc, :commission, :etat, :idTypeBien, :idProprietaire)";
                $req = $this->bdd->prepare($sql);
                $data = $req->execute(array(
                    'nom'=>$this->nom,
                    'adresse'=>$this->adresse,
                    'montantLoc'=>$this->montantLoc,
                    'commission'=>$this->commission,
                    'etat'=>'0',
                    'idTypeBien'=>$this->idTypeBien,
                    'idProprietaire'=>$this->idProprietaire
                ));
                return $data;
            }
        }
        public function update($idProp)   //-----------------------------------------------------Modifier un bien
        {
            $sql = "UPDATE BIEN SET nom = ':nom', adresse = ':adresse', montantLoc = :'montantLoc', commssion = ':commission', etat = ':etat', idTypeBien = ':typeBien' WHERE idProprietaire = '".$idProp."' ";
            $dreq = $this->bdd->prepare($sql);
            $data = $req->execute(array(
                    'nom'=>$this->nom,
                    'adresse'=>$this->adresse,
                    'montantLoc'=>$this->montantLoc,
                    'commission'=>$this->commission,
                    'etat'=>'0',
                    'idTypeBien'=>$this->idTypeBien,
                    'idProprietaire'=>$this->idProprietaire
                ));
            return $data;
        }
        public function find($idLeProp) //-----------------------------------------------------------Trouver un bien
        {
            $this->getConnexion();
            $sql = "SELECT * FROM BIEN WHERE idProprietaire = '".$idLeProp."'";
            $donnees = $this->bdd->query($sql);
            return $donnees;
        }
        public function list()  //-----------------------------------------------------------Lister tout les biens
        {
            $this->getConnexion();
            $sql = "SELECT * FROM BIEN";
            $donnees = $this->bdd->query($sql);
            return $donnees;
        }
        public function listBytype($idLeType)  //-----------------------------------------------------------Lister tout les biens par type
        {
            $this->getConnexion();
            $sql = "SELECT * FROM BIEN WHERE idTypeBien = '".$idLeType."'";
            $donnees = $this->bdd->query($sql);
            return $donnees;
        }
        public function listByEtat($l_Etat)  //-----------------------------------------------------------Lister tout les biens par etat
        {
            $this->getConnexion();
            $sql = "SELECT * FROM BIEN WHERE etat = '".$l_Etat."'";
            $donnees = $this->bdd->query($sql);
            return $donnees;
        }
    }
?>