
// GLOBAL VARIABLES DECLARATIONS
var debugg = true;
var container;
var adImg;
var name;
var title;
var intro1;
var intro2;
var intro3;
var copy1;
var copy2;
var list;
var classes;
var btnCta;
var logo;
var harvard;

var introAnim = false;

function debuggery(string){
	if (debugg == true) {
		console.log('%c' + string, 'color:#00B3EB; font-size:1.2em;');
	}
}

function startAd(){
	debuggery('XAL debug: in start ad');

	// GET ELEMENTS FROM DOCUMENT
	container = document.getElementById('container');
	adImg = document.getElementById('adImg');
	title = document.getElementById('title');
	intro1 = document.getElementById('intro1');
	intro2 = document.getElementById('intro2');
	intro3 = document.getElementById('intro3');
	copy1 = document.getElementById('copy1');
	copy2 = document.getElementById('copy2');
	btnCta = document.getElementById('btnCta');
	logo = document.getElementById('logo');
	name = document.getElementById('name');
	harvard = document.getElementById('harvard');

	// INTIALIZE FUNCTIONS
	addEventListeners();
	setDynamics();
}

function setDynamics(){
nameText = id;
	adImg.style.backgroundImage = 'url("img/' + nameText.toLowerCase().replace(' ','').replace(' ','') + 'Img.jpg")';
	container.style.backgroundColor = '#' + containerColor;

	title.getElementsByTagName('span')[0].innerHTML = titleText;
	title.style.color = '#' + titleColor;
	title.style.fontSize = titleSize;
	if (nameText.toLowerCase().replace(' ', '') == 'generalprogram') {
		title.style.borderBottomColor = '#6fb6b1';
	}

	if (nameText.toLowerCase().replace(' ', '').replace(' ', '') == 'secondaryschoolprogram') {
		intro1.style.display = intro2.style.display = intro3.style.display = 'block';

		intro1.innerHTML = introCopy1;
		intro2.innerHTML = introCopy2;
		intro3.innerHTML = introCopy3;

		introAnim = true;
		
		setTimeout(function(){ anim(); }, 10);
	} else {
		anim();
	}

	copy1.innerHTML = copy1Text;
	copy1.style.fontFamily = '"' + copy1Font + '", sans-serif';
	copy1.style.color = '#' + copy1Color;
	copy1.style.fontSize = copy1Size;

	if (copy2Display == 'true') {
		copy2.innerHTML = copy2Text;
		copy2.style.fontFamily = '"' + copy2Font + '", sans-serif';
		copy2.style.fontSize = copy2Size;
		copy2.style.color = '#' + copy2Color;
	} else {
		copy2.style.display = 'none';
	}

	if (logoType == 'standard') {
		logo.src = 'img/logoStandard.png';
	} else {
		logo.src = 'img/logoSecondary.png';
	}

	// btnCta.innerHTML = btnCtaText;
}

function addEventListeners(){
	debuggery('XAL debug: in add event listeners');

}



function anim(){
	debuggery('XAL debug: in anim');

	container.className += ' anim';
	title.className += ' anim';
	logo.className += ' anim';
	harvard.className += ' anim';

	if (introAnim == true) {
		intro1.className += ' anim';
		intro2.className += ' anim';
		intro3.className += ' anim';

		setTimeout(function(){
			intro1.className = intro1.className.replace(' anim', '');;
			intro2.className = intro2.className.replace(' anim', '');;
			intro3.className = intro3.className.replace(' anim', '');;
			setTimeout(function(){
				copy1.className += ' anim';
	 			copy2.className += ' anim';
				btnCta.className += ' anim';
				setTimeout(function(){
					btnCta.style.webkitTransitionDelay = '0s';
					btnCta.style.transitionDelay = '0s';
					btnCta.style.webkitTransitionDuration = '0.1s';
					btnCta.style.transitionDuration = '0.1s';
				}, 1700);
			}, 700);
		}, 3000);
	} else {
		copy1.className += ' anim';
		copy2.className += ' anim';
		btnCta.className += ' anim';
		setTimeout(function(){
			btnCta.style.webkitTransitionDelay = '0s';
			btnCta.style.transitionDelay = '0s';
			btnCta.style.webkitTransitionDuration = '0.1s';
			btnCta.style.transitionDuration = '0.1s';
		}, 1700);
	}

}
