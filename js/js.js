
// ******************************************************************* INICIALIZACIÓN DE EVENTOS ****************************************************************
var d = new Date();
 var n = d.getTime(); 
var deviceready=false;
var mobileinit=false;
var ready=false;
$(document).on("deviceready", function() {
	deviceready=true;
	todopreparado();
});
$(document).on("mobileinit", function() {
	mobileinit=true;
	todopreparado();
});
$(document).on("ready", function() {
	ready=true;
	todopreparado();
});
function todopreparado(){
	if(deviceready && mobileinit && ready){
		inicializar();
		fileApi.initialize();	
		// Continúa en el evento fileApiCreado
	}
}
function fileApiCreado(){
	//APAÑAMOS EL TEXTAREA DE LOS MENSAJES
	altocontenedor=$("#escritura").height();
	altoeditor=$("#edicionmensaje").height();
	var resto=altocontenedor-altoeditor;
	// Y APAÑAMOS LA ALTURA DE LA PAGINA DE MENSAJES
	$("#edicionmensaje").on("keyup input",function(){
		$("#escritura").css("height",$("#edicionmensaje").height()+resto);
	});
	PonerLiterales();
}
function PonerLiterales(){
	//Aprovechamos la llamada al servidor para poner los literales para obtener también el cartel de la fecha
	data={};
	data.idioma=Usuario.idioma;
	data.FechaFiesta=FechaFiesta;
	jQuery.ajax({type: "POST",dataType: "json",url: ruta+"literales.php",data:data}).done(respuestaPonerLiterales);	
}
function respuestaPonerLiterales(response){
	if(response.cartel==1){
		var camino="url("+ruta+"carteles/"+FechaFiesta+".jpg)";
	}else{
		var camino="url("+ruta+"carteles/general.jpg)";
	}
	literales.si=response.si;
	literales.no=response.no;
	literales.añadirafavoritospregunta=response.añadirafavoritospregunta;
	literales.leenviasteunflas=response.leenviasteunflas;
	literales.teenviounflas=response.teenviounflas;
	literales.elflashasidoenviado=response.elflashasidoenviado;
	//Cartel del dia
	$("#contenido").css("background-image",camino);
	$("#labelentrar").text(response.labelentrar);
	$("#labelenviados").text(response.labelenviados);
	$("#labelrecibidos").text(response.labelrecibidos);
	$("#labelseleccionaelorigendelaimagen").text(response.labelseleccionaelorigendelaimagen);
	$("#labelunafrase").text(response.labelunafrase);
	soy=response.soy;
	megusta=response.megusta;
	añosliteral=response.añosliteral;
	//PONERMOS LOS LITERALES DE LOS HORÓSCOPOS EN EL SELECT DE BUSCAR
	$("#selecthoroscopo").append("<option value=''></option>");
	$("#selecthoroscopo").append("<option value='Aries'>"+response.aries+"</option>");
	$("#selecthoroscopo").append("<option value='Tauro'>"+response.tauro+"</option>");
	$("#selecthoroscopo").append("<option value='Geminis'>"+response.geminis+"</option>");
	$("#selecthoroscopo").append("<option value='Cancer'>"+response.cancer+"</option>");
	$("#selecthoroscopo").append("<option value='Leo'>"+response.leo+"</option>");
	$("#selecthoroscopo").append("<option value='Virgo'>"+response.virgo+"</option>");
	$("#selecthoroscopo").append("<option value='Libra'>"+response.libra+"</option>");
	$("#selecthoroscopo").append("<option value='Escorpio'>"+response.escorpio+"</option>");
	$("#selecthoroscopo").append("<option value='Sagitario'>"+response.sagitario+"</option>");
	$("#selecthoroscopo").append("<option value='Capricornio'>"+response.capricornio+"</option>");
	$("#selecthoroscopo").append("<option value='Acuario'>"+response.acuario+"</option>");
	$("#selecthoroscopo").append("<option value='Piscis'>"+response.piscis+"</option>");
	ObtenerDatosPerfil();
}
function ObtenerDatosPerfil(){
	data={};
	data.id=Usuario.id;
	data.idioma=Usuario.idioma;
	data.discoteca=discoteca.id;
	data.fechafiesta=FechaFiesta;
	jQuery.ajax({type: "POST",dataType: "json",url: ruta+"obtenerperfil.php",data:data}).done(respuestaObtenerDatosPerfil);
}
function respuestaObtenerDatosPerfil(response){
	$("#numerodeflasesenviados").html(response.enviados);
	$("#numerodeflasesrecibidos").html(response.recibidos);
	$("#numerodeflaspops").html(response.flaspops);
	$("#nombreusuario").val(response.nombre);
	$("#pensamiento").val(response.frase);
	$("#sexoliteral").text(response.sexoliteral);
	$("#sexoliteral").attr("data-sexo",response.sexo);
	Usuario.sexo=response.sexo;
	$("#fechadenacimiento").val(response.fechanacimiento);
	$("#admitoliteral").text(response.buscoliteral);
	$("#admitoliteral").attr("data-admito",response.busco);
	Usuario.busco=response.busco;
	var Cadena="";
	$.each(soy,function(i,val){
		Cadena=Cadena +"<tr><td style='width:100%;height:50px;'>"+val+"</td><td><input id='soy"+i+"' class='cmn-toggle cmn-toggle-round' data-role='none' type='checkbox'><label for='soy"+i+"'></label></td></tr>";
	});
	$("#tablasoy").html(Cadena);
	var Cadenabusqueda=Cadena.split("soy").join("buscosoy");
	$("#tablabusquedasoy").html(Cadenabusqueda);
	$.each(soy,function(i,val){
		$("#soy"+i).attr("checked",(response.soy["soy"+i]==1)?true : false);
	});
	var Cadena2="";
	$.each(megusta,function(i,val){
		Cadena2=Cadena2 +"<tr><td style='width:100%;height:50px;'>"+val+"</td><td><input id='megusta"+i+"' class='cmn-toggle cmn-toggle-round' data-role='none' type='checkbox'><label for='megusta"+i+"'></label></td></tr>";
	});
	$("#tablamegusta").html(Cadena2);
	Cadenabusqueda=Cadena2.split("megusta").join("buscomegusta");
	$("#tablabusquedamegusta").html(Cadenabusqueda);
	$.each(megusta,function(i,val){
		$("#megusta"+i).attr("checked",(response.megusta["megusta"+i]==1)?true : false);
	});
	MostrarPantallaPrincipal()
}
function MostrarPantallaPrincipal(){
	while(new Date().getTime()<n+2000){}
	$("#cover").fadeOut();
}
//**************************************************************************************************************
// FUNCIONES QUE SE UTILIZARÁN EN EL FILEAPI
function Existe(fileEntry){
	// Tenemos que leer el fichero y asignar a Usuario.id su valor;
	fileEntry.file(function(file) {
		var reader = new FileReader();
		reader.onloadend = function(e) {
			Usuario.id=this.result;
			document.dispatchEvent(event);
	}
	reader.readAsText(file);
	});
}
function Noexiste(error){
	// Obtenermos un nuevo usuario y lo grabamos en el fichero flaspop.pop
	jQuery.ajax({type: "POST",dataType: "text",url: "http://www.afassvalencia.es/android/flaspop/grabarnoexiste.php"}).done(respuestagrabarnoexiste);
}
function respuestagrabarnoexiste(response){
	Usuario.id=response;
	fileApi.dir.getFile("flaspop.pop", { create : true }, function(fileentry){fileentry.createWriter(function(filewriter){filewriter.write(response);},fileApi.onError)},fileApi.onError);
	document.dispatchEvent(event);
}
//***************************************************************************************************************
// SE INICIALIZA LA PRIMERA PÁGINA
$(document).on("pagecreate", "#principal", function(event){
	
});
function inicializar() {
	// CONSTANTES GENERALES
	literales={};
	discoteca={};
	discoteca.id=1; //Pachá;
	FechaFiesta="";
	ruta="http://www.afassvalencia.es/android/flaspop/";
	usuariosporpagina=30;
	registroelemento=0;
	otroUsuario={};	
	// CREAMOS LA VARIABLE USUARIO SIN INICIALIZAR GLOBAL
	Usuario={};
	// OBTENEMOS LA FECHA DE LA FIESTA DEL SERVIDOR
		jQuery.ajax({type: "POST",dataType: "text",async: false,url: ruta+"fechafiesta.php"}).done(function(response){
		FechaFiesta=response;	
	});		
	// CREACIÓN DE OBJETO FILEAPI PARA TRATAMIENTO DEL PLUGIN FILEAPI
	// Este objeto disparará un objeto cuando fileAPi.dir esté creado
	event = document.createEvent('Event');
	event.initEvent('build', true, true);
	document.addEventListener('build', fileApiCreado, false);
	fileApi = {
		initialize: 		function(){
						window.resolveLocalFileSystemURL(cordova.file.externalDataDirectory, fileApi.onDir, fileApi.onError);
					},
		onDir: 		function(directoryEntry) {
						fileApi.dir = directoryEntry;
						fileApi.dir.getFile("flaspop.pop", { create : false },Existe,Noexiste);
					},
		onError: 		function(err) {
						alert(err.code);
					},
	}
	// INICIALIZAMOS AJAX
	$.ajaxSetup({ cache:false });
	// FASTCLICK
	window.addEventListener('load', function() {
                new FastClick(document.body);
	}, false);
	// INICIAMOS PROPIEDADES DE JQUERYMOBILE
	$.mobile.allowCrossDomainPages = true;
	$.support.cors = true;
	$.mobile.buttonMarkup.hoverDelay = 0;
	$.mobile.pushStateEnabled = false;
	$.mobile.defaultPageTransition = "none";	
	// COMPROBAMOS CONEXIÓN A INTERNET
	ComprobarConexion();
	// INICIAMOS EL OBJETO USUARIO
	//Usuario=new claseUsuario();
	Usuario.id=0;
	// COMPROBAMOS EL IDIOMA
	switch(navigator.language.substring(0, 2)){
			case "es":
				Usuario.idioma=1;
				break;
			case "en":
				Usuario.idioma=2;
				break;
			default:
				Usuario.idioma=3;
				break;
	}
	// CONTROLAMOS SI SE PIERDA LA CONEXIÓN A INTERNET
	document.addEventListener("offline", ComprobarConexion, false);
	// INICIAMOS LAS OPCIONES DE TOASTR
	toastr.options = {
	  "closeButton": false,
	  "debug": false,
	  "newestOnTop": false,
	  "progressBar": false,
	  "positionClass": "toast-bottom-center",
	  "preventDuplicates": false,
	  "showDuration": "100",
	  "hideDuration": "1000",
	  "timeOut": "3000",
	  "extendedTimeOut": "1000",
	  "showEasing": "swing",
	  "hideEasing": "linear",
	  "showMethod": "fadeIn",
	  "hideMethod": "fadeOut"
	}
	/*
	//ESTABLECEMOS LAS OPCIONES POR DEFECTO DE LA CAMARA
	camara.defaults = {
		quality : 100,
		destinationType : Camera.DestinationType.DATA_URL,
		sourceType : Camera.PictureSourceType.CAMERA,
		allowEdit : false,
		encodingType: Camera.EncodingType.JPEG,
		//popoverOptions: CameraPopoverOptions,
		saveToPhotoAlbum: false,
		correctOrientation: true
	}
	//camara.initialize();
	*/
	const push = PushNotification.init({
		android: {
		},
		ios: {
			alert: "true",
			badge: true,
			sound: 'false'
		},
		windows: {}
	});
	push.on('registration', (data) => {
		Usuario.token=data.registrationId;
	});
	push.on('notification', (data) => {
		alert(data.message);
		alert(data.additionalData.foto);
		console.log(data.message);
		console.log(data.title);
		console.log(data.count);
		console.log(data.sound);
		console.log(data.image);
		console.log(data.additionalData);
	});
	push.on('error', (e) => {
		console.log(e.message);
	});
}
function ComprobarConexion() {
    if(navigator.connection.type==Connection.NONE){
	$.mobile.pageContainer.pagecontainer("change", "#sinconexion", {
		transition:"fade",
	});
	return false;
    }
    return true;
}
/*
camara = {
  initialize: function() {
    camara.defaults = {
      quality : 100,
      destinationType : Camera.DestinationType.DATA_URL,
      sourceType : Camera.PictureSourceType.CAMERA,
      allowEdit : false,
      encodingType: Camera.EncodingType.JPEG,
      //popoverOptions: CameraPopoverOptions,
      saveToPhotoAlbum: false,
      correctOrientation: true
    }
	//$('#labelcamara').on('click', camara.sacarFoto);
    },
  sacarFoto: function() {
	navigator.camera.getPicture(camara.onDataUrlSuccess, camara.onError, camara.defaults);
  },
  onDataUrlSuccess: function(imageData) {
    // cuando la camara se cierre se ejecutara esta funcion
    // y usaremos "imageData" como src del tag <img>
   $("#imagencrop").attr('src', "data:image/jpeg;base64," + imageData);	  

   //var image=document.getElementById('imagencrop');
   //image.src=imageData;
   quitardedondefoto();	  
   picture = $("#imagencrop");
   $("#paginaguillotine").fadeIn("slow",function(){
					  // Must be already loaded or cached!
					//picture.attr("src", "data:image/png;base64,"+cadenafoto);
					//picture.guillotine({width:300, height:300});
					//picture.guillotine("fit");
				});	
    //$('#fotoperfil').attr('src', "data:image/jpeg;base64," + imageData);
    //picture.guillotine({width:300, height:300});
    //picture.guillotine("fit");	  
  },
  onError: function(message){
    //alert('Error: ' + message);
	// PONER AQUÍ LO QUE QUEREMOS QUE HAGA SI NO SE HA SELECCIONADO UNA IMAGEN
  }
};
*/
function BotonAtras(){
	switch($(":mobile-pagecontainer" ).pagecontainer("getActivePage").attr("id")){
		case "principal" :
			alert("aquí avisaremos de salir de la aplicación");
			break;
		case "perfil" :
			if($("#sexo").css("display")=="block"){
					quitarseleccionarsexo();
			}else if($("#admito").css("display")=="block"){
					quitaradmito();
			}else if($("#paginaguillotine").css("display")=="block"){
					$("#paginaguillotine").fadeOut();
			}else if($("#dedondefoto").css("display")=="block"){
					quitardedondefoto();
			}else{
					var id=$( ":mobile-pagecontainer" ).pagecontainer("getActivePage").data("prevPag");
						$.mobile.pageContainer.pagecontainer("change", "#"+id,{
						transition:"flip",
					});
			}
			break;
		case "detalles" :
			if(swal.isVisible()){
				
				swal.close();
			}else{
				
				var id=$( ":mobile-pagecontainer" ).pagecontainer("getActivePage").data("prevPag");
				
				$.mobile.pageContainer.pagecontainer("change", "#"+id,{
					transition:"flip",
				});	
			};
			break;
		default :
			var id=$( ":mobile-pagecontainer" ).pagecontainer("getActivePage").data("prevPag");
			$.mobile.pageContainer.pagecontainer("change", "#"+id,{
					transition:"flip",
			});
			break;
	}
}
function informacion(){
 $( ":mobile-pagecontainer" ).pagecontainer( "change", "informacion.html");
}

