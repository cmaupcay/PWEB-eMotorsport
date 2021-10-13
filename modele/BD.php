<?php

    class BD
    {
        private const INI = 'modele/bd.ini';

        private $_pdo;
        public function __construct(?string $fichier_ini = null)
        {
            if ($fichier_ini === null) 
                $fichier_ini = self::INI;
            if (!($ini = parse_ini_file($fichier_ini, true)))
                throw new Exception('Impossible d\'initialiser la base de données.');
            $this->_pdo = new PDO(
                $ini['driver'] .
                ':host=' . $ini['domaine'] .
                (isset($ini['port']) ? ';port=' . $ini['port'] : '') .
                ';dbanme=' . $ini['base'],
                $ini['id'], $ini['mdp']
            );
        }
    }

?>