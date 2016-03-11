function verificationSaisie() 
{
	if (document.formconnexion.pseudo.value == '') 
	{
  		document.formconnexion.pseudo.focus();
  		return false;
 	}
 	else
		if (document.formconnexion.motpasse.value == '') 
		{
  			document.formconnexion.motpasse.focus();
  			return false;
 		}
 	return true;
}
