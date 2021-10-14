<?php

    class BD
    {
        private const INI = 'modele/bd.ini';
        const ACCEPTER_HORS_LIGNE = true;
        
        private $_pdo;
        public function __construct(?string $fichier_ini = null)
        {
            if ($fichier_ini === null) 
                $fichier_ini = self::INI;
            if (!($ini = parse_ini_file($fichier_ini, true)))
                throw new Exception('Impossible d\'initialiser la base de données.');
            try 
            {
                $this->_pdo = new PDO(
                    $ini['driver'] .
                    ':host=' . $ini['domaine'] .
                    (isset($ini['port']) ? ';port=' . $ini['port'] : '') .
                    ';dbanme=' . $ini['base'],
                    $ini['id'], $ini['mdp']
                );
            } catch (\PDOException $err) {
                print("<p class=\"erreur\">La base de données est injoignable.</p>\n");
                if (!self::ACCEPTER_HORS_LIGNE) throw $err;
            }
        }
    }

?>