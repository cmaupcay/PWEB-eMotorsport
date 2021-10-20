<?php
    include_once 'modele/ModeleBD.php';

    class Cookie extends ModeleBD
    {
        public function informations(): array
        { return ['id', 'idu', 'ip']; }
        public function table(): string { return 'cookie'; }
        public function composant(): string { return ''; }

        private $_idu;
        public function idu() : ?int { return $this->_idu; }
        public function modifier_idu(?int $valeur) { $this->_idu = $valeur; }
        private $_ip;
        public function ip() : ?string { return $this->_ip; }
        public function modifier_ip(?string $valeur) 
        {
            if (filter_var($valeur, FILTER_VALIDATE_IP))
                $this->_ip = $valeur; 
        }

        static public function ecrire(BD &$bd, int $idu, string $ip, string $nom, int $survie_h = 1, string $chemin = '/') : ?int
        {
            ($c = new Cookie())->depuis_tableau(['idu' => $idu, 'ip' => $ip]);
            if ($c->envoyer($bd, ['id']))
            {
                if ($c->recevoir($bd, 'ip') && setcookie($nom, $c->id(), time() + ($survie_h * 3600), $chemin))
                    return $c->id();
                else $c->supprimer($bd, true, 'ip');
            }
            return null;
        }
        static public function lire(BD &$bd, int $id, string $ip) : ?int
        {
            try { $c = new Cookie($id, $bd); }
            catch (\Exception $e) { return null; }
            if ($c->ip() == $ip)
                return $c->idu();
            return null;
        }
        static public function effacer(BD &$bd, string $ip, string $nom, string $chemin = '/') : bool
        { 
            $reussi = false;
            try 
            {
                ($c = new Cookie())->depuis_tableau(['ip' => $ip]);
                $reussi = $c->supprimer($bd, true, 'ip'); 
            } 
            catch (\Exception $e) {}
            return setcookie($nom, 0, 1, $chemin) && $reussi;
        }
    }

?>