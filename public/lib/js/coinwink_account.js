// Notice

var emailProvider = location.href; 
emailProvider = emailProvider.substring(emailProvider.indexOf("?")+1);
emailProvider = emailProvider.substring(0, emailProvider.indexOf("#"));
switch (emailProvider) {
	case 'yahoo':
		document.getElementById('notice').style.display = "block";
		document.getElementById('email-provider').innerHTML = "Yahoo";
		break;
	case 'outlook':
		document.getElementById('notice').style.display = "block";
		document.getElementById('email-provider').innerHTML = "Outlook";
		break;
	case 'msn':
		document.getElementById('notice').style.display = "block";
		document.getElementById('email-provider').innerHTML = "MSN";
		break;
	case 'live':
		document.getElementById('notice').style.display = "block";
		document.getElementById('email-provider').innerHTML = "Live";
		break;
	case 'aol':
		document.getElementById('notice').style.display = "block";
		document.getElementById('email-provider').innerHTML = "AOL";
		break;
	case 'hotmail':
		document.getElementById('notice').style.display = "block";
		document.getElementById('email-provider').innerHTML = "Hotmail";
		break;
	case 'att':
		document.getElementById('notice').style.display = "block";
		document.getElementById('email-provider').innerHTML = "AT&T";
		break;
	case 'ymail':
		document.getElementById('notice').style.display = "block";
		document.getElementById('email-provider').innerHTML = "Ymail";
		break;
	case 'icloud':
		document.getElementById('notice').style.display = "block";
		document.getElementById('email-provider').innerHTML = "iCloud";
		break;
}





// MATRIX THEME

var stopDemo = false;

function stopDemoNow() {
	stopDemo = true;
}

var array = '';
var arrayReal = '';
var stripesInitialized = false;
function stripesInit() {
	if (!stripesInitialized) {
		array = document.getElementsByClassName('black-stripe');
		arrayReal = Array.from(array);
	}
	stripesInitialized = true;
}

function runDemo() {
	stripesInit();

	if (stopDemo) { return; }
	setTimeout(function() {
		if (stopDemo) { return; }
		if(cw_theme == 'classic') {
			jQuery('.black-stripe').css('background-color', '#4F585B');
		}
		else if (cw_theme == 'metaverse') {
			jQuery('.black-stripe').css('background-color', '#060512');
			// jQuery('.theme-transition-over').addClass('bg-metaverse');

			setTimeout(() => {
				jQuery('.theme-transition-over').fadeIn(4000);
			}, 3000);
		}
		jQuery('.transition-matrix').css('z-index', 999);

		if(cw_theme == 'matrix') {
			setTimeout(function() {
				var styles = "<style type='text/css'>body::-webkit-scrollbar-track{background: black; } body::-webkit-scrollbar-thumb:hover { background-color: black; } body::-webkit-scrollbar-thumb{background: black}</style>";  
				jQuery(styles).appendTo('head');
			}, 500)
		}
		else if (cw_theme == 'classic') {
			setTimeout(function() {
				var styles = "<style type='text/css'>body::-webkit-scrollbar-track{background-color: #4F585B!important; box-shadow: none!important;} body::-webkit-scrollbar-thumb:hover { background-color: #4F585B!important; } body::-webkit-scrollbar-thumb{background-color: #4F585B!important; }</style>";  
				jQuery(styles).appendTo('head');
			}, 500)
		}
		else if (cw_theme == 'metaverse') {
			setTimeout(function() {
				var styles = "<style type='text/css'>body::-webkit-scrollbar-track{background-color: #060512!important; box-shadow: none!important;} body::-webkit-scrollbar-thumb:hover { background-color: #060512!important; } body::-webkit-scrollbar-thumb{background-color: #060512!important; }</style>";  
				jQuery(styles).appendTo('head');
			}, 500)
		}
	
		if(arrayReal.length != 0) {
			var item = arrayReal[Math.floor(Math.random() * arrayReal.length)];
			item.classList.add("full-height");
			arrayReal.splice(arrayReal.indexOf(item), 1);
			runDemo();
		}
		else {
			stopDemo = true;
			
			var string = 'Welcome to the Matrix';
			
			if(cw_theme == 'classic') {
				string = 'Coinwink Classic';
				jQuery('#t-centered').css('color', '#fff');
			}

			if (cw_theme == 'metaverse') {
				string = 'Entering the Metaverse';
				jQuery('#t-centered').css('color', '#25b0ca');
				jQuery('#t-centered').css('marginTop', '-45px');
			}

			var i = 0;
			function typeWriter() {
				if (i < string.length) {
					document.getElementById("t-centered").innerHTML += string.charAt(i);
					i++;
					setTimeout(typeWriter, 25);
				}
			}

			setTimeout(function() {
				// jQuery('.t-centered').show();
				typeWriter();
			}, 1500);

			setTimeout(function() {
				window.location.href = '/';
			}, 4500);
		}
	}, 50)
}
// runDemo();

