function showToast(type,judul,message){
	switch(type.toLowerCase()){
		case "success":
			$.growl.notice({
				title: judul,
				message: message
			});
			break;
		case "warning":
			$.growl.warning({
				title: judul,
				message: message
			});
			break;
		case "danger":
			$.growl.error({
				title: judul,
				message: message
			});
			break;
		default:
			$.growl({
				title: judul,
				message: message
			});
	}
}
function loadScript(src) {
  let script = document.createElement('script');
  script.src = src;
  script.async = false;
  document.body.append(script);
}
function gritter(pesan,type='danger'){
	$.bootstrapGrowl(pesan, {
		type: type,
		delay: 4567,
		allow_dismiss: true
	});
}
if(typeof dtIntegration === 'function'){
	dtIntegration();
}else if(typeof dtIntegration2 === 'function'){
	dtIntegration2();
}
