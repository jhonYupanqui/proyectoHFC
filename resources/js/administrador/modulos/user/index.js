 $(function(){

    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

     //peticiones.cargaCompletaUsuarios(SORTBY,0)
      var cargaUsuariosLista = $('#listUsersPrint').DataTable({
                                    "serverSide": true,
                                    "processing": true,
                                    "ajax": {
                                    "url":"/administrador/usuarios/lista", 
                                        "error": function(jqXHR, textStatus)
                                        { 
                                        // console.log( "Error: " ,jqXHR, textStatus); 
                                            if(jqXHR.status){
                                                if (jqXHR.status == 401) {
                                                    location.reload();
                                                    return false
                                                  } 
                                                cargaUsuariosLista.ajax.reload();
                                                return false
                                            } 
                                            cargaUsuariosLista.ajax.reload();
                                            return false 
                                        }
                                    }, 
                                    "columns": [
                                        {data: 'id'},
                                        {data: 'nombre'},
                                        {data: 'apellidos'},
                                        {data: 'username'},
                                        {data: 'email'},
                                        {data: 'btn'},
                                    ],
                                    "language": {
                                        "info": "_TOTAL_ registros",
                                        "search": "Buscar",
                                        "paginate": {
                                            "next": "Siguiente",
                                            "previous": "Anterior",
                                        },
                                        "lengthMenu": 'Mostrar <select >'+
                                                    '<option value="15">15</option>'+
                                                    '<option value="50">50</option>'+
                                                    '<option value="100">100</option>'+
                                                    '<option value="-1">Todos</option>'+
                                                    '</select> registros',
                                        "loadingRecords": "<div id='carga_person'> <div class='loader'>Cargando...</div></div>",
                                        "processing": "<div id='carga_person'> <div class='loader'>Procesando...</div></div>",
                                        "emptyTable": "No hay usuarios disponibles",
                                        "zeroRecords": "No hay coincidencias", 
                                        "infoEmpty": "",
                                        "infoFiltered": ""
                                    }
                                });
        

        $("#listUsersPrint").parent().addClass("table-responsive tableFixHead") 
     
  /**Tabla fixed */
  var th = $('.tableFixHead').find('thead th')
 
  $('.tableFixHead').on('scroll', function() {
      //console.log("ejecutando"+this.scrollTop); 
      th.css('transform', 'translateY('+ this.scrollTop +'px)'); 
  });
  /**Tabla tabla fixed */
   
 })
 

 