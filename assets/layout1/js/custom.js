var startTime, endTime, lama_akses, lightspinner, darkspinner, darkspinnerDT, darkspinners;

$(document).ready(function() {

	$('.fTables').dataTable({
	scrollX     : true,
	responsive  : false,
	order       : [],
	columnDefs  : [
		{targets   : [],orderable : false},
		{className : "text-center", targets:[0]}
	],
	});

	$('.fTablek').dataTable();

	$('.fTablex').dataTable({
	scrollX     : true,
	responsive  : true,
	order       : [],
	columnDefs  : [
		{targets   : [],orderable : false},
		{className : "text-center", targets:[0]}
	],
	});

	$('.fTable').dataTable({
	scrollX     : false,
	pagingType  : "full_numbers",
	order       : [],
	columnDefs  : [
		{targets   : [-1],orderable : false},
		{className : "text-center", targets:[0,-1]}
	]
	});

	$('.jTable-button').dataTable({
	scrollX     : false,
	pagingType  : "full_numbers",
	order       : [],
	columnDefs  : [
		{targets   : [-1],orderable : false},
		{className : "text-center", targets:[0,-1]}
	],
	buttons: {            
		dom: {
			button: {
				className: 'btn btn-outline-success p-1'
			}
		},
		buttons: [
		{
			extend: 'copyHtml5',
			text: '<i class="fa fa-copy"></i> Copy',
			titleAttr: 'Copy'
		},
		{
			extend: 'excelHtml5',
			text: '<i class="fa fa-file-excel-o"></i> Excel',
			titleAttr: 'Excel'
		},
		{
			extend: 'csvHtml5',
			text: '<i class="fa fa-file-excel-o"></i> CSV',
			titleAttr: 'CSV'
		}
		]
	}
	});

	$('.jTables-button').dataTable({
	scrollX     : true,
	iDisplayLength: 25,
	columnDefs  : [
		{className : "text-center", targets:[0]}
	],
	buttons: {            
		dom: {
			button: {
				className: 'btn btn-outline-success p-1'
			}
		},
		buttons: [
		{
			extend: 'copyHtml5',
			text: '<i class="fa fa-copy"></i> Copy',
			titleAttr: 'Copy'
		},
		{
			extend: 'excelHtml5',
			text: '<i class="fa fa-file-excel-o"></i> Excel',
			titleAttr: 'Excel'
		},
		{
			extend: 'csvHtml5',
			text: '<i class="fa fa-file-excel-o"></i> CSV',
			titleAttr: 'CSV'
		}
		]
	}
	});

	$('.jTablek-button').dataTable({
	buttons: {            
		dom: {
			button: {
				className: 'btn btn-outline-success p-1'
			}
		},
		buttons: [
		{
			extend: 'copyHtml5',
			text: '<i class="fa fa-copy"></i> Copy',
			titleAttr: 'Copy'
		},
		{
			extend: 'excelHtml5',
			text: '<i class="fa fa-file-excel-o"></i> Excel',
			titleAttr: 'Excel'
		},
		{
			extend: 'csvHtml5',
			text: '<i class="fa fa-file-excel-o"></i> CSV',
			titleAttr: 'CSV'
		}
		]
	}
	});

	$('.jTablex-button').dataTable({
	scrollX     : true,
	responsive  : true,
	buttons: {            
		dom: {
			button: {
				className: 'btn btn-outline-success p-1'
			}
		},
		buttons: [
		{
			extend: 'copyHtml5',
			text: '<i class="fa fa-copy"></i> Copy',
			titleAttr: 'Copy'
		},
		{
			extend: 'excelHtml5',
			text: '<i class="fa fa-file-excel-o"></i> Excel',
			titleAttr: 'Excel'
		},
		{
			extend: 'csvHtml5',
			text: '<i class="fa fa-file-excel-o"></i> CSV',
			titleAttr: 'CSV'
		}
		]
	}
	});

	$('.fUpload1').filestyle({
	buttonName: 'btn-danger',
	buttonText: ' File selection'

	});

	$('.fUpload2').filestyle({
	buttonName: 'btn-success',
	buttonText: ' Open'

	});

	$('.fUpload3').filestyle({
	buttonName: 'btn-info',
	buttonText: ' Select a File'

	});
	$('.fUpload4').filestyle({
	buttonName: 'btn-warning',
	buttonText: ' Select a File'

	});
	//var editor = ace.edit('editor');

	$(this).on('click', function(e) {
	// console.log(e.target.classList[0]);
	if(e.target.classList[0] === 'modal') {
		var $dialog = $(this).find('.modal-dialog');
		$dialog.addClass('animated shake');
		$dialog.one('webkitAnimationEnd oanimationend msAnimationEnd animationend', function() {
		$dialog.removeClass('shake');
		});      
	}
	}); 
	
	$('input').keydown(function(event){
	if(event.keyCode == 13) {
		event.preventDefault();
		return false;
	}
	});
	
	$('.multiselect-filtering').multiselect({
		enableFiltering: true,
		enableCaseInsensitiveFiltering: true
	});
	
});

