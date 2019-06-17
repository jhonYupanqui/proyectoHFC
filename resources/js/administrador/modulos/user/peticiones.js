import filters from '@/globalResources/lists/filters'
import errors from '@/globalResources/errors'

const peticiones = {}
  
//Store
///administrador/roles/{rol}/permisos
peticiones.seleccionarPermisosByRoles = function seleccionarPermisosByRoles(idRol,dataIdent,modalShow){

  $.ajax({
    url:`/administrador/roles/${parseInt(idRol)}/permisos`,
    method:"get",
    dataType: "json", 
  })
  .done(function(data){
    //console.log("los permisos del rol seleccionado:", data)
    let permisosSegunRol = data.response.data
    
    permisosSegunRol.forEach(el => {
        //console.log(el);
        $(`input#check${dataIdent}`+el.identificador).prop('checked', true)
        $(`input#check${dataIdent}`+el.identificador).prop('disabled', true)
    }) 
    modalShow.modal("show")
    
  })
  .fail(function(jqXHR, textStatus){
    console.log( "Request failed: " ,textStatus ,jqXHR);
    $("#body-errors-modal").html(`Hubo un error en el servicio de permisos, intente nuevamente por favor!`)
    $('#errorsModal').modal('show')  
    //console.log( "Request failed: " ,jqXHR.responseJSON.mensaje);
    
  });
}

export default peticiones