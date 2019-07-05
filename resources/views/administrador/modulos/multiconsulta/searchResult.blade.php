<div class="col-md-10 result-form-multi d-flex justify-content-center  flex-column p-0">

    @forelse ($resultadoMulti as $item)
        <span>{!!$item->IDCLIENTECRM!!}</span> 

        @if ($item->obsoleto == "SI" && $item->rol<> "CALL")
            <div class="container text-center text-danger font-weight-bold">CAMBIO DE MODEM POR OBSOLESCENCIA</div>
        @endif
        @if ((int)$item->esTrabProg == 1 && $item->mensajeDigital <> "")
            @if ((int)$item->num_masiva > 0)
                <div class="container text-center text-success font-weight-bold"> 
                        Averia Num: {{$item->num_masiva}}
                </div> 
            @else
                
            @endif
        @endif
        @if ((int)$item->esMasiva == 1 && $item->resultadoAlerta <> "")
            <div class="container text-center text-success font-weight-bold"> 
                {!!$item->resultadoAlerta!!} 
            </div>
        @endif
      

    @empty
        
    @endforelse

</div>