function themeMetaverse() {
	if (cw_theme == 'metaverse') { return; }

	jQuery('#themeMetaverse').prop("checked", true);
	jQuery('#themeClassic').prop("checked", false);
	jQuery('#themeMatrix').prop("checked", false);
	cw_theme = 'metaverse';
	runDemo();

	var data = 'theme=metaverse';
	// Execute
	jQuery.ajax({
		type:"POST",
		url: '/api/account_theme',
		data: data
	});
}

function themeMatrix() {
	if (cw_theme == 'matrix') { return; }

	jQuery('#themeMatrix').prop("checked", true);
	jQuery('#themeClassic').prop("checked", false);
	jQuery('#themeMetaverse').prop("checked", false);
	cw_theme = 'matrix';
	runDemo();

	var data = 'theme=matrix';
	// Execute
	jQuery.ajax({
		type:"POST",
		url: '/api/account_theme',
		data: data
	});
}

function themeClassic() {
	if (cw_theme == 'classic') { return; }

	jQuery('#themeClassic').prop("checked", true);
	jQuery('#themeMatrix').prop("checked", false);
	jQuery('#themeMetaverse').prop("checked", false);8
	cw_theme = 'classic';
	runDemo();

	var data = 'theme=classic';
	// Execute
	jQuery.ajax({
		type:"POST",
		url: '/api/account_theme',
		data: data
	});
}