function startfirst() {
	$('#mDialogfirst').modal('show');
	startTime = performance.now();
};

function endfirst() {
	endTime = performance.now();
	var timeDiff = endTime - startTime; //in ms 
	// strip the ms 
	timeDiff /= 1000; 
	
	// get seconds 
	lama_akses =1000*(1+Math.round(timeDiff));
	// console.log(lama_akses + " seconds");
	setTimeout(function() {$('#mDialogfirst').modal('hide');},lama_akses);
	// $('#mDialogfirst').modal('hide');
}

function startforgot() {
	$('#mDialog').modal('show');
	// startTime = performance.now();
};

function endforgot() {
	// endTime = performance.now();
	// var timeDiff = endTime - startTime; //in ms 
	// strip the ms 
	// timeDiff /= 1000; 
	
	// get seconds 
	// lama_akses =1000*(1+Math.round(timeDiff));
	// console.log(lama_akses + " seconds");
	// setTimeout(function() {$('#mDialogfirst').modal('hide');},lama_akses);
	$('#mDialog').modal('hide');
}

function start() {
	$('#mDialog').modal('show');
	startTime = performance.now();
};

function end() {
	endTime = performance.now();
	var timeDiff = endTime - startTime; //in ms 
	// strip the ms 
	timeDiff /= 1000; 
	
	// get seconds 
	lama_akses =1000*(1+Math.round(timeDiff));
	// console.log(lama_akses + " seconds");
	setTimeout(function() {$('#mDialog').modal('hide');},lama_akses);
}

function startcorner() {
	$('#mDialogCorner').modal('show');
	startTime = performance.now();
};

function endcorner() {
	endTime = performance.now();
	var timeDiff = endTime - startTime; //in ms 
	// strip the ms 
	timeDiff /= 1000; 
	
	// get seconds 
	lama_akses =1000*(1+Math.round(timeDiff));
	// console.log(lama_akses + " seconds");
	setTimeout(function() {$('#mDialogCorner').modal('hide');},lama_akses);
}

function stoplightspinner() {
	endTime = performance.now();
	var timeDiff = endTime - startTime; //in ms 
	// strip the ms 
	timeDiff /= 1000; 
	
	// get seconds 
	lama_akses =50*(1+Math.round(timeDiff));
	window.setTimeout(function(){ $(lightspinner).unblock(); }, lama_akses);
}

function endlightspinner(i) {
	endTime   = performance.now();
	var timeDiff = endTime - startTime;
		timeDiff /= 1000; 
		lama_akses =1000*(1+Math.round(timeDiff));
	window.setTimeout(function(){ $(lightspinner).unblock(); }, lama_akses);
	if(i!=""){
	setTimeout(function() {$(i).modal('hide');},lama_akses+500);
	}
}

function enddarkspinner(i) {
	endTime = performance.now();
	var timeDiff = endTime - startTime;
		timeDiff /= 1000; 
		lama_akses =1000*(1+Math.round(timeDiff));
	window.setTimeout(function(){ $(darkspinner).unblock(); }, lama_akses);
	if(i!=""){
	setTimeout(function() {$(i).modal('hide');},lama_akses+500);
	}
}

function stopdarkspinner() {
	endTime = performance.now();
	var timeDiff = endTime - startTime;
		timeDiff /= 1000; 
		lama_akses =100*(1+Math.round(timeDiff));
	window.setTimeout(function(){ $(darkspinner).unblock(); }, lama_akses);
}

function spinnerlight001(e) {
	startTime = performance.now();
	lightspinner = e.closest('.card, .modal-content, .spinnerdark01');
	$(lightspinner).block({
	message: '<i class="icon-spinner spinner"></i></br><small>Loading.....</small>',
	overlayCSS: {
		backgroundColor: '#fff',
		opacity: 0.8,
		cursor: 'wait'
	},
	css: {
		border: 0,
		padding: 0,
		backgroundColor: 'none'
	}
	});
};

