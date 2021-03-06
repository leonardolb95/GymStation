<a href="{{ url('/admin/'. $ruta .'/' . $clase->id) }}" class="list-group-item list-group-item-action flex-column align-items-start">
    <div class="d-flex w-100 justify-content-between">
        <h5 class="mb-1">{{ ($clase->nombre) ? $clase->nombre : 'Sin nombre' }}</h5>
        <small class="text-right">
            <strong>Comienza:</strong> {{ ($clase->fecha_inicio) ? date('d/m/Y', strtotime($clase->fecha_inicio)) . ' a las ' . $clase->hora_inicio : 'Sin asignar' }}<br/>
            <strong>Termina:</strong> {{ ($clase->fecha_fin) ? date('d/m/Y', strtotime($clase->fecha_fin)) . ' a las ' . $clase->hora_fin : 'Sin asignar' }}<br/>
        </small>
    </div>
    <!--<p class="mb-1"></p>-->
    <small>
        Detalles: {{ ($clase->detalle) ? $clase->detalle : 'Sin detalles' }}<br/>
        Cupo: {{ (($clase->cupo_actual) ? $clase->cupo_actual : '0') . '/' . (($clase->cupo_total) ? $clase->cupo_total : '0') }}<br/>
        <strong>Costo:</strong> $ {{ ($clase->costo) ? $clase->costo : 'Sin asignar' }}<br/>
        <strong>Pago profesor:</strong> $ {{ ($clase->pago_profesor) ? $clase->pago_profesor : 'Sin asignar' }}
        <br/>
        <br/>
        @if($clase->trashed())
            <i class="material-icons text-danger">check_circle</i>
        @else
            <i class="material-icons text-success">check_circle</i>
        @endif
    </small>
</a>