// 
// MATRIX THEME SCRIPTS
// 
if (cw_theme == 'matrix') {

	if (t_i == '') {t_i = 0.65}
	var matrixTr = Number(t_i);
	var matrixTransp = Number(t_i);


	var bg = 'rgba(0,0,0,'+matrixTransp+')';
	document.getElementById('matrix-overlay').style.backgroundColor = bg; 

	
	function moreTransp() {
		
		// if (typeof(matrixTr) == 'undefined') {
		//     matrixTr = t_i;
		//     if (t_i == '') {t_i = 0.65}
		// }
		// console.log(matrixTr, matrixTransp)

		if (matrixTransp < 0) { matrixTransp == 0.00; return; }

		if (matrixTransp == 0.00) {
			// console.log(matrixTransp.toFixed(2), Number(matrixTr).toFixed(2))
			if (matrixTransp.toFixed(2) == Number(matrixTr).toFixed(2)) {
				matrixTransp = 0.00;
				matrixTr = 0.00;
				return;
			}
			else {
				matrixTransp = 0.00;
				matrixTr = 0.00;
			}
		}

		if (matrixTransp < 0) { matrixTransp = 0.00 }
		else if (matrixTransp > 1.00) { matrixTransp = 1.00 }

		matrixTr = matrixTransp.toFixed(2)
		if (matrixTr <= 0.15 || matrixTr > 0.85) {
			matrixTransp -= 0.05;
		}
		else {
			matrixTransp -= 0.10;
		}
		var bg = 'rgba(0,0,0,'+matrixTransp.toFixed(2)+')';
		jQuery('.overlay').css("background-color", bg);
		newRequest(matrixTransp.toFixed(2));
	}

	function lessTransp() {

		// if (typeof(matrixTr) == 'undefined') {
		//     matrixTr = t_i;
		//     if (t_i == '') {t_i = 0.65}
		// }

		if (matrixTransp > 1) { matrixTransp = 1.00; return; }

		if (matrixTransp == 1.00) {
			if (matrixTransp.toFixed(2) == Number(matrixTr).toFixed(2)) {
				matrixTransp = 1.00;
				matrixTr = 1.00;
				return;
			}
			else {
				matrixTransp = 1.00;
				matrixTr = 1.00;
			}
		}

		if (matrixTransp < 0) { matrixTransp = 0.00 }
		else if (matrixTransp > 1.00) { matrixTransp = 1.00 }

		matrixTr = matrixTransp.toFixed(2);
		if (matrixTr < 0.15 || matrixTr >= 0.85) {
			matrixTransp += 0.05;
		}
		else {
			matrixTransp += 0.10;
		}
		var bg = 'rgba(0,0,0,'+matrixTransp.toFixed(2)+')';
		jQuery('.overlay').css("background-color", bg); 
		newRequest(matrixTransp.toFixed(2));
	}

	var canvas = document.getElementById('canv');
	var ctx = canvas.getContext('2d');

	var w = canvas.width = document.body.offsetWidth;
	var h = canvas.height = document.body.offsetHeight;
	var cols = Math.floor(w / 20) + 1;
	var ypos = Array(cols).fill(0);

	ctx.fillStyle = '#000';
	ctx.fillRect(0, 0, w, h);

	// ctx.fillStyle = '#0f0';
	// ctx.font = '15pt monospace';

	bottomReached = false;

	function matrix() {
		if (bottomReached && t_s) { clearInterval(matrixAnim); }
		ctx.fillStyle = '#0001';
		ctx.fillRect(0, 0, w, h);
		
		ctx.fillStyle = '#0f0';
		ctx.font = '13pt monospace';

		ypos.forEach((y, ind) => {
			const text = String.fromCharCode(Math.random() * 128);
			const x = ind * 20;
			ctx.fillText(text, x, y);
			if (y > 100 + Math.random() * 10000) ypos[ind] = 0;else
			ypos[ind] = y + 20;
			if (y > window.innerHeight && t_s != 0) {
				bottomReached = true;
			}
		});
	}
	// setInterval(matrix, 50);
	var matrixAnim = setInterval(matrix, 50);

	var isAnim = true;
	if (t_s != '0') { isAnim = false; }

	if (screen.width > 800) {
		window.addEventListener("resize", function() {
			canvas = document.getElementById('canv');
			ctx = canvas.getContext('2d');

			w = canvas.width = document.body.offsetWidth;
			h = canvas.height = document.body.offsetHeight;
			cols = Math.floor(w / 20) + 1;
			ypos = Array(cols).fill(0);

			ctx.fillStyle = '#000';
			ctx.fillRect(0, 0, w, h);

			canvas.width = window.innerWidth;
			canvas.height = window.innerHeight;
		});
	}

	var requests = 0;

	function newRequest(matrixTr) {
		requests++;
		var i = requests;

		setTimeout(function() {
			if (i == requests) {
				requests = 0;

				var data = 't_i='+matrixTr;
				
				jQuery.ajax({
					type:"POST",
					url: '/api/theme_intensity',
					data: data
				});

				console.log('Intensity saved!')
			}
		}, 350);
	}


	// MATRIX: STATIC/DYNAMIC

	if (t_s != '0') {
		jQuery('#static-check').prop("checked", true);
	}

	function themeStatic() {
		if (isAnim) {
			isAnim = false;
			t_s = 1;
			clearInterval(matrixAnim);
		}
		else {
			isAnim = true;
			t_s = 0;
			bottomReached = false;
			clearInterval(matrixAnim);
			matrixAnim = setInterval(matrix, 50);
		}

		var data = 't_s='+t_s;
		jQuery.ajax({
			type:"POST",
			url: '/api/theme_static',
			data: data
		});
	}

}
