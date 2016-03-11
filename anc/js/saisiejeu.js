
function Change(id,montype)
{
   var monSpan = document.getElementById("v"+id);
   var monText = document.getElementById("t"+id);

   if (monText.value=='0')
   {
        monText.value=1;
        if (montype=='P')
            monSpan.className="choixs";
        else if (montype=='R')
            monSpan.className="resultats";
   }
   else
   {
        monText.value=0;
        var posLettreC = id.indexOf("c");
        var numColonne = id.substr(posLettreC+1,1);
        if (numColonne == 1)
        {
            if (montype=='P')
                monSpan.className="choix1";
            else if (montype=='R')
                monSpan.className="resultat1";
        }
        if (numColonne == 2)
        {
            if (montype=='P')
                monSpan.className="choixn";
            else if (montype=='R')
                monSpan.className="resultatn";
        }
        if (numColonne == 3)
        {
            if (montype=='P')
                monSpan.className="choix2";
            else if (montype=='R')
                monSpan.className="resultat2";
        }
   }
}
function aleatoire(N) 
{
	return (Math.floor((N)*Math.random()+1));
}
function valide(champs)
{
    var monSpan = document.getElementById("v"+champs);
    monSpan.className="choixs";
    var monText = document.getElementById("t"+champs);
    monText.value=1;
}
function invalide(champs)
{
    var monSpan = document.getElementById("v"+champs);
    var posLettreC = champs.indexOf("c");
    var numColonne = champs.substr(posLettreC+1,1);

    if (numColonne == 1)
    {
        monSpan.className="choix1";
    }
    if (numColonne == 2)
    {
        monSpan.className="choixn";
    }
    if (numColonne == 3)
    {
        monSpan.className="choix2";
    }
    var monText = document.getElementById("t"+champs);
    monText.value=0;
}
function jeuFlash(nombreMatchs)
{
  for ( var i=1; i<=nombreMatchs; i++) 
  {
      nombre = aleatoire(3);
      if (nombre == 1)
      {
        valide("l"+i+"c1");          
        invalide("l"+i+"c2");    
        invalide("l"+i+"c3");
      }
      if (nombre == 2)
      {
        invalide("l"+i+"c1");          
        valide("l"+i+"c2");    
        invalide("l"+i+"c3");
      }       
      if (nombre == 3)
      {
        invalide("l"+i+"c1");          
        invalide("l"+i+"c2");    
        valide("l"+i+"c3");
      }
  }
  for ( var i=nombreMatchs+1; i<=15; i++) 
  {
    invalide("l"+i+"c1");          
    invalide("l"+i+"c2");    
    invalide("l"+i+"c3");
  }
}
function efface()
{
  for ( var i=1; i<=15; i++) 
  {
    invalide("l"+i+"c1");          
    invalide("l"+i+"c2");    
    invalide("l"+i+"c3");
  }
}
