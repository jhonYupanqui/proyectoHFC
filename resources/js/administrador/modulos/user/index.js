import peticiones from './peticiones.js'

var SORTBY = ''

 $(function(){

    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

    peticiones.cargaCompletaUsuarios(SORTBY,0)

    $(function(){
        $('#listUsersPrint').DataTable({
            "language": {
                "info": "_TOTAL_ registros",
                "search": "Buscar",
                "paginate": {
                    "next": "Siguiente",
                    "previous": "Anterior",
                },
                "lengthMenu": 'Mostrar <select >'+
                            '<option value="10">10</option>'+
                            '<option value="30">30</option>'+
                            '<option value="-1">Todos</option>'+
                            '</select> registros',
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "emptyTable": "No hay datos",
                "zeroRecords": "No hay coincidencias", 
                "infoEmpty": "",
                "infoFiltered": ""
            }
        });
    })

    //FILTRO TABLA
    $("#nombre").keydown(function(event){
        if(event.which == 13){
            event.preventDefault()
            peticiones.cargaCompletaUsuarios(SORTBY,0)
        }
    });
    $("#apellidos").keydown(function(event){
        if(event.which == 13){
            event.preventDefault()
            peticiones.cargaCompletaUsuarios(SORTBY,0)
        }
    });
    $("#documento").keydown(function(event){
        if(event.which == 13){
            event.preventDefault()
            peticiones.cargaCompletaUsuarios(SORTBY,0)
        }
    });
    $("#usuario").keydown(function(event){
        if(event.which == 13){
            event.preventDefault()
            peticiones.cargaCompletaUsuarios(SORTBY,0)
        }
    });
    $("#searchData").click(function(){
        peticiones.cargaCompletaUsuarios(SORTBY,0)
    }) 
    $('body').on("keydown", "#paginateData", function(event) {
        if(event.which == 13){
            event.preventDefault()
            peticiones.cargaCompletaUsuarios(SORTBY,0)
        } 
    })
    $('body').on("click", "#paginarResult", function(e) {
        peticiones.cargaCompletaUsuarios(SORTBY,0)

    })
    $('body').on("click", "#result_page_list .page-link-filter", function(e) {
        let paginateSearch = $(this).data("paginate")
        peticiones.cargaCompletaUsuarios(SORTBY,paginateSearch)

    })

    //ORDER TABLA
  $("#table_list_general_index .icon-orde_by").click(function(){
    let orderBy = ''

    if($(this).hasClass("active")){
      $("#table_list_general_index .icon-orde_by").removeClass("active")
    }else{
      $("#table_list_general_index .icon-orde_by").removeClass("active")
      $(this).addClass("active")
      orderBy = $(this).data("order")
    }

    //alert("se ordenara por :"+orderBy)
    SORTBY = orderBy
    peticiones.cargaCompletaUsuarios(SORTBY,0)
  })

  /**Tabla fixed */
  var th = $('.tableFixHead').find('thead th')
 
  $('.tableFixHead').on('scroll', function() {
      //console.log("ejecutando"+this.scrollTop); 
      th.css('transform', 'translateY('+ this.scrollTop +'px)'); 
  });
  /**Tabla tabla fixed */
   
 })

 