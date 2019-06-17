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

})