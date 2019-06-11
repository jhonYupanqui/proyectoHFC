import errors from '@/globalResources/errors'


$(function(){

    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

   load_modulos()

   const filter_modulo = document.getElementById('filter_modulos')
   filter_modulo.addEventListener('keydown', e => {
      if(e.keyCode == 13){ 
        load_modulos() 
      } 
      
    })

})

function load_modulos()
{
  document.getElementById("listModulos").innerHTML = `<div id="carga_person">
                                                        <div class="loader">Loading...</div>
                                                      </div>` 

  let filter = $("#filter_modulos").val()
    $.ajax({
        url:`/administrador/lista`,
        method:"get",
        data:{nombre:filter},
        dataType: "json", 
      })
      .done(function(data){
        console.log(data) 

        if(data.error){
          $("#body-reload-modal").html(`
            <p>Hubo un error al cargar los modulos, se intentará nuevamente!</p>
          `)
          $("#reloadModal").modal("show")
          return false
        }
        
        let lista_modulos = data.response.data
        let estructura = ``
        lista_modulos.forEach(el => {
          estructura += `<div class="col-6 col-sm-4 col-md-3 col-lg-3">
                            <a href="${el.ruta}" class="text-decoration-none">
                              <div class="card">
                                <img class="card-img-top" src="${el.imagen}" alt="Modulos publicos list">
                                <div class="card-body text-center text_decoration_none">
                                  <h4 class="font-weight-bold text-uppercase text_modulo_publico">${el.nombre}</h4>
                                </div>
                              </div>
                            </a>
                        </div> `
        });
        $("#listModulos").html(estructura)
      })
      .fail(function(jqXHR, textStatus){
        console.log("error",jqXHR, textStatus)
        $("#listModulos").html("") 
        /*if(jqXHR.responseJSON){
          let errors = jqXHR.responseJSON.message  //captura objeto
          //recorreo objeto como array
          let mensaje_error = errors.mensajeErrorJson(errors) 
          $("#body-reload-modal").html(`<p>${mensaje_error}.</p>`)
          $("#reloadModal").modal("show") 
          return false;
        }*/
        if(jqXHR.status){  
          let mensaje_error = errors.codigos(jqXHR.status)
          $("#body-reload-modal").html(`<p>${mensaje_error}</p>`)
          $("#reloadModal").modal("show")
          return false 
        }
        $("#body-reload-modal").html(`<p>Falla inesperada con la petición. Intente nuevamente.</p>`)
        $("#reloadModal").modal("show") 
      })
}
 