function spinnerlight002(e) {
	startTime = performance.now();
	lightspinner = e.closest('.card, .modal-content, .spinnerdark01');
	$(lightspinner).block({
	message: '<i class="icon-spinner2 spinner"></i></br><small>Loading.....</small>',
	overlayCSS: {
		backgroundColor: '#fff',
		opacity: 0.8,
		cursor: 'wait'
	},
	css: {
		border: 0,
		padding: 0,
		backgroundColor: 'none'
	}
	});
};

function spinnerlight003(e) {
	startTime = performance.now();
	lightspinner = e.closest('.card, .modal-content, .spinnerdark01');
	$(lightspinner).block({
	message: '<i class="icon-spinner3 spinner"></i></br><small>Loading. . .</small>',
	overlayCSS: {
		backgroundColor: '#fff',
		opacity: 0.8,
		cursor: 'wait'
	},
	css: {
		border: 0,
		padding: 0,
		backgroundColor: 'none'
	}
	});
};

function spinnerlight004(e) {
	startTime = performance.now();
	lightspinner = e.closest('.card, .modal-content, .spinnerdark01');
	$(lightspinner).block({
	message: '<i class="icon-spinner4 spinner"></i></br><small>Loading. . .</small>',
	overlayCSS: {
		backgroundColor: '#fff',
		opacity: 0.8,
		cursor: 'wait'
	},
	css: {
		border: 0,
		padding: 0,
		backgroundColor: 'none'
	}
	});
};

function spinnerlight005(e) {
	startTime = performance.now();
	lightspinner = e.closest('.card, .modal-content, .spinnerdark01');
	$(lightspinner).block({
	message: '<i class="icon-enlarge3 spinner"></i></br><small>Loading. . .</small>',
	overlayCSS: {
		backgroundColor: '#fff',
		opacity: 0.8,
		cursor: 'wait'
	},
	css: {
		border: 0,
		padding: 0,
		backgroundColor: 'none'
	}
	});
};

function spinnerlight006(e) {
	startTime = performance.now();
	lightspinner = e.closest('.card, .modal-content, .spinnerdark01');
	$(lightspinner).block({
	message: '<i class="icon-spinner6 spinner"></i></br><small>Loading. . .</small>',
	overlayCSS: {
		backgroundColor: '#fff',
		opacity: 0.8,
		cursor: 'wait'
	},
	css: {
		border: 0,
		padding: 0,
		backgroundColor: 'none'
	}
	});
};

function spinnerlight007(e) {
	startTime = performance.now();
	lightspinner = e.closest('.card, .modal-content, .spinnerdark01');
	$(lightspinner).block({
	message: '<i class="icon-picassa spinner"></i></br><small>Loading. . .</small>',
	overlayCSS: {
		backgroundColor: '#fff',
		opacity: 0.8,
		cursor: 'wait'
	},
	css: {
		border: 0,
		padding: 0,
		backgroundColor: 'none'
	}
	});
};

function spinnerlight008(e) {
	startTime = performance.now();
	lightspinner = e.closest('.card, .modal-content, .spinnerdark01');
	$(lightspinner).block({
	message: '<i class="icon-joomla spinner"></i></br><small>Loading. . .</small>',
	overlayCSS: {
		backgroundColor: '#fff',
		opacity: 0.8,
		cursor: 'wait'
	},
	css: {
		border: 0,
		padding: 0,
		backgroundColor: 'none'
	}
	});
};

function spinnerlight009(e) {
	startTime = performance.now();
	lightspinner = e.closest('.card, .modal-content, .spinnerdark01');
	$(lightspinner).block({
	message: '<i class="icon-spinner9 spinner"></i></br><small>Loading. . .</small>',
	overlayCSS: {
		backgroundColor: '#fff',
		opacity: 0.8,
		cursor: 'wait'
	},
	css: {
		border: 0,
		padding: 0,
		backgroundColor: 'none'
	}
	});
};

function spinnerlight010(e) {
	startTime = performance.now();
	lightspinner = e.closest('.card, .modal-content, .spinnerdark01');
	$(lightspinner).block({
	message: '<i class="icon-spinner10 spinner"></i></br><small>Loading. . .</small>',
	overlayCSS: {
		backgroundColor: '#fff',
		opacity: 0.8,
		cursor: 'wait'
	},
	css: {
		border: 0,
		padding: 0,
		backgroundColor: 'none'
	}
	});
};

