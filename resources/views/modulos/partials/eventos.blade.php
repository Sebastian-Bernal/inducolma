<button class="btn btn-primary text-light mt-2 mb-2 w-100 rounded-pill" type="button"
    data-bs-toggle="collapse" data-bs-target="#eventoMaquina" aria-expanded="false"
    aria-controls="eventoMaquina" id="eventosDeMaquina">
    EVENTOS DE LA MAQUINA
</button>
<div class="collapse " id="eventoMaquina">
    <div class="card card-body border border-primary border-4 rounded-3">
        @forelse ($tipos_evento as $tipo_evento)
        <button type="button" class="btn w-80 btn-primary text-light m-2"
            id="{{ 'tipoEvento'.$tipo_evento->id }}"
            onclick="listarEventos({{ $tipo_evento->id .','. $eventos }})">
            {{ $tipo_evento->tipo_evento }}
        </button>
        @empty
        <span>Ningun tipo de evento encontrado, por favor agrege uno</span>
        @endforelse


    </div>
</div>

        <!-- Button trigger modal eventos -->
        <button type="button"
                class="btn btn-primary"
                data-bs-toggle="modal"
                data-bs-target="#eventosModal"
                hidden
                id="modalEventos">
        </button>

<!-- Modal eventos -->
<div class="modal fade" id="eventosModal" tabindex="-1" aria-labelledby="eventosModalLabel" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="eventosModalLabel">Eventos</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="eventos">
            <div class="input-group mb-3 mt-3">
                <select  id="listaEventos"
                        name="eventosMaquina"
                        type="text"
                        class="form-control  text-uppercase"
                        name="listaEventos"
                        required
                        autofocus>

                </select>
            </div>
            <div class="form-floating">
                <textarea class="form-control" placeholder="Observaciones" id="observacionEvento" style="height: 100px"></textarea>
                <label for="floatingTextarea2">Observaciones:</label>
            </div>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cerrarEvento">Cerrar</button>
        <button type="button" class="btn btn-primary" onclick="guardaEvento({{ Auth::user()->id.','.$maquina }})">Guardar evento</button>
        </div>
    </div>
    </div>
</div>
