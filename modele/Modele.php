<?php

    abstract class Modele
    {
        abstract public function informations() : array;

        public function __construct(string $json) { $this->depuis_json($json); }    // Construction depuis un texte JSON
        
        static protected function _formater_ignorer(array &$ignorer)
        {
            foreach ($ignorer as $k => $i)                                               // On inverse les valeurs et les clés
            { $ignorer[$i] = $k; unset($ignorer[$k]); }                                  // On retrouvera facilement la valeur avec isset
        }
        public function depuis_tableau(?array $data, bool $effacer = true, array $ignorer = []) : bool
        {
            foreach ($data as &$v)                                                  // Encodage en UTF-8
                if (is_string($v))  $v = utf8_encode($v);
            $infos = $this->informations();                                         // Chargement des informations du modèle
            $reussi = false;
            self::_formater_ignorer($ignorer);
            foreach ($infos as $info) 
            {
                if (isset($ignorer[$info])) continue;                                    // On doit ignorer la valeur
                $reussi = isset($data[$info]);                                           // Recherche dans le JSON
                if (!$reussi)                                                            // Si on n'a pas de valeur
                {
                    if (!$effacer) continue;                                                 // Et si on ne doit pas effacer, on continue
                    $data[$info] = null;
                }
                try { $this->{'modifier_' . $info}($data[$info]); }                      // Appel de la méthode de modfication de l'attribut
                catch (\Error $e)                                                        // La méthode n'existe pas
                { $this->{'_' . $info} = $data[$info]; }
            }
            return $reussi;
        }
        public function vider(array $ignorer = []) { $this->depuis_tableau([], true, $ignorer); }

        public function json(bool $inclure_null = true, array $ignorer = []) : string
        {                                                                                // Transformation en texte JSON
            $infos = $this->informations();
            $this->_formater_ignorer($ignorer);
            $_data = [];
            foreach ($infos as $info)
            {
                if (isset($ignorer[$info])) continue;                                    // On doit ignorer la valeur
                $val = $this->{(string)$info}();                                         // On récupère la valeur depuis la fonction <nom>()
                if ($val === null && !$inclure_null) continue;                           // La valeur est nulle et on ne doit pas l'inclure
                $_data[$info] = $val;                                                    // Sinon on ajoute au tableau
            }
            return json_encode($_data);                                                  // On transforme le tableau en texte JSON
        }
        public function depuis_json(string $json, bool $effacer = true, array $ignorer = []) : bool
        {                                                                           // Charge les informations du modèle depuis un texte JSON
            $json = json_decode($json, true);                                       // Transformation en tableau associatif
            return $this->depuis_tableau($json, $effacer, $ignorer);
        }

        public function depuis_ini(string $fichier_ini = null, bool $effacer = true, array $ignorer = []) : ?array
        {
            if (!($ini = parse_ini_file($fichier_ini)))
                throw new Exception("Impossible d\'initialiser le modèle depuis le fichier \"$fichier_ini.");
            $this->depuis_tableau($ini, $effacer, $ignorer);
            return $ini;
        }

    }

?>