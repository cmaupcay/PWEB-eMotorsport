<?php
    include_once 'modele/ModeleBD.php';

    class Cookie extends ModeleBD
    {
        public function informations(): array
        { return ['id', 'u']; }
        public function table(): string { return 'cookie'; }
        public function composant(): string { return ''; }

        private $_u;
        public function u() : ?int { return $this->_u; }
        public function modifier_u(?int $valeur) { $this->_u = $valeur; }

        public function __construct(?int $id = null, ?BD &$bd = null)
        {
            if ($id === null && $bd === null)
                $this->regenerer_id();
            else
                parent::__construct($id, $bd);
        }

        public function regenerer_id() { $this->_id = rand(); }

        static public function ecrire(BD &$bd, int $u, string $nom, int $survie_h = 1, string $chemin = '/') : ?int
        {
            ($c = new Cookie())->modifier_u($u);
            while ($c->existe($bd))
                $c->regenerer_id();
            if ($c->envoyer($bd))
            {
                if (setcookie($nom, $c->id(), time() + ($survie_h * 3600), $chemin))
                    return $c->id();
                else $c->supprimer($bd);
            }
            return null;
        }
        static public function lire(BD &$bd, int $id) : ?int
        {
            try { $c = new Cookie($id, $bd); }
            catch (\Exception $e)
            { return null; }
            return $c->u();
        }
        static public function effacer(BD &$bd, int $id, string $nom, string $chemin = '/') : bool
        { 
            $reussi = false;
            try { $reussi = (new Cookie($id, $bd))->supprimer($bd); } 
            catch (\Exception $e) {}
            return setcookie($nom, 0, 1, $chemin) && $reussi;
        }
    }

?>