function spinnerlight011(e) {
	startTime = performance.now();
	var lightspinner = e.closest('.card, .modal-content, .spinnerdark01');
	$(lightspinner).block({
	message: '<i class="icon-spinner11 spinner"></i></br><small>Loading. . .</small>',
	overlayCSS: {
		backgroundColor: '#fff',
		opacity: 0.8,
		cursor: 'wait'
	},
	css: {
		border: 0,
		padding: 0,
		backgroundColor: 'none'
	}
	});
};

function spinnerdark001(e) {
	startTime = performance.now();
	darkspinner = e.closest('.card, .modal-content, .spinnerdark01');
	$(darkspinner).block({
	message: '<i class="icon-spinner spinner"></i></br><small>Loading.....</small>',
	overlayCSS: {
		backgroundColor: '#1B2024',
		opacity: 0.85,
		cursor: 'wait'
	},
	css: {
		border: 0,
		padding: 0,
		backgroundColor: 'none',
		color: '#fff'
	}
	});
};

function spinnerdark002(e) {
	startTime = performance.now();
	darkspinner = e.closest('.card, .modal-content, .spinnerdark01');
	$(darkspinner).block({
	message: '<i class="icon-spinner2 spinner"></i></br><small>Loaaading.....</small>',
	overlayCSS: {
		backgroundColor: '#1B2024',
		opacity: 0.85,
		cursor: 'wait'
	},
	css: {
		border: 0,
		padding: 0,
		backgroundColor: 'none',
		color: '#fff'
	}
	});
};

function spinnerdark003(e) {
	startTime = performance.now();
	darkspinner = e.closest('.card, .modal-content, .spinnerdark01');
	$(darkspinner).block({
	message: '<i class="icon-spinner3 spinner"></i></br><small>Loading. . .</small>',
	overlayCSS: {
		backgroundColor: '#1B2024',
		opacity: 0.85,
		cursor: 'wait'
	},
	css: {
		border: 0,
		padding: 0,
		backgroundColor: 'none',
		color: '#fff'
	}
	});
};

function spinnerdark004(e) {
	startTime = performance.now();
	darkspinner = e.closest('.card, .modal-content, .spinnerdark01');
	$(darkspinner).block({
	message: '<i class="icon-spinner4 spinner"></i></br><small>Loading. . .</small>',
	overlayCSS: {
		backgroundColor: '#1B2024',
		opacity: 0.85,
		cursor: 'wait'
	},
	css: {
		border: 0,
		padding: 0,
		backgroundColor: 'none',
		color: '#fff'
	}
	});
};

function spinnerdark005(e) {
	startTime = performance.now();
	darkspinner = e.closest('.card, .modal-content, .spinnerdark01');
	$(darkspinner).block({
	message: '<i class="icon-enlarge3 spinner"></i></br><small>Loading. . .</small>',
	overlayCSS: {
		backgroundColor: '#1B2024',
		opacity: 0.85,
		cursor: 'wait'
	},
	css: {
		border: 0,
		padding: 0,
		backgroundColor: 'none',
		color: '#fff'
	}
	});
};

function spinnerdark006(e) {
	startTime = performance.now();
	darkspinner = e.closest('.card, .modal-content, .spinnerdark01');
	$(darkspinner).block({
	message: '<i class="icon-spinner6 spinner"></i></br><small>Loading. . .</small>',
	overlayCSS: {
		backgroundColor: '#1B2024',
		opacity: 0.85,
		cursor: 'wait'
	},
	css: {
		border: 0,
		padding: 0,
		backgroundColor: 'none',
		color: '#fff'
	}
	});
};

function spinnerdark007(e) {
	startTime = performance.now();
	darkspinner = e.closest('.card, .modal-content, .spinnerdark01');
	$(darkspinner).block({
	message: '<i class="icon-picassa spinner"></i></br><small>Loading. . .</small>',
	overlayCSS: {
		backgroundColor: '#1B2024',
		opacity: 0.85,
		cursor: 'wait'
	},
	css: {
		border: 0,
		padding: 0,
		backgroundColor: 'none',
		color: '#fff'
	}
	});
};

