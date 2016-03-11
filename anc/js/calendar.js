/* --- Swazz Javascript Calendar ---
/* --- v 1.0 3rd November 2006
By Oliver Bryant
http://calendar.swazz.org */

function getObj(objID)
{
    if (document.getElementById) {return document.getElementById(objID);}
    else if (document.all) {return document.all[objID];}
    else if (document.layers) {return document.layers[objID];}
}

function checkClick(e) {
	e?evt=e:evt=event;
	CSE=evt.target?evt.target:evt.srcElement;
	if (getObj('calendar'))
		if (!isChild(CSE,getObj('calendar')))
			getObj('calendar').style.display='none';
}

function isChild(s,d) {
	while(s) {
		if (s==d) 
			return true;
		s=s.parentNode;
	}
	return false;
}

// Position gauche d'un objet : la seule soluce est d'additionner les positions de l'objet et des ces parents.
function Left(obj)
{
	var curleft = 0;
	if (obj.offsetParent)
	{
	  curleft = obj.offsetLeft;
		while (obj = obj.offsetParent)
		{
			curleft += obj.offsetLeft;
		}
	}
	else if (obj.x)
		curleft += obj.x;
	return curleft+2;
}

function Top(obj)
{
	var curtop = 0;
	if (obj.offsetParent)
	{
	  curtop = obj.offsetTop;
		while (obj = obj.offsetParent)
		{
			curtop += obj.offsetTop;
		}
	}
	else if (obj.y)
		curtop += obj.y;
	return curtop;
}
	
document.write('<table id="calendar" cellpadding=2>');
document.write('<tr><td class="imagecalendar" onclick="csubm()"><img src="img/arrowleftmonth.gif"></td><td colspan=5 id="mns" align="center" style="font:bold 13px Arial"></td><td align="right" onclick="caddm()"><img src="img/arrowrightmonth.gif"></td></tr>');
document.write('<tr class="titrecalendar"><td>L</td><td>M</td><td>Me</td><td>J</td><td>V</td><td>S</td><td>D</td></tr>');
for(var kk=1;kk<=6;kk++) {
	document.write('<tr>');
	for(var tt=1;tt<=7;tt++) {
		num=7 * (kk-1) - (-tt);
		document.write('<td id="v' + num + '" class="colonnecalendar">&nbsp;</td>');
	}
	document.write('</tr>');
}
document.write('</table>');

document.all?document.attachEvent('onclick',checkClick):document.addEventListener('click',checkClick,false);


// Calendar script
var now = new Date;
var sccm=now.getMonth();
var sccy=now.getFullYear();
// LC ! Ces deux lignes pour bloquer la flèche gauche jusqu'à une date donnée (ici sept. 2007)
sccm=7;
sccy=2007;
var ccm=now.getMonth();
var ccy=now.getFullYear();

var updobj;

function Remplace(expr,a,b) {
  var i=0
  while (i!=-1) {
     i=expr.indexOf(a,i);
     if (i>=0) {
        expr=expr.substring(0,i)+b+expr.substring(i+a.length);
        i+=b.length;
     }
  }
  return expr
}

function lcs(ielem) {

	updobj=ielem;
	
	// Positionnement du calendrier
	getObj('calendar').style.left=Left(ielem)+"px";;
	getObj('calendar').style.top=Top(ielem)+ielem.offsetHeight+"px";;
	getObj('calendar').style.display='block';

	// Vérification de la date
	curdt=ielem.value;
	// Si tiret, on remet des /
	curdt=Remplace(curdt,'-','/');
	// On fait un tableau avec les 3 chiffres de la date
	curdtarr=curdt.split('/');
	isdt=true;
	for(var k=0;k<curdtarr.length;k++) {
		if (isNaN(curdtarr[k]))
			isdt=false;
	}
	if (isdt&(curdtarr.length==3)) {
		ccm=curdtarr[1]-1;
		ccy=curdtarr[2];
		prepcalendar(curdtarr[0],curdtarr[1]-1,curdtarr[2]);
	}

}

function evtTgt(e)
{
	var el;
	if(e.target)el=e.target;
	else if(e.srcElement)el=e.srcElement;
	if(el.nodeType==3)el=el.parentNode; // defeat Safari bug
	return el;
}
function EvtObj(e){if(!e)e=window.event;return e;}
function cs_over(e) {
	evtTgt(EvtObj(e)).style.background='#FFCC66';
}
function cs_out(e) {
	evtTgt(EvtObj(e)).style.background='#C4D3EA';
}
function cs_click(e) {
	updobj.value=calvalarr[evtTgt(EvtObj(e)).id.substring(1,evtTgt(EvtObj(e)).id.length)];
	// On a choisi une date, on cache le tableau
	getObj('calendar').style.display='none';
	
}

