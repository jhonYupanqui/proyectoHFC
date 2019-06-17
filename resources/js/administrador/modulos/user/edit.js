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
          
        let idRol = $("#rolUpdate").val()
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

    $("#actualizarUsuario").click(function(){

        let validacionUpdate = validacionCotinueUpdate()
        if(!validacionUpdate){ 
            return false
        }

        let usuario = $("#idUpdate").val() 
        let nombre = $("#nombreUpdate").val() 
        let apellidos = $("#apellidosUpdate").val() 
        let documento = $("#documentoUpdate").val() 
        let celular = $("#celularUpdate").val() 
        let correo = $("#correoUpdate").val() 
        let empresa = $("#empresaUpdate").val() 
        let rol = $("#rolUpdate").val() 
        let estado = $("#estadoUpdate").val()

        let permisos = []
        let permisosGenerales = $("#modulosAndPermisosList input[type=checkbox]")
    
        for (let index = 0; index < permisosGenerales.length; index++) {
         
          if(permisosGenerales[index].checked && permisosGenerales[index].disabled == false){ 
            //formData.append('permisos[]', permisosGenerales[index].value); 
            permisos.push(permisosGenerales[index].value);
     
          }
        }

        $("#form_update_detail").css({'display':'none'})
        $("#form_update_load").html(`<div id="carga_person">
                                  <div class="loader">Loading...</div>
                                </div>`) 

        $.ajax({
            url:`/administrador/empresa/${empresa}/rol/${rol}/usuario/${usuario}/update`,
            method:"put",
            data:{
                nombre,
                apellidos,
                documento,
                celular,
                correo,
                estado,
                permisos,
            },
            dataType: "json", 
        })
        .done(function(data){

            $("#form_update_load").css({'display':'none'})
            $("#form_update_load").html('')
            $("#form_update_detail").css({'display':'flex'})
           // limpia.limpiaFormUser()
    
             console.log(data)
            /* $("#errors_Update").html(data)
            if(data.error){
                $("#body-errors-modal").html(data.error)
                $('#errorsModal').modal('show') 
                return false
            }
      
             $("#body-success-modal").html(` `)
            $("#successModal").modal("show")*/
      
        })
        .fail(function(jqXHR, textStatus){
      
            $("#form_update_load").css({'display':'none'})
            $("#form_update_load").html('')
            $("#form_update_detail").css({'display':'flex'})
      
              console.log( "Error: " ,jqXHR, textStatus);
              //console.log( "Request failed: " ,jqXHR.responseJSON.mensaje);
               $("#errors_Update").html(jqXHR.responseText)
              if(jqXHR.responseJSON){
                  if(jqXHR.responseJSON.mensaje){
                      let erroresMensaje = jqXHR.responseJSON.mensaje  //captura objeto
                      let mensaje = errors.mensajeErrorJson(erroresMensaje)
                      $("#errors_Update").html(mensaje)
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

    })

})

function validacionCotinueUpdate()
{
    let nombre = $("#nombreUpdate") 
    let apellidos = $("#apellidosUpdate") 
    let dni = $("#documentoUpdate") 
    let celular = $("#celularUpdate") 
    let correo = $("#correoUpdate") 
    let empresa = $("#empresaUpdate") 
    let rol = $("#rolUpdate") 
        
    $(".validateText").removeClass("valida-error-input")
    $(".validateSelect").removeClass("valida-error-input")
    $("#errors_Update").html(``)

    if(!valida.isValidText(nombre.val())){
        valida.isValidateInputText(nombre)
        $("#errors_Update").html(`El campo nombre es requerido`)
        return false
    } 
    if(!valida.isValidLetters(nombre.val())){
        valida.isValidateInputText(nombre)
        $("#errors_Update").html(`El campo nombre debe ser solamente de formato texto`)
        return false
    } 

    if(!valida.isValidText(apellidos.val())){
        valida.isValidateInputText(apellidos)
        $("#errors_Update").html(`El campo apellidos es requerido`)
        return false
    } 
    if(!valida.isValidLetters(apellidos.val())){
        valida.isValidateInputText(apellidos)
        $("#errors_Update").html(`El campo apellidos debe ser solamente de formato texto`)
        return false
    }
    
    if(!valida.isValidText(dni.val())){
        valida.isValidateInputText(dni)
        $("#errors_Update").html(`El campo dni es requerido`)
        return false
    } 
    if(!valida.isValidNumber(dni.val())){
        valida.isValidateInputText(dni)
        $("#errors_Update").html(`El campo dni debe ser de formato numérico`)
        return false
    } 
    if(dni.val().length > 8 || dni.val().length < 8){
        valida.isValidateInputText(dni)
        $("#errors_Update").html(`El campo dni debe tener una logintud de 8 dígitos`)
        return false
    }

    if(!valida.isValidText(celular.val())){
        valida.isValidateInputText(celular)
        $("#errors_Update").html(`El campo celular es requerido`)
        return false
    } 
    if(!valida.isValidNumber(celular.val())){
        valida.isValidateInputText(celular)
        $("#errors_Update").html(`El campo celular debe ser de formato numérico`)
        return false
    } 
    if(celular.val().length > 9 || celular.val().length < 9){
        valida.isValidateInputText(celular)
        $("#errors_Update").html(`El campo celular debe tener una logintud de 9 dígitos`)
        return false
    }
    
    if(!valida.isValidEmail(correo.val())){
        valida.isValidateInputText(correo)
        $("#errors_Update").html(`El correo no tiene un formato válido`)
        return false
    }

    if(!valida.isValidText(empresa.val())){
        valida.isValidateInputText(empresa)
        $("#errors_Update").html(`El campo empresa es requerido`)
        return false
    }  
    if(empresa.val().toLowerCase() == "seleccionar"){
        valida.isValidateInputText(empresa)
        $("#errors_Update").html(`Seleccione una empresa válida`)
        return false
    }
    if(!valida.isValidNumber(empresa.val())){
        valida.isValidateInputText(empresa)
        $("#errors_Update").html(`Seleccione una empresa válida`)
        return false
    } 
    
    if(!valida.isValidText(rol.val())){
        valida.isValidateInputText(rol)
        $("#errors_Update").html(`El campo rol es requerido`)
        return false
    } 
    if(rol.val().toLowerCase() == "seleccionar"){
        valida.isValidateInputText(rol)
        $("#errors_Update").html(`Seleccione un rol válido`)
        return false
    }
    if(!valida.isValidNumber(rol.val())){
        valida.isValidateInputText(rol)
        $("#errors_Update").html(`Seleccione un rol válido`)
        return false
    } 
  
    $(".validateText").removeClass("valida-error-input")
    $(".validateSelect").removeClass("valida-error-input")
    $("#errors_Update").html(``)


    return true
    
}