function spinnerdark008(e) {
	startTime = performance.now();
	darkspinner = e.closest('.card, .modal-content, .spinnerdark01');
	$(darkspinner).block({
	message: '<i class="icon-joomla spinner"></i></br><small>Loading. . .</small>',
	overlayCSS: {
		backgroundColor: '#1B2024',
		opacity: 0.85,
		cursor: 'wait'
	},
	css: {
		border: 0,
		padding: 0,
		backgroundColor: 'none',
		color: '#fff'
	}
	});
};

function spinnerdark009(e) {
	startTime = performance.now();
	darkspinner = e.closest('.card, .modal-content, .spinnerdark01');
	$(darkspinner).block({
	message: '<i class="icon-spinner9 spinner"></i></br><small>Loading. . .</small>',
	overlayCSS: {
		backgroundColor: '#1B2024',
		opacity: 0.85,
		cursor: 'wait'
	},
	css: {
		border: 0,
		padding: 0,
		backgroundColor: 'none',
		color: '#fff'
	}
	});
};

function spinnerdark010(e) {
	startTime = performance.now();
	darkspinner = e.closest('.card, .modal-content, .spinnerdark01');
	$(darkspinner).block({
	message: '<i class="icon-spinner10 spinner"></i></br><small>Loading. . .</small>',
	overlayCSS: {
		backgroundColor: '#1B2024',
		opacity: 0.85,
		cursor: 'wait'
	},
	css: {
		border: 0,
		padding: 0,
		backgroundColor: 'none',
		color: '#fff'
	}
	});
};

function spinnerdark011(e) {
	startTime = performance.now();
	var darkspinner = e.closest('.card, .modal-content, .spinnerdark01');
	$(darkspinner).block({
	message: '<i class="icon-spinner11 spinner"></i></br><small>Loading. . .</small>',
	overlayCSS: {
		backgroundColor: '#1B2024',
		opacity: 0.85,
		cursor: 'wait'
	},
	css: {
		border: 0,
		padding: 0,
		backgroundColor: 'none',
		color: '#fff'
	}
	});
};

function spinnersdark(e) {
	darkspinners = e.closest(".panel, .card, .modal-content");
	$(darkspinners).block({
	message:
		'<i class="icon-spinner11 spinner"></i></br><small>Loading. . .</small>',
	overlayCSS: {
		backgroundColor: "#1B2024",
		opacity: 0.85,
		cursor: "wait",
	},
	css: {
		border: 0,
		padding: 0,
		backgroundColor: "none",
		color: "#fff",
	},
	});
}

function spinnerdarkDT(e) {
	darkspinnerDT = e.closest(".panel");
	$(darkspinnerDT).block({
	message:
		'<i class="icon-spinner11 spinner"></i></br><small>Loading. . .</small>',
	overlayCSS: {
		backgroundColor: "#1B2024",
		opacity: 0.85,
		cursor: "wait",
	},
	css: {
		border: 0,
		padding: 0,
		backgroundColor: "none",
		color: "#fff",
	},
	});
}

function on_loader(e) {
	var loaderOn = e.closest(".onLoader");
	$(loaderOn).block({
	message:
		'<i class="icon-spinner11 spinner"></i></br><small>Loading. . .</small>',
	overlayCSS: {
		backgroundColor: "#1B2024",
		opacity: 0.85,
		cursor: "wait",
	},
	css: {
		border: 0,
		padding: 0,
		backgroundColor: "none",
		color: "#fff",
	},
	});
}
function onx_loader(e) {
	$(e).block({
	message:
		'<i class="icon-spinner11 spinner"></i></br><small>Loading. . .</small>',
	overlayCSS: {
		backgroundColor: "#1B2024",
		opacity: 0.85,
		cursor: "wait",
	},
	css: {
		border: 0,
		padding: 0,
		backgroundColor: "none",
		color: "#fff",
	},
	});
}

function waitingForm(e) {
	spinners = e.closest(".modal-content");
	$(spinners).block({
	message:
		'<i class="icon-spinner11 spinner"></i></br><small>Loading. . .</small>',
	overlayCSS: {
		backgroundColor: "#1B2024",
		opacity: 0.85,
		cursor: "wait",
	},
	css: {
		border: 0,
		padding: 0,
		backgroundColor: "none",
		color: "#fff",
	},
	});
}

function stopspinnersdark() {
	$(darkspinners).unblock();
}

function stopdarkspinnerDT() {
	$(darkspinnerDT).unblock();
}

