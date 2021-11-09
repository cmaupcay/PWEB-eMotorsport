<?php
    namespace Loueur;
    require_once 'controleur/Controleur.php';
    require_once 'modele/Reduction.php';
    require_once 'modele/Utilisateur.php';
    require_once 'modele/Vehicule.php';

    class URI_Facturation extends \Controleur
    {
        const REDUCTIONS = ['duree', 'quantite'];
        private $_reductions;
        public function __construct()
        {
            foreach (self::REDUCTIONS as $r)
                $this->_reductions[$r] = new \Reduction($r);   
        }

        public function calculer_valeur(\Facture $facture) : float
        {
            $valeur = $facture->valeur() ?? 0;
            if ($valeur > 0)
            {
                if ( $facture->date_f() === null ||
                    (($facture->DT_date_f()->getTimestamp() - $facture->DT_date_d()->getTimestamp()) >= ($this->_reductions['duree']->condition() * 86400))
                ) $valeur -= ($valeur * $this->_reductions['duree']->valeur());
            }
            return $valeur;
        }

        public function executer(array &$server, array &$session, array &$post, array &$get, array &$params, \BD &$_BD, \Authentification &$_AUTH, \Routeur &$_ROUTEUR, ?\JetonAuthentification &$_JETON = null)
        {
            if (isset($params[URI]))
            {
                if (count($params[URI]) === 1 && $idu = (new \Utilisateur($params[URI][0], $_BD))->id() !== null
                    && (count(($factures = (new \Facture())->selection($_BD, null, '(date_f IS NULL OR date_f >= CURRENT_DATE) AND idu = ' . $params[URI][0]))) !== 0))
                {
                    $params[FACTURE][NON_REGLEE][TOTAL] = 0;
                    $params[FACTURE][REGLEE][TOTAL] = 0;
                    foreach ($factures as &$f)
                    {
                        $f->modifier_valeur($this->calculer_valeur($f));
                        if ($f->etat_r())
                        {
                            $params[FACTURE][REGLEE][] = $f;
                            $params[FACTURE][REGLEE][TOTAL] += $f->valeur();
                        }
                        else 
                        {
                            $params[FACTURE][NON_REGLEE][] = $f;
                            $params[FACTURE][NON_REGLEE][TOTAL] += $f->valeur();
                        }
                    }
                    // Réductions
                    if (count($params[FACTURE][NON_REGLEE]) > $this->_reductions['quantite']->condition())
                        $params[FACTURE][NON_REGLEE][TOTAL] -= ($params[FACTURE][NON_REGLEE][TOTAL] * $this->_reductions['quantite']->valeur());
                    if (count($params[FACTURE][REGLEE]) > $this->_reductions['quantite']->condition())
                        $params[FACTURE][REGLEE][TOTAL] -= ($params[FACTURE][REGLEE][TOTAL] * $this->_reductions['quantite']->valeur());
                    return;
                }
                $_ROUTEUR->redirection('loueur/facture');
            } // Afficher les totaux du mois pour chaque entreprise
            else
            {
                $factures = (new \Facture())->selection($_BD, null,
                'date_f IS NULL OR (date_d >= DATE_SUB(CURRENT_DATE, INTERVAL 31 DAY) OR date_f < DATE_ADD(CURRENT_DATE, INTERVAL 31 DAY)) ORDER BY id DESC');
                if (count($factures) > 0)
                {
                    foreach ($factures as $f)
                    {
                        if (!isset($params[UTILISATEUR][$f->idu()]))
                        {
                            $params[UTILISATEUR][$f->idu()][UTILISATEUR] = new \Utilisateur($f->idu(), $_BD);
                            $params[UTILISATEUR][$f->idu()][TOTAL] = $this->calculer_valeur($f);
                        }
                        else $params[UTILISATEUR][$f->idu()][TOTAL] += $this->calculer_valeur($f);
                    }
                    $params[UTILISATEUR][TOTAL] = 0;
                    foreach ($params[UTILISATEUR] as $i => &$u)
                    {
                        if ($i === TOTAL) continue;
                        if ((count($u) - 1) > $this->_reductions['quantite']->condition())
                            $u[TOTAL] -= ($u[TOTAL] * $this->_reductions['quantite']->valeur());
                        $params[UTILISATEUR][TOTAL] += $u[TOTAL];
                    }
                } else $params[CTRL_MESSAGE] = 'Aucune facture sur le mois courant.';
            }
        }
    }
?>