function entrar(){
	document.addEventListener("backbutton", BotonAtras, true);
	$("#entrar").css("display","none");
	$("#pie").css("visibility","visible");
	$(".imagenmenu").css("opacity","1");
	$("#contenido").css("background","url('./imagenes/await2.gif') no-repeat fixed center");
	$("#topflases").css("display","block");
	vertopflases();
	data={};
	data.id=Usuario.id;
	data.token=Usuario.token;
	data.plataforma=device.platform;
	jQuery.ajax({type: "POST",dataType: "text",url: ruta +"guardartoken.php",data:data}).done(respuestaguardartoken);	
	//jQuery.ajax({type: "POST",dataType: "text",url: ruta +"notification.php",data:data}).done(respuestanotification);
	//Grabamos el token en la base de datos;
	
}
function respuestaguardartoken(response){
}
function respuestanotification(response){
	//alert(response);
}
// ALMACENAMIENTO DE LA PAGINA ANTERIOR
function GuardarPaginaAnterior(NuevaPagina){
	id=$( ":mobile-pagecontainer" ).pagecontainer("getActivePage").attr("id");
	$("#"+NuevaPagina).data("prevPag",id);
}
function verpaginafavoritos(){
	$("#pensando").fadeIn();
	$("#resultadofavoritos").html("");
	GuardarPaginaAnterior("favoritos");
	$.mobile.pageContainer.pagecontainer("change", "#favoritos", {
		transition:"flip",
	});
}
function verpaginabusqueda(){
	if($("#menuverpaginabusqueda").css("opacity")<1){return;}
	$("#pensando").fadeIn();
	GuardarPaginaAnterior("busqueda");
	$("#edad1").val("");
	$("#edad1").change();
	$("#edad2").val("");
	$("#edad2").change();
	$.mobile.pageContainer.pagecontainer("change", "#busqueda", {
		transition:"flip",
	});
}
$(document).on("pagecreate", "#busqueda", function(event){
	var cadenaedad="";
	cadenaedad=cadenaedad+"<option value=''></option>";
	for(x=18;x<100;x++){
		cadenaedad=cadenaedad+"<option value="+x+">"+x+"</option>";
	}
	$("#edad1").html(cadenaedad);
	$("#edad2").html(cadenaedad);
	$( "#edad1" ).change(function() {
		if($("#edad1").val()==""){
			$("#edad2").val("");
			$("#edad2").change();
		}else{
			var valor=$("#edad1").val();
			$("#edad2 option").each(function(){
				if($(this).val()<valor && $(this).val()!=""){
					$(this).hide();
				}else{
					$(this).show();
				}
			});
		}
	});
});
$(document).on("pageshow", "#busqueda", function(event){
	$("#pensando").fadeOut();
});
function verpaginaperfil(){
	if($("#menuverpaginaperfil").css("opacity")<1){return;}
	if(ComprobarConexion()==false){
		return;
	}	
	$("#pensando").fadeIn();	
	GuardarPaginaAnterior("perfil");
	$.mobile.pageContainer.pagecontainer("change", "#perfil", {
		transition:"flip",
	});
}
function vertopflases(){
	data={};
	data.sexo=Usuario.sexo;
	data.busco=Usuario.busco;
	data.discoteca=discoteca.id;
	data.FechaFiesta=FechaFiesta;
	jQuery.ajax({type: "POST",dataType: "json",url: ruta +"topflases.php",data:data}).done(respuestavertopflases);
}
function respuestavertopflases(response){
	longitud=response.resultado.length*106;
	var cadena="<ul id=\"top\" style=\"position:relative;width:"+longitud+"px;margin-left:4px;\">";
	$.each(response.resultado,function( indice,elemento ) {
		cadena=cadena+"<li><figure class='post_image'><img src=\'"+ruta+"fotosperfiles/"+elemento.foto+"\' style=\"width:100%;\" onclick=\"detalles("+elemento.id+")\"/></figure></li>";
		new Image().src=elemento.foto;
	});
	cadena=cadena+"</ul>";
	$("#topscroll").html(cadena).promise().done(function(){
		$("#contenido").css("background","");
	});
}
function vermensajesgeneral(){
	$("#pensando").fadeIn();
	data={};
	data.id=Usuario.id;
	data.discoteca=discoteca.id;
	data.FechaFiesta=FechaFiesta;
	jQuery.ajax({type: "POST",dataType: "json",url: ruta+"mensajesgeneral.php",data:data}).done(respuestavermensajesgeneral);	
}
function respuestavermensajesgeneral(response){
	var cadena="";
	$.each(response,function(indice,elemento){
		cadena=cadena+ponermensaje(elemento);
	});
	GuardarPaginaAnterior("mensajesgeneral");
	$("#contenidomensajesgeneral").html(cadena);
	$.mobile.pageContainer.pagecontainer("change", "#mensajesgeneral", {
		transition:"flip",
	});
	$("#pensando").fadeOut();
}
function mensajesprivados(){
	$("#pensando").fadeIn();
	data={};
	data.id=Usuario.id;
	data.id2=otroUsuario.id;
	data.discoteca=discoteca.id;
	data.fechafiesta=FechaFiesta;
	jQuery.ajax({type: "POST",dataType: "json",url: ruta+"obtenermensajes.php",data:data}).done(respuestamensajesprivados);	
}
function respuestamensajesprivados(response){
	var cadena="";
	$.each(response,function(indice,elemento){
		if(elemento["CodigoUsuario1"]==Usuario.id){
			cadena=cadena+"<div class='yo'><img src='http://www.afassvalencia.es/android/flaspop/fotosperfiles/337.jpg'><p>"+elemento["Texto"]+"</p><p>"+elemento["hora"]+"</p></div>";
		}else{
			cadena=cadena+"<div class='tu'><img src='http://www.afassvalencia.es/android/flaspop/fotosperfiles/11.jpg'><p>"+elemento["Texto"]+"</p><p>"+elemento["hora"]+"</p></div>";
		}
	});
	$("#contenidomensajes").html(cadena);
	GuardarPaginaAnterior("mensajesprivados");
	$("#imagenenbar").attr("src",$("#imagendetalles").attr("src"));
	$("#labelmensajesprivados").text($("#labeldetalles").text());
	$.mobile.pageContainer.pagecontainer("change", "#mensajesprivados", {
		transition:"flip",
	});
	$("#contenidomensajes").animate({ scrollTop: $(document).height()}, 600);
	$("#pensando").fadeOut();	
}
function grabarmensaje(){
	if($("#edicionmensaje").val()==""){return;}
	data={};
	data.id=Usuario.id;
	data.id2=otroUsuario.id;
	data.mensaje=$("#edicionmensaje").val();
	data.discoteca=discoteca.id;
	data.fechafiesta=FechaFiesta;
	jQuery.ajax({type: "POST",dataType: "text",url: ruta+"grabarmensaje.php",data:data}).done(respuestagrabarmensaje);	
}
function respuestagrabarmensaje(response){
	var cadena="<div class='yo'><img src='http://www.afassvalencia.es/android/flaspop/fotosperfiles/337.jpg'><p>"+$("#edicionmensaje").val()+"</p><p>"+response+"</p></div>"
	altoeditor=$("#edicionmensaje").height();
	$("#edicionmensaje").val("");
	$("#edicionmensaje").css("height",altoeditor);
	$("#escritura").css("height",altocontenedor+18);
	$("#contenidomensajes").append(cadena);
	$("#contenidomensajes").animate({ scrollTop: $(document).height()}, 600);
}
function encodeImagetoBase64(element) {
	  var canvas = document.createElement("canvas");
          alert(element.width);
	  canvas.width = element.width;
          canvas.height = element.height;
          var ctx = canvas.getContext("2d");
          ctx.drawImage(element, 0, 0);
	  var fullQuality = canvas.toDataURL('image/jpeg', 1.0);
	return fullQuality;
}
function subirfoto(){
	$("#pensando").fadeIn();
	toastr.options.timeOut = 0;
        toastr.options.extendedTimeOut = 0;
	toastr.info("Subiendo foto al servidor");
	data = $("#imagencrop").guillotine('getData');
	data.cadenafoto=base64;
	//var element=document.getElementById("imagencrop");
	//data.cadenafoto=encodeImagetoBase64(element);
	//data.cadenafoto=data.cadenafoto.replace("data:image/jpeg;base64,","");
	//alert(data.cadenafoto);
	data.id=Usuario.id;
	jQuery.ajax({type: "POST",dataType: "text",url: ruta+"subirfoto.php",data: data}) .done(respuestasubirfoto);
}
function respuestasubirfoto(respuesta){
	var d = new Date();
	var n = d.getTime();
	$("#fotoperfil").attr("src",ruta+"fotosperfiles/"+Usuario.id+".jpg?ver="+n);
	$("#paginaguillotine").hide();
	$("#pensando").fadeOut();
	toastr.options.timeOut = 3000;
        toastr.options.extendedTimeOut = 1000;
	toastr.clear();
	return;
	$("#pensando").fadeOut("slow",function(){
		$('#thepicture').guillotine('remove');
		//$("#subirfoto").css("display","none");
		//$("#pagina2").fadeIn("slow");
		//paginaactual="pagina2";
	});	
	
	//$("#fotoperfil").attr("src",ruta+"fotosperfiles/"+Usuario.id+".jpg?ver="+n);
}
$(document).on("pagecreate", "#perfil", function(event){
	ancho=$(window).width();
	ancholiteral=(ancho*75/100)+"px";
	$("#foto").css("height",ancholiteral);
	$("#fotoperfil").attr("src","http://www.afassvalencia.es/android/flaspop/fotosperfiles/"+Usuario.id+".jpg");
	var myElement = document.getElementById('sexo');
	var mc = new Hammer(myElement);
	mc.get('pan').set({ direction: Hammer.DIRECTION_ALL });
	mc.on("pandown", function(ev) {
		quitarseleccionarsexo();
	});
	//zoomInmouse();
	var myElement=document.getElementById("imagencrop");
	var mc=new Hammer(myElement);
	mc.get('pinch').set({enable: true});
	mc.on("pinchout",function(ev){
		$("#imagencrop").guillotine("zoomIn");
	});
	mc.on("pinchin",function(ev){
		$("#imagencrop").guillotine("zoomOut");
	});
	var myElement2 = document.getElementById('admito');
	var mc = new Hammer(myElement2);
	mc.get('pan').set({ direction: Hammer.DIRECTION_ALL });
	mc.on("pandown", function(ev) {
		quitaradmito();
	});
	var myElement3 = document.getElementById('dedondefoto');
	var mc = new Hammer(myElement3);
	mc.get('pan').set({ direction: Hammer.DIRECTION_ALL });
	mc.on("pandown", function(ev) {
		quitardedondefoto();
	});
	$('#labelcamara').on('click', function(){
		//camara.defaults.sourceType = Camera.PictureSourceType.CAMERA;
		sacofoto(1);
	});	
	$('#labelgaleria').on('click', function(){
		//camara.defaults.sourceType = Camera.PictureSourceType.PHOTOLIBRARY;
		sacofoto(2);
	});
	$("#pensamiento").keyup(function(){
		$("#maximopensamiento").text(150-($("#pensamiento").val().length));
		
	});
	$("#pensando").fadeOut();
});
function sacofoto(tipo){
	quitardedondefoto();	  
	if(tipo==1){
		navigator.camera.getPicture(Exito, Fracaso,{ quality: 100, destinationType: Camera.DestinationType.DATA_URL,sourceType : Camera.PictureSourceType.CAMERA,correctOrientation: true});
	}else if(tipo==2){
		navigator.camera.getPicture(Exito, Fracaso,{ quality: 100, destinationType: Camera.DestinationType.DATA_URL,sourceType : Camera.PictureSourceType.PHOTOLIBRARY,correctOrientation: true});
	}
	//navigator.camera.getPicture(Exito, camara.onError,camara.defaults);
	
}
var base64;
function Exito(imageData){
	base64=imageData;
	picture = $('#imagencrop');  
	picture.guillotine("remove");
	$("#imagencrop").attr('src', "data:image/jpeg;base64," + imageData);	  
	$("#imagencrop").src=imageData;
	//image=document.getElementById("imagencrop");
	//image.src=imageData;
	quitardedondefoto();	  
	$("#paginaguillotine").fadeIn("slow",function(){
		picture = $('#imagencrop');  
		picture.guillotine({width:300, height:300,zoomStep: 0.01});
		picture.guillotine("fit");
	});	
}
function Fracaso(){
	
};
$(document).on("pageshow", "#perfil", function(event){
	$("#pensando").fadeOut();
	$("#perfil").animate({ scrollTop: 0 }, 600);
});
$(document).on("pageshow", "#favoritos", function(event){
	//mostrarfavoritos();
});
$(document).on("pageshow", "#resultadobusqueda", function(event){
	$("#pensando").fadeOut();
	//$("#resultadobusqueda").animate({ scrollTop: 0 }, 600);
});
function verflasesestados(tipo){
	$("#pensando").fadeIn();
	data={};
	data.id=Usuario.id;	
	data.idioma=Usuario.idioma;
	data.discoteca=discoteca.id;
	data.fechafiesta=FechaFiesta;
	data.tipo=tipo;
	jQuery.ajax({type: "POST",dataType: "json",url: "http://www.afassvalencia.es/android/flaspop/verflasesestados.php",data:data}).done(respuestaverflasesestados);
}
function respuestaverflasesestados(response){
	GuardarPaginaAnterior("flasesestados");
	$("#labelflasesestados").text(response.titulo);
	var cadena="";
	$.each(response.datos,function(indice,elemento){
		if(elemento["_id"]!=Usuario.id){
			cadena=cadena+PonerFicha(elemento,response.flasl,response.flast);
		}	
	});
	$("#contenidoflasesestados").html("");
	$("#flasesestados").animate({ scrollTop: 0 }, 600);
	$("#contenidoflasesestados").html(cadena);
	$.mobile.pageContainer.pagecontainer("change", "#flasesestados", {
		transition:"flip",
	});
	$("#pensando").fadeOut();
}
function mostrarfavoritos(){
	if($("#menumostrarfavoritos").css("opacity")<1){return;}
	$("#pensando").fadeIn();
	data={};
	data.id=Usuario.id;	
	data.idioma=Usuario.idioma;
	data.discoteca=discoteca.id;
	data.fechafiesta=FechaFiesta;	
	jQuery.ajax({type: "POST",dataType: "json",url: "http://www.afassvalencia.es/android/flaspop/verfavoritos.php",data:data}).done(respuestamostrarfavoritos);
}
function respuestamostrarfavoritos(response){
	GuardarPaginaAnterior("favoritos");
	var cadena="";
	$.each(response.datos,function(indice,elemento){
		if(elemento["_id"]!=Usuario.id){
			cadena=cadena+PonerFicha(elemento,response.flasl,response.flast);
		}	
	});
	$("#contenidofavoritos").html("");
	$("#favoritos").animate({ scrollTop: 0 }, 600);
	$("#contenidofavoritos").html(cadena);
	$.mobile.pageContainer.pagecontainer("change", "#favoritos", {
		transition:"flip",
	});
	$("#pensando").fadeOut();
}
function buscar(){
	$("#pensando").fadeIn("slow");
	data={};
	data.elemento=registroelemento;	
	data.id=Usuario.id;
	data.discoteca=discoteca.id;
	data.fechafiesta=FechaFiesta;
	data.usuariosporpagina=usuariosporpagina;
	data.idioma=Usuario.idioma;
	data.sexo=Usuario.sexo;
	data.busco=Usuario.busco;
	data.nombre=$("#nombrebusquedausuario").val().toUpperCase();
	data.horoscopo=$("#selecthoroscopo").val();
	data.edad1=$("#edad1").val();
	data.edad2=$("#edad2").val();
	if(data.edad2=="" && data.edad1!=""){
		data.edad2=99;
	}
	var cadena="";
	$.each(soy,function(i,val){
		if($("#buscosoy"+i).prop("checked")==true){
			cadena=cadena+"soy"+i+"=1,";
		}
	});
	data.datasoy= cadena.slice(0,-1);
	cadena="";
	$.each(megusta,function(i,val){
		if($("#buscomegusta"+i).prop("checked")==true){
			cadena=cadena+"megusta"+i+"=1,";
		}
	});
	data.datamegusta=cadena.slice(0,-1);
	jQuery.ajax({type: "POST",dataType: "json",url: ruta+"buscargeneral.php",data:data}).done(respuestabuscar);
}
function respuestabuscar(response){
	GuardarPaginaAnterior("resultadobusqueda");
	var cadena="";
	$.each(response.datos,function(indice,elemento){
		if(elemento["_id"]!=Usuario.id){
			cadena=cadena+PonerFicha(elemento,response.flasl,response.flast);
		}	
	});
	if(registroelemento+usuariosporpagina<response.totalderegistros){
		cadena=cadena+`<div class="buscarmas fondo" onclick="vermas('$tipo');" style=\"margin-top:40px;margin-bottom:40px;">
							<p class="colorletra" style="width:100%;text-align:center;font-size:20px;font-style:italic;">VER MAS</p>
					</div>`;
	}	
	registroelemento=registroelemento+usuariosporpagina-1;
	$("#contenidoresultadobusqueda").html("");
	$("#resultadobusqueda").animate({ scrollTop: 0 }, 600);
	$("#contenidoresultadobusqueda").html(cadena);
	$.mobile.pageContainer.pagecontainer("change", "#resultadobusqueda", {
		transition:"flip",
	});
	$("#pensando").fadeOut();
}
function PonerFicha(elemento,flasl,flast){
	var id=elemento["_id"];
	var elementofoto=ruta+"fotosperfiles/"+elemento["foto"];
	var cadenafavoritos="";
	if(elemento["esfavorito"]==1){
		cadenafavoritos="<img id='favorito"+elemento["_id"] +"' style='display:block;margin:-4px 1px 0 0 !important;' src='imagenes/favoritos2.png' />";
	}else{
		cadenafavoritos="<img id='favorito"+elemento["_id"] +"' style='display:none;margin:-4px 1px 0 0 !important;' src='imagenes/favoritos2.png' />";
	}
	var cadenaflases="";
	if(elemento["ParaTi"]==1 && elemento["ParaMi"]==1){
		cadenaflases="FLASPOP";
	}else if(elemento["ParaTi"]==1){
		cadenaflases=flasl;
	}else if(elemento["ParaMi"]==1){
		cadenaflases=flast;
	}	
	var cadena=`<div class="fondo" style="width:100%;overflow:hidden;" onclick="detalles(${id});">
				<div style="display:block;float:left;background-color:white;padding:6px !important;width:99%;border:1px solid gray;border-radius:6px;-webkit-box-shadow: 1px 2px 5px 0px rgba(0,0,0,0.90);-moz-box-shadow: 1px 1px 5px 0px rgba(0,0,0,0.90);box-shadow: 1px 1px 5px 0px rgba(0,0,0,0.90)";>
					<img class="imagenfoto" style="display:block;float:left;width:40%;border-radius:6px 0 0 6px;padding:0 !important" src="${elementofoto}" />
					<div class="anchodecelda" style="display:block;float:left;">
						<div class="flexcolumn" style="justify-content:space-between;width:100%;height:100%;padding:0 ! important">
							<div class="flexrow w100" style="justify-content: flex-end;">
								${cadenafavoritos}
								<img src="${elemento['esta']}" />
							</div>
							<div class="flexcolumn">
								<p style="color:rgb(100,100,100);font-family:Arial;font-style:bold;font-size:24px;margin-top:4px !important;">${elemento["Nombre"]}</p>
								<p style="font-size:14px;color:rgb(100,100,100);margin-top:0px !important;font-style:italic;">${elemento["edad"]} ${añosliteral}</p>
							</div>
							<span id="cadena${id}" style="margin:0;margin-top:2px !important;padding:0;font-style:bold;color:#D4AF37;font-size:14px;">${cadenaflases}</span>
						</div>
					</div>
				</div>				
			</div>
			<hr class="uno" />
			<hr class="dos" />`;
							
	/*
	var cadena="<div class=\"fondo\">"+
	"			<div class=\"cajadetalle\">"+
	"				<img class=\"imagenfoto\" src=\""+ruta+"fotosperfiles/"+elemento["foto"]+"\" />"+
	"			</div>"+
	"	           </div>";	
	*/
	return cadena;			
}
function ponermensaje(elemento){
	var id=elemento["Persona"];
	var elementofoto=ruta+"fotosperfiles/"+elemento["foto"];
	var nombre=elemento["Nombre"];
	var hora=elemento["hora"];
	var texto=elemento["Texto"];
	var cadena=`<div class="flexrow" style="width:100%;" onclick="otroUsuario.id=${id};mensajesprivados();">
				<div style="width:30%;">
					<img src="${elementofoto}" style="width:100%;border-radius:50%;">
				</div>
				<div style="width:70%;padding-left:10px;">
					<div style="fflexcolumn" style="width:100%;justify-content:space-between">
						<span style="font-weight:bold !important;">${nombre}</span>
						<span style="float:right;font-size:12px;font-style:italic;color:blue;">${hora}</span>
					</div>
					<p style="margin-top:4px;">${texto}</p>
				</div>
			</div>
			<div class="raya"></div>`;
	return cadena;
}
function detalles(id){
	otroUsuario.id=id;
	$("#pensando").fadeIn();
	GuardarPaginaAnterior("detalles");
	var data={};
	data.id2=id;
	data.id=Usuario.id;
	data.discoteca=discoteca.id;
	data.fechafiesta=FechaFiesta;
	data.idioma=Usuario.idioma;
	jQuery.ajax({type: "POST",dataType: "json",url: ruta+"detalleusuario.php",data:data}).done(respuestadetalles);	
}
function respuestadetalles(response){
	if(response.esfavorito=="1"){
		$("#añadirfavoritos").css("opacity","0.2");
		$("#favoritoendetalles").show();
	}else{
		$("#añadirfavoritos").css("opacity","1");
		$("#favoritoendetalles").hide();		
	}
	if(response.parati=="1"){
		if(response.parami=="1"){
			$("#enviarflas").css("opacity","0.2");
			$("#enviarpop").css("opacity","0.2");
			$("#enviarmensaje").css("opacity","1");
			$("#divflas").hide();
			$("#divflaspop").show();
		}else{
			$("#enviarflas").css("opacity","0.2");
			$("#enviarpop").css("opacity","0.2");
			$("#enviarmensaje").css("opacity","0.2");
			$("#divflas p").text("Le enviaste un");
			$("#divflaspop").hide();
			$("#divflas").show();
		}
	}else{
		if(response.parami=="1"){
			$("#enviarflas").css("opacity","0.2");
			$("#enviarpop").css("opacity","1");
			$("#enviarmensaje").css("opacity","0.2");
			$("#divflaspop").hide();
			$("#divflas p").text("Te envió un");
			$("#divflas").show();
		}else{
			$("#enviarflas").css("opacity","1");
			$("#enviarpop").css("opacity","0.2");
			$("#enviarmensaje").css("opacity","0.2");
			$("#divflas").hide();
			$("#divflaspop").hide();
		}
	}
	$("#labeldetalles").text(response.nombre);
	$("#imagendetalles").attr("src",ruta+"fotosperfiles/"+response.foto);
	$("#imagendetalles").prop("tag",ruta+"fotosperfiles/"+response.foto);
	$("#detallefrase").text(response.frase);
	$("#imagenhoroscopo").attr("src",ruta+"/horoscopo/"+response.fotohoroscopo);
	$("#textohoroscopo").text(response.horoscopo);
	$("#años").text(response.edad);
	$.mobile.pageContainer.pagecontainer("change", "#detalles", {
		transition:"flip",
	});
	$("#pensando").fadeOut();
	return;
	
	$("#pensamiento").val(response.frase);
	$("#sexoliteral").text(response.sexoliteral);
	$("#sexoliteral").attr("data-sexo",response.sexo);
	Usuario.sexo=response.sexo;
	$("#fechadenacimiento").val(response.fechanacimiento);
	$("#admitoliteral").text(response.buscoliteral);
	$("#admitoliteral").attr("data-admito",response.busco);
	Usuario.busco=response.busco;
	var Cadena="";
	$.each(soy,function(i,val){
		Cadena=Cadena +"<tr><td style='width:100%;height:50px;'>"+val+"</td><td><input id='soy"+i+"' class='cmn-toggle cmn-toggle-round' data-role='none' type='checkbox'><label for='soy"+i+"'></label></td></tr>";
	});
	$("#tablasoy").html(Cadena);
	var Cadenabusqueda=Cadena.split("soy").join("buscosoy");
	$("#tablabusquedasoy").html(Cadenabusqueda);
	$.each(soy,function(i,val){
		$("#soy"+i).attr("checked",(response.soy["soy"+i]==1)?true : false);
	});
	var Cadena2="";
	$.each(megusta,function(i,val){
		Cadena2=Cadena2 +"<tr><td style='width:100%;height:50px;'>"+val+"</td><td><input id='megusta"+i+"' class='cmn-toggle cmn-toggle-round' data-role='none' type='checkbox'><label for='megusta"+i+"'></label></td></tr>";
	});
	$("#tablamegusta").html(Cadena2);
	Cadenabusqueda=Cadena2.split("megusta").join("buscomegusta");
	$("#tablabusquedamegusta").html(Cadenabusqueda);
	$.each(megusta,function(i,val){
		$("#megusta"+i).attr("checked",(response.megusta["megusta"+i]==1)?true : false);
	});
	MostrarPantallaPrincipal()
}
function añadirfavoritos(){
	if($("#añadirfavoritos").css("opacity")<1){return;}
	swal({
		//type: "question",
		padding:"10px",
		title: $("#labeldetalles").text(),
		text: literales.añadirafavoritospregunta,
		imageUrl: $("#imagendetalles").attr("src"),
		imageWidth: "80%",
		showCancelButton: true,
		confirmButtonText: literales.si,
		cancelButtonText: literales.no,
		reverseButtons: true,
		allowOutsideClick: false,
		backdrop: "rgba(100,100,100,0.85)",
		height: "25%",
	}).then((result) => {
		if(result.value){
			grabarfavoritos();
		}	
	});
}
function grabarfavoritos(){
	swal.close();
	$("#pensando").fadeIn();
	var data={};
	data.id=Usuario.id;
	data.id2=otroUsuario.id;
	data.idioma=Usuario.idioma;
	jQuery.ajax({type: "POST",dataType: "text",url: ruta+"anadirafavoritos.php",data:data}).done(respuestagrabarfavoritos);	
}
function respuestagrabarfavoritos(response){
	$("#pensando").fadeOut();
	$("#favoritoendetalles").show();
	$("#añadirfavoritos").css("opacity","0.1");
	$("#favorito"+otroUsuario.id).css("display","block");
	//$("#quitardefavoritos").show();
}
function quitardefavoritos(){
	swal({
		//type: "question",
		padding:"10px",
		title: $("#labeldetalles").text(),
		text: "¿ELIMINAR DE FAVORITOS?",
		imageUrl: $("#imagendetalles").attr("src"),
		imageWidth: "80%",
		showCancelButton: true,
		confirmButtonText: "SI",
		cancelButtonText: "NO",
		reverseButtons: true,
		allowOutsideClick: false,
		backdrop: "rgba(100,100,100,0.85)",
		height: "25%",
	}).then((result) => {
		if(result.value){
			eliminarfavoritos();
		}	
	});
}
function eliminarfavoritos(){
	swal.close();
	$("#pensando").fadeIn();
	GuardarPaginaAnterior("detalles");
	var data={};
	data.id=Usuario.id;
	data.id2=otroUsuario.id;
	data.idioma=Usuario.idioma;
	jQuery.ajax({type: "POST",dataType: "json",url: ruta+"quitardefavoritos.php",data:data}).done(respuestaeliminarfavoritos);	
}
function respuestaeliminarfavoritos(response){
	$("#pensando").fadeOut();
	$("#favoritoendetalles").hide();
	$("#añadirfavoritos").css("opacity","1");
	$("#favorito"+otroUsuario.id).css("display","none");
}	
function enviarflas(){
	if($("#enviarflas").css("opacity")<1){return;}
	swal({
		padding:"10px",
		title: $("#labeldetalles").text(),
		html: "<img src='imagenes/flas.gif'/><br/><span>Enviar </span><span>F</span><span>L</span><span>A</span><span>S</span>",
		imageUrl: $("#imagendetalles").attr("src"),
		imageWidth: "80%",
		showCancelButton: true,
		confirmButtonText: "SI",
		cancelButtonText: "NO",
		reverseButtons: true,
		allowOutsideClick: false,
		backdrop: "rgba(100,100,100,0.85)",
		height: "25%",
	}).then((result) => {
		if(result.value){
			guardarflas();
		}	
	});
}
function guardarflas(){
	swal.close();
	$("#pensando").fadeIn();
	var data={};
	data.id=Usuario.id;
	data.id2=otroUsuario.id;
	data.idioma=Usuario.idioma;
	data.discoteca=discoteca.id;
	data.fechafiesta=FechaFiesta;
	jQuery.ajax({type: "POST",dataType: "text",url: ruta+"enviarflas.php",data:data}).done(respuestaguardarflas);	
}
function respuestaguardarflas(response){
	$("#mensajeflas").show();
	$("#enviarflas").css("opacity","0.1");
	$("#cadena"+otroUsuario.id).text(literales.leenviasteunflas);
	var fenviados=$("#numerodeflasesenviados").text();
	$("#numerodeflasesenviados").text(parseInt(fenviados)+1);
	$("#pensando").fadeOut(); 
	toastr.success(literales.elflashasidoenviado,"OK");
}
function introducirnombre(){
      $('#inputintroducirnombre').on("keydown",function(event){
		if (event.keyCode == 13) {
			quitarintroducirnombre();
		}
	});
	$("#todo").fadeIn();
	$("#introducirnombre").slideDown(300);
	$("#inputintroducirnombre").focus();
}
function quitarintroducirnombre(){
	$("#introducirnombre").slideUp(300);
	$("#introducirnombre").blur();
	$("#todo").fadeOut();
}
function seleccionarsexo(){
	$("#todo").fadeIn();
	$("#sexo").slideDown(300);
}
function quitarseleccionarsexo(){
	$("#sexo").slideUp(300);
	$("#todo").fadeOut();
}
function devolversexo(sexo){
	var sexocadena;
	if(sexo==1){
		sexocadena="HOMBRE";
	}else{
		sexocadena="MUJER";
	}
	$("#sexoliteral").html(sexocadena);
	$("#sexoliteral").attr("data-sexo",sexo);
	quitarseleccionarsexo();
}
function seleccionaradmito(){
	$("#todo").fadeIn();
	$("#admito").slideDown(300);
}
function quitaradmito(){
	$("#admito").slideUp(300);
	$("#todo").fadeOut();
}
function devolveradmito(sexo){
	var sexocadena;
	if(sexo==1){
		sexocadena="HOMBRES";
	}else if(sexo==2){
		sexocadena="MUJERES";
	}else{
		sexocadena="HOMBRES Y MUJERES"
	}
	$("#admitoliteral").html(sexocadena);
	$("#admitoliteral").attr("data-admito",sexo);
	quitaradmito();
}
function grabarperfil(){
	$("#pensando").fadeIn("slow");
	data={};
	data.id=Usuario.id;
	data.idioma=Usuario.idioma;
	data.nombre=$("#nombreusuario").val().toUpperCase();
	data.frase=$("#pensamiento").val();
	data.sexo=$("#sexoliteral").attr("data-sexo");
	data.fecha=$("#fechadenacimiento").val();
	data.busco=$("#admitoliteral").attr("data-admito");
	var cadena="";
	$.each(soy,function(i,val){
		if($("#soy"+i).prop("checked")==true){
			cadena=cadena+"soy"+i+"=1,";
		}else{
			cadena=cadena+"soy"+i+"=0,";
		}
	});
	data.datasoy= cadena.slice(0,-1);
	cadena="";
	$.each(megusta,function(i,val){
		if($("#megusta"+i).prop("checked")==true){
			cadena=cadena+"megusta"+i+"=1,";
		}else{
			cadena=cadena+"megusta"+i+"=0,";
		}
	});
	data.datamegusta=cadena.slice(0,-1);
	jQuery.ajax({type: "POST",dataType: "json",url: ruta+"grabarusuario.php",data:data}).done(respuestaenviar);
}
function respuestaenviar(respuesta){
	//Si ha tenido éxito cambios las propiedades del objeto Usuario.
	//Si Usuario.id=0 Tenemos que crear el fichero flaspop.pop con el dato del id
	$("#pensando").fadeOut("slow");
	if(respuesta.resultado==0){
		toastr.error(respuesta.frase,"ERROR");
		return;
	}	
	Usuario.sexo=$("#sexoliteral").attr("data-sexo");
	Usuario.busco=$("#admitoliteral").attr("data-admito");
	toastr.success(respuesta.frase,"OK");
	BotonAtras();
}
function dedondefoto(){
	$("#todo").fadeIn();
	$("#dedondefoto").slideDown(300);
}
function quitardedondefoto(){
	$("#dedondefoto").slideUp(300);
	$("#todo").fadeOut();
}