function stopingForm() {
	$(spinners).unblock();
}

function stopon_loader(e) {
	var loaderOff = e.closest(".onLoader");
	$(loaderOff).unblock();
}
function stoponx_loader(e) {
	$(e).unblock();
}

$('.spinner-dark-3').on('click', function() {
	var dark01 = $(this).closest('.card, .modal-content, .spinnerdark01');
	$(dark01).block({
	message: '<i class="icon-spinner3 spinner"></i></br><small>Loading. . .</small>',
	overlayCSS: {
		backgroundColor: '#1B2024',
		opacity: 0.85,
		cursor: 'wait'
	},
	css: {
		border: 0,
		padding: 0,
		backgroundColor: 'none',
		color: '#fff'
	}
	});
	window.setTimeout(function() {
	$(dark01).unblock();
	}, 2000);
});


function rbAktif(){
	$('#tNonpublish').prop('checked', false).uniform();
	$('#tpublish').prop('checked', false).uniform();
	$('#tPnonaktif').prop('checked', false).uniform();
	$('#tPaktif').prop('checked', false).uniform();
}

function spinnerdark01() {
	var dark_6 = $(this).closest('.card');
	$(dark_6).block({
	message: '<i class="icon-spinner9 spinner"></i>',
	overlayCSS: {
		backgroundColor: '#1B2024',
		opacity: 0.85,
		cursor: 'wait'
	},
	css: {
		border: 0,
		padding: 0,
		backgroundColor: 'none',
		color: '#fff'
	}
	});
	window.setTimeout(function() {
	$(dark_6).unblock();
	}, 1000);
}

function hideUpload(){
	var mModal  = $('#mForm').get();
	var upload = $(mModal).find('.fileinput-upload');
		upload.addClass('d-none');
	// console.log(upload);
}

function submitFormOld(method, url, data) {
	return $.ajax({
	method: method,
	url: url,
	data: data,
	async: true,
	dataType: "json",
	success: function (response) {
		return response;
	},
	error: function (error) {
		return error;
	},
	});
}

function submitForm(method, url, data) {
	return $.ajax({
	type: "ajax",
	method: method,
	url: url,
	data: data,
	async: true,
	dataType: "json",
	success: function (response) {
		return response;
	},
	error: function (error) {
		return error;
	},
	});
}

function submitFormData(method, url, data) {
	return $.ajax({
	type: "ajax",
	method: method,
	url: url,
	data: new FormData(data),
	contentType: false,
	processData: false,
	async: true,
	dataType: "json",
	success: function (response) {
		return response;
	},
	error: function (error) {
		return error;
	},
	});
}

async function uploadFirebase(firebaseConfig, file, filename, loadingname) {
	var result = {};
	// Your web app's Firebase configuration
	// For Firebase JS SDK v7.20.0 and later, measurementId is optional
	// Initialize Firebase
	if (!firebase.apps.length) {
		firebase.initializeApp(firebaseConfig);
	}else {
		firebase.app(); // if already initialized, use that one
	}
	var storageRef 	= firebase.storage().ref(filename);
	var task 		= storageRef.put(file);
	// Listen for state changes, errors, and completion of the upload.
	return new Promise(function(resolve, reject) {
		task.on(
			firebase.storage.TaskEvent.STATE_CHANGED,
			snapshot => {
				var progress = (snapshot.bytesTransferred / snapshot.totalBytes) * 100;
				$(".se-pre-con1").css('display', 'none');
				$("#mUpload").modal('show');
				$("#mUpload").find('.progress-bar').css('width', progress+'%');
				$("#mUpload").find('.cText').text(loadingname);
				console.log('Upload is ' + progress + '% done');
				switch (snapshot.state) {
					case firebase.storage.TaskState.PAUSED: // or 'paused'
					console.log('Upload is paused');
					result = {
						status: 'paused',
						data: '',
						error: false
					}
					break;
					case firebase.storage.TaskState.RUNNING: // or 'running'
					console.log('Upload is running');
					result = {
						status: 'running',
						data: '',
						error: false
					}
					break;
				}
			},
			error => { 
				switch (error.code) {
					case 'storage/unauthorized':
						console.log(error);
						console.log('storage/unauthorized');
						// User doesn't have permission to access the object
						$("#mUpload").modal('hide');
						$(".se-pre-con1").css('display', 'none');
						result = {
							status: false,
							data: 'storage/unauthorized',
							error: error
						}
					break;
					case 'storage/canceled':
						console.log(error);
						console.log('storage/canceled');
						// User canceled the upload
						$("#mUpload").modal('hide');
						$(".se-pre-con1").css('display', 'none');
						result = {
							status: false,
							data: 'storage/canceled',
							error: error
						}
					break;
					case 'storage/unknown':
						console.log(error);
						console.log('storage/unknown');
						// Unknown error occurred, inspect error.serverResponse
						$("#mUpload").modal('hide');
						$(".se-pre-con1").css('display', 'none');
						result = {
							status: false,
							data: 'storage/unknown',
							error: error
						}
					break;
				}
				reject(result);
			},
			() => {
				task.snapshot.ref
				.getDownloadURL()
				.then(downloadURL => {
					$("#mUpload").modal('hide');
					$(".se-pre-con1").css('display', 'none');
					result = {
						status: true,
						data: downloadURL,
						error: false
					}
					resolve(result);
				});
			}
		);
	});
}