var mn=new Array('JAN','FEV','MAR','AVR','MAI','JUIN','JUIL','AOUT','SEP','OCT','NOV','DEC');
var mnn=new Array('31','28','31','30','31','30','31','31','30','31','30','31');
var mnl=new Array('31','29','31','30','31','30','31','31','30','31','30','31');
var calvalarr=new Array(42);

function f_cps(obj) {
	obj.style.background='#C4D3EA';
	obj.style.font='10px Arial';
	obj.style.color='#333333';
	obj.style.textAlign='center';
	obj.style.textDecoration='none';
	obj.style.border='1px solid #6487AE';
	obj.style.cursor='pointer';
}

function f_cpps(obj) {
	obj.style.background='#C4D3EA';
	obj.style.font='10px Arial';
	obj.style.color='#ABABAB';
	obj.style.textAlign='center';
	obj.style.textDecoration='line-through';
	obj.style.border='1px solid #6487AE';
	obj.style.cursor='default';
}

function f_hds(obj) {
	obj.style.background='#FFF799';
	obj.style.font='bold 10px Arial';
	obj.style.color='#333333';
	obj.style.textAlign='center';
	obj.style.textDecoration='none';
	obj.style.border='1px solid #6487AE';
	obj.style.cursor='pointer';
}

// day selected
function prepcalendar(hd,cm,cy) 
{
	now=new Date();
	sd=now.getDate();
	td=new Date();
	td.setDate(1);
	td.setFullYear(cy);
	td.setMonth(cm);
	cd=td.getDay();
	getObj('mns').innerHTML=mn[cm]+ ' ' + cy;
	marr=((cy%4)==0)?mnl:mnn;
	for(var d=1;d<=42;d++) {
		f_cps(getObj('v'+parseInt(d)));
		if ((d >= (cd )) && (d<=cd-(-marr[cm])-1)) {
			dip=((d-cd < sd)&&(cm==sccm)&&(cy==sccy));
			htd=((hd!='')&&(d-cd+1==hd));
			// LC !
			if (dip)
				f_cpps(getObj('v'+parseInt(d)));
			if (htd)
				f_hds(getObj('v'+parseInt(d)));
			else
				f_cps(getObj('v'+parseInt(d)));
			// LC !
			dip=null;
			getObj('v'+parseInt(d)).onmouseover=(dip)?null:cs_over;
			getObj('v'+parseInt(d)).onmouseout=(dip)?null:cs_out;
			getObj('v'+parseInt(d)).onclick=(dip)?null:cs_click;
			getObj('v'+parseInt(d)).innerHTML=d-cd+1;	
			// Ce tableau contient les dates en clair
			moisclair=cm+1;
			if (moisclair <10)
			   moisclair="0"+moisclair;			
      jourclair=(d-cd+1);
			if (jourclair <10)
			   jourclair="0"+jourclair;
 			calvalarr[d]=''+jourclair+'/'+moisclair+'/'+cy;
		}
		// On efface les zones qui ne correspondent pas à un jour
		else {
			getObj('v'+d).innerHTML='&nbsp;';
			getObj('v'+parseInt(d)).onmouseover=null;
			getObj('v'+parseInt(d)).onmouseout=null;
			getObj('v'+parseInt(d)).style.cursor='default';
			calvalarr[d]='';
			}
	}
}

prepcalendar('',ccm,ccy);
//getObj('calendar'+cc).style.visibility='hidden';

function caddm() {
	marr=((ccy%4)==0)?mnl:mnn;
	
	ccm+=1;
	if (ccm>=12) {
		ccm=0;
		ccy++;
	}
	cdayf();
	prepcalendar('',ccm,ccy);
}

function csubm() {
	marr=((ccy%4)==0)?mnl:mnn;

	ccm-=1;
	if (ccm<0) {
		ccm=11;
		ccy--;
	}

	cdayf();
	prepcalendar('',ccm,ccy);
}

function cdayf() {
if ((ccy>sccy)|((ccy==sccy)&&(ccm>=sccm)))
	return;
else {
	ccy=sccy;
	ccm=sccm;
	//LC ! cfd=scfd;
	}
}
