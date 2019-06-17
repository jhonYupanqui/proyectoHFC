import valida from  "@/globalResources/forms/valida.js"
import errors from  "@/globalResources/errors.js"
import peticiones from './peticiones.js'

$(function(){

    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

    $("#activarModalPermisos").click(function(){
          
        let idRol = $("#rolStore").val()
        console.log("el idrol es: ",idRol)
        if(idRol.toLocaleLowerCase() == "seleccionar" || idRol.trim() == ""){
            $("#body-errors-modal").html(`Para mostrar los permisos adicionales debe tener un rol seleccionado.`)
            $("#errorsModal").modal("show") 
            return false
        }
        if (idRol) { 
            peticiones.seleccionarPermisosByRoles(idRol,"store",$("#addPermisosModal"))
        }else{
            $("#body-errors-modal").html(`Ocurrio un error al traer los permisos del rol seleccionado, intente nuevamente!`)
            $("#errorsModal").modal("show")  
        }
       

    })

    $("#crearUsuario").click(function(){
        registroUserStore()
    })

    
})

function registroUserStore()
{
   /* let validacionConitnueStore = validacionContinueStore()
    if(!validacionConitnueStore){ 
        return false
    } */
   
 //luego de validar  
 //registrar
    let nombre = $("#nombreStore").val()
    let apellidos = $("#apellidosStore").val()
    let documento = $("#documentoStore").val()
    let celular = $("#celularStore").val()
    let correo = $("#correoStore").val()
    let empresa = $("#empresaStore").val()
    let rol = $("#rolStore").val()

    let permisos = []
    let permisosGenerales = $("#modulosAndPermisosList input[type=checkbox]")

    for (let index = 0; index < permisosGenerales.length; index++) {
     
      if(permisosGenerales[index].checked && permisosGenerales[index].disabled == false){ 
        //formData.append('permisos[]', permisosGenerales[index].value); 
        permisos.push(permisosGenerales[index].value);
 
      }
    }

    console.log("Los permisos a enviarse son: ",permisos)
     
    //console.log("el tipo de permisos es:",typeof(permisos), "permisos son:", permisos)

    $.ajax({
        url:`/administrador/empresa/${empresa}/rol/${rol}/usuario/store`,
        method:"post",
        data:{
            nombre,
            apellidos,
            documento,
            celular,
            correo,
            permisos
        },
        dataType: "json", 
    })
    .done(function(data){
         console.log(data)
         $("#errors_store").html(data)
        if(data.error){
            $("#body-errors-modal").html(data.error)
            $('#errorsModal').modal('show') 
            return false
        }

        /*$("#body-success-modal").html("Se eliminó al usuario seleccionado correctamente!.")
        $("#successModal").modal("show")

        _this.closest('tr').remove();*/

    })
    .fail(function(jqXHR, textStatus){ 
        console.log( "Error: " ,jqXHR, textStatus);
        //console.log( "Request failed: " ,jqXHR.responseJSON.mensaje);
         $("#errors_store").html(jqXHR.responseText)
        if(jqXHR.responseJSON){
            if(jqXHR.responseJSON.mensaje){
                let erroresMensaje = jqXHR.responseJSON.mensaje  //captura objeto
                let mensaje = errors.mensajeErrorJson(erroresMensaje)
                $("#errors_store").html(mensaje)
                return false
            } 
        }
        if(jqXHR.status){
            let mensaje = errors.codigos(jqXHR.status)
            $("#body-errors-modal").html(mensaje)
            $('#errorsModal').modal('show')
            return false
        }
       $("#body-errors-modal").html("hubo un problema en la red del internet, intente nuevamente por favor.")
        $('#errorsModal').modal('show') 
    })
   return false
   let fileInput = document.getElementById('avatarCreate');
   let imagen = fileInput.files[0];
   //console.log("file es:",imagen, "=>",imagen.name)
   let formData = new FormData();
   if(imagen){
     formData.append('imagen', imagen,`${imagen.name}`);
   } 
   formData.append('datoPersonal', dataPersonalId); 
   formData.append('usuario', usuario); 
   formData.append('codigo', codigo); 
   formData.append('correo', correo); 
   formData.append('password', password); 
   formData.append('password_confirmation', password_confirmation); 
   formData.append('estado', estado); 
   formData.append('roles', roles); 

   for (let index = 0; index < permisosGenerales.length; index++) {
     
     if(permisosGenerales[index].checked && permisosGenerales[index].disabled == false){

       formData.append('permisos[]', permisosGenerales[index].value); 

     }
   }
    
   console.log("la data ha enviar es:", formData)
    $("#formStore").css({'display':'none'})
   $("#formSendStore").html(`<div id="carga_person">
                           <div class="loader">Loading...</div>
                         </div>`) 
       $.ajax({
         url:'/users/store',
         method:"post",
         data:formData,
         cache:false,
         contentType: false,
         processData: false,
       })
       .done(function(data){
            $("#formSendStore").html(``)
            $("#formStore").css({'display':'block'})
 
           console.log("la respuesta del registro es: ",data) 
           $("#errors_store").html(data) 
            if(data.error){
             $("#errors_store").html('Hubo un error en la creacion, intentar nuevamente!.')
             return false
           }
           
             limpia.limpiaFormUser()
             $("#userStore .validateText").removeClass("valida-error-input")
             $(".validateSelect").removeClass("valida-error-input")
             $("#errors_store").html('') 
             $("#response-rol-rtpa").html('')
         

           $("#body-success-modal").html("Se registro correctamente!.")
           $("#successModal").modal("show")
          
             
       })
       .fail(function(jqXHR, textStatus){
          $("#formSendStore").html(``)
          $("#formStore").css({'display':'block'})
         console.log( "Request failed: " ,textStatus ,jqXHR);
          console.log( "Request failed: " ,jqXHR.responseJSON.mensaje);
         if(jqXHR.responseJSON.mensaje){
           let errors = jqXHR.responseJSON.mensaje  //captura objeto
           //recorreo objeto como array
           let msj = ``
           Object.keys(errors).forEach(key =>{
             msj +=`${key} : ${errors[key]} <br/>`
             
           })
           $("#errors_store").html(msj)
 
         } 
         
       });
}