async function uploadFirebaseBase64(firebaseConfig, file, ref, filename, loadingname) {
var result = {};
// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
// Initialize Firebase
if (!firebase.apps.length) {
	firebase.initializeApp(firebaseConfig);
}else {
	firebase.app(); // if already initialized, use that one
}
// var storageRef 	= firebase.storage().ref(filename);
var task 	= firebase.storage().ref(ref).child(filename).putString(file, 'base64');
// Listen for state changes, errors, and completion of the upload.
return new Promise(function(resolve, reject) {
	task.on(
		firebase.storage.TaskEvent.STATE_CHANGED,
		snapshot => {
			var progress = (snapshot.bytesTransferred / snapshot.totalBytes) * 100;
			$(".se-pre-con2").css('display', 'none');
			$("#mUpload").modal('show');
			$("#mUpload").find('.progress-bar').css('width', progress+'%');
			$("#mUpload").find('.cText').text(loadingname);
			console.log('Upload is ' + progress + '% done');
			switch (snapshot.state) {
				case firebase.storage.TaskState.PAUSED: // or 'paused'
				console.log('Upload is paused');
				result = {
					status: 'paused',
					data: '',
					error: false
				}
				break;
				case firebase.storage.TaskState.RUNNING: // or 'running'
				console.log('Upload is running');
				result = {
					status: 'running',
					data: '',
					error: false
				}
				break;
			}
		},
		error => { 
			switch (error.code) {
				case 'storage/unauthorized':
					console.log(error);
					console.log('storage/unauthorized');
					// User doesn't have permission to access the object
					$("#mUpload").modal('hide');
					$(".se-pre-con2").css('display', 'none');
					result = {
						status: false,
						data: 'storage/unauthorized',
						error: error
					}
				break;
				case 'storage/canceled':
					console.log(error);
					console.log('storage/canceled');
					// User canceled the upload
					$("#mUpload").modal('hide');
					$(".se-pre-con2").css('display', 'none');
					result = {
						status: false,
						data: 'storage/canceled',
						error: error
					}
				break;
				case 'storage/unknown':
					console.log(error);
					console.log('storage/unknown');
					// Unknown error occurred, inspect error.serverResponse
					$("#mUpload").modal('hide');
					$(".se-pre-con2").css('display', 'none');
					result = {
						status: false,
						data: 'storage/unknown',
						error: error
					}
				break;
			}
			reject(result);
		},
		() => {
			task.snapshot.ref
			.getDownloadURL()
			.then(downloadURL => {
				$("#mUpload").modal('hide');
				$(".se-pre-con2").css('display', 'block');
				result = {
					status: true,
					data: downloadURL,
					error: false
				}
				resolve(result);
			});
		}
	);
});
}

$.fn.serializeObject = function() {
	var o = {};
	var a = this.serializeArray();
	$.each(a, function() {
		if (o[this.name]) {
			if (!o[this.name].push) {
				o[this.name] = [o[this.name]];
			}
			o[this.name].push(this.value || '');
		} else {
			o[this.name] = this.value || '';
		}
	});
	return o;
};

function modalDragShow(element) {
	if (!($('.modal.in').length)) {
		$('.modal-dialog').css({
			top: 0,
			left: 0
		});
	}
	$(element).modal({
		// backdrop: false,
		show: true
	});
	$('.modal-dialog').draggable({
		handle: ".modal-content"
	});
}
