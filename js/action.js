function ouverture(id, classe_o, classe_f)
{
    objet = document.getElementById(id);
    if (objet.classList.contains(classe_o))
    {
        objet.classList.remove(classe_o);
        objet.classList.add(classe_f);
    }
    else if (objet.classList.contains(classe_f))
    {
        objet.classList.remove(classe_f);
        objet.classList.add(classe_o);
    }
}

function obscursir(id)
{
    objet = document.getElementById(id);
    if (objet.classList.contains("obscur"))
        objet.classList.remove("obscur");
    else objet.classList.add("obscur");
}

function ouvrir_navigation()
{
    ouverture('actions', 'nav-fermee', 'nav-ouverte');
    ouverture('navigation', 'ouvert', 'ferme');
    obscursir('fond');
    obscursir('page');
}

function desactiver(objet)
{
    if (objet.classList.contains('active'))
        objet.classList.remove('active');
    else
        objet.classList.add('active');
}
function ouvrir_sous_menu(id)
{ 
    bouton = document.getElementById(id);
    desactiver(bouton);
    sous_menus = document.getElementsByClassName('sous-menu');
    sous_menus.forEach(sous_menu => {
        if (sous_menu.id != 'sm-' + id && sous_menu.classList.contains('ouvert-v'))
        {
            sous_menu.classList.remove('ouvert-v');
            sous_menu.classList.add('ferme-v');
            desactiver(document.getElementById((sous_menu.id.replace('sm-', ''))));
        }
    });
    ouverture('sm-' + id, 'ouvert-v', 'ferme-v');
}

function ouverture_acces_rapide()
{ 
    ouverture('acces-rapides', 'plus', 'moins');
    ouverture('plus', 'plus', 'moins');
}