function validacionContinueStore()
{
  let nombre = $("#nombreStore") 
  let apellidos = $("#apellidosStore") 
  let dni = $("#documentoStore") 
  let celular = $("#celularStore") 
  let correo = $("#correoStore") 
  let empresa = $("#empresaStore") 
  let rol = $("#rolStore") 
    
  $(".validateText").removeClass("valida-error-input")
  $(".validateSelect").removeClass("valida-error-input")
  $("#errors_store").html(``)

  if(!valida.isValidText(nombre.val())){
    valida.isValidateInputText(nombre)
    $("#errors_store").html(`El campo nombre es requerido`)
    return false
  } 
  if(!valida.isValidLetters(nombre.val())){
    valida.isValidateInputText(nombre)
    $("#errors_store").html(`El campo nombre debe ser solamente de formato texto`)
    return false
  } 

  if(!valida.isValidText(apellidos.val())){
    valida.isValidateInputText(apellidos)
    $("#errors_store").html(`El campo apellidos es requerido`)
    return false
  } 
  if(!valida.isValidLetters(apellidos.val())){
    valida.isValidateInputText(apellidos)
    $("#errors_store").html(`El campo apellidos debe ser solamente de formato texto`)
    return false
  }
 
  if(!valida.isValidText(dni.val())){
    valida.isValidateInputText(dni)
    $("#errors_store").html(`El campo dni es requerido`)
    return false
  } 
  if(!valida.isValidNumber(dni.val())){
    valida.isValidateInputText(dni)
    $("#errors_store").html(`El campo dni debe ser de formato numérico`)
    return false
  } 
  if(dni.val().length > 8 || dni.val().length < 8){
    valida.isValidateInputText(dni)
    $("#errors_store").html(`El campo dni debe tener una logintud de 8 dígitos`)
    return false
  }

  if(!valida.isValidText(celular.val())){
    valida.isValidateInputText(celular)
    $("#errors_store").html(`El campo celular es requerido`)
    return false
  } 
  if(!valida.isValidNumber(celular.val())){
    valida.isValidateInputText(celular)
    $("#errors_store").html(`El campo celular debe ser de formato numérico`)
    return false
  } 
  if(celular.val().length > 9 || celular.val().length < 9){
    valida.isValidateInputText(celular)
    $("#errors_store").html(`El campo celular debe tener una logintud de 9 dígitos`)
    return false
  }
 
  if(!valida.isValidEmail(correo.val())){
    valida.isValidateInputText(correo)
    $("#errors_store").html(`El correo no tiene un formato válido`)
    return false
  }

  if(!valida.isValidText(empresa.val())){
    valida.isValidateInputText(empresa)
    $("#errors_store").html(`El campo empresa es requerido`)
    return false
  }  
  if(empresa.val().toLowerCase() == "seleccionar"){
    valida.isValidateInputText(empresa)
    $("#errors_store").html(`Seleccione una empresa válida`)
    return false
  }
  if(!valida.isValidNumber(empresa.val())){
    valida.isValidateInputText(empresa)
    $("#errors_store").html(`Seleccione una empresa válida`)
    return false
  } 
  
  if(!valida.isValidText(rol.val())){
    valida.isValidateInputText(rol)
    $("#errors_store").html(`El campo rol es requerido`)
    return false
  } 
  if(rol.val().toLowerCase() == "seleccionar"){
    valida.isValidateInputText(rol)
    $("#errors_store").html(`Seleccione un rol válido`)
    return false
  }
  if(!valida.isValidNumber(rol.val())){
    valida.isValidateInputText(rol)
    $("#errors_store").html(`Seleccione un rol válido`)
    return false
  } 
 
  $(".validateText").removeClass("valida-error-input")
  $(".validateSelect").removeClass("valida-error-input")
  $("#errors_store").html(``)


  return true
 
}

 