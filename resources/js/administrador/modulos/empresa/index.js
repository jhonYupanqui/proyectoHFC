  
 $(function(){

    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

     //peticiones.cargaCompletaUsuarios(SORTBY,0)
 
     var cargaEmpresaLista = $('#listEmpresasPrint').DataTable({
                                "serverSide": true,
                                "processing": true,
                                "ajax": {
                                    "url":"/administrador/empresas/lista", 
                                    "error": function(jqXHR, textStatus)
                                            { 
                                            // console.log( "Error: " ,jqXHR, textStatus); 
                                                if(jqXHR.status){
                                                    if (jqXHR.status == 401) {
                                                        location.reload();
                                                        return false
                                                        } 
                                                        cargaEmpresaLista.ajax.reload();
                                                    return false
                                                } 
                                                cargaEmpresaLista.ajax.reload();
                                                return false 
                                            }
                                    },
                                "columns": [
                                    {data: 'id'},
                                    {data: 'nombre'},
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
                                    "emptyTable": "No hay empresas disponibles",
                                    "zeroRecords": "No hay coincidencias", 
                                    "infoEmpty": "",
                                    "infoFiltered": ""
                                }
                            });

        $("#listEmpresasPrint").parent().addClass("table-responsive tableFixHead") 
     
  /**Tabla fixed */
  var th = $('.tableFixHead').find('thead th')
 
  $('.tableFixHead').on('scroll', function() {
      //console.log("ejecutando"+this.scrollTop); 
      th.css('transform', 'translateY('+ this.scrollTop +'px)'); 
  });
  /**Tabla tabla fixed */
   
 })

 