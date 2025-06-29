<template>
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-6">
                <h1>Equipos</h1>
            </div>
            <div class="col-md-6 text-end">
                <button @click="showModal" class="btn btn-primary">Nuevo</button>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <input type="text" v-model="search" class="form-control" placeholder="Buscar equipos...">
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Modelo</th>
                                <th>Marca</th>
                                <th>Nombre</th>
                                <th>Tipo</th>
                                <th>Potencia (kW)</th>
                                <th>Velocidad (RPM)</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="equipo in filteredEquipos" :key="equipo.id">
                                <td>{{ equipo.modelo }}</td>
                                <td>{{ equipo.marca }}</td>
                                <td>{{ equipo.nombre }}</td>
                                <td>{{ equipo.tipo_equipo.nombre }}</td>
                                <td>{{ equipo.potencia_kw }}</td>
                                <td>{{ equipo.velocidad_rpm }}</td>
                                <td>
                                    <button @click="editEquipo(equipo)" class="btn btn-sm btn-warning me-2">Editar</button>
                                    <button @click="confirmDelete(equipo.id)" class="btn btn-sm btn-danger">Eliminar</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal para agregar/editar -->
        <div class="modal fade" id="equipoModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ modalTitle }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="saveEquipo">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Tipo de equipo*</label>
                                    <select v-model="equipoForm.tipo_equipo_id" class="form-select" required>
                                        <option v-for="tipo in tipoEquipos" :value="tipo.id" :key="tipo.id">
                                            {{ tipo.nombre }}
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Clasificación*</label>
                                    <select v-model="equipoForm.clasificacion" class="form-select" required>
                                        <option value="Equipo principal">Equipo principal</option>
                                        <option value="Equipo secundario">Equipo secundario</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Modelo*</label>
                                    <input type="text" v-model="equipoForm.modelo" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Marca*</label>
                                    <input type="text" v-model="equipoForm.marca" class="form-control" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nombre*</label>
                                <input type="text" v-model="equipoForm.nombre" class="form-control" required>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Fecha de fabricación</label>
                                    <input type="date" v-model="equipoForm.fecha_fabricacion" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Proveedor</label>
                                    <input type="text" v-model="equipoForm.proveedor" class="form-control">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Potencia (kW)*</label>
                                    <input type="number" step="0.01" v-model="equipoForm.potencia_kw" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Velocidad (RPM)*</label>
                                    <input type="number" v-model="equipoForm.velocidad_rpm" class="form-control" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Vigencia</label>
                                <div class="input-group">
                                    <input type="number" v-model="equipoForm.vigencia_anos" class="form-control">
                                    <span class="input-group-text">AÑO(S)</span>
                                </div>
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" v-model="equipoForm.requiere_calibracion" class="form-check-input">
                                <label class="form-check-label">Requiere calibración</label>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Información adicional</label>
                                <textarea v-model="equipoForm.informacion_adicional" class="form-control" rows="3"></textarea>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Swal from 'sweetalert2';

export default {
    data() {
        return {
            equipos: [],
            tipoEquipos: [],
            search: '',
            equipoForm: this.resetEquipoForm(),
            modalTitle: 'Nuevo Equipo',
            editingId: null
        };
    },
    computed: {
        filteredEquipos() {
            return this.equipos.filter(equipo => {
                const searchTerm = this.search.toLowerCase();
                return (
                    equipo.modelo.toLowerCase().includes(searchTerm) ||
                    equipo.marca.toLowerCase().includes(searchTerm) ||
                    equipo.nombre.toLowerCase().includes(searchTerm) ||
                    equipo.tipo_equipo.nombre.toLowerCase().includes(searchTerm)
                );
            });
        }
    },
    mounted() {
        this.fetchEquipos();
        this.fetchTipoEquipos();
    },
    methods: {
        resetEquipoForm() {
            return {
                modelo: '',
                marca: '',
                nombre: '',
                tipo_equipo_id: '',
                clasificacion: 'Equipo principal',
                fecha_fabricacion: '',
                potencia_kw: '',
                velocidad_rpm: '',
                proveedor: '',
                informacion_adicional: '',
                vigencia_anos: 10,
                requiere_calibracion: false
            };
        },
        fetchEquipos() {
            axios.get('/api/equipos')
                .then(response => {
                    this.equipos = response.data;
                })
                .catch(error => {
                    console.error(error);
                });
        },
        fetchTipoEquipos() {
            axios.get('/api/tipo-equipos')
                .then(response => {
                    this.tipoEquipos = response.data;
                })
                .catch(error => {
                    console.error(error);
                });
        },
        showModal() {
            this.equipoForm = this.resetEquipoForm();
            this.modalTitle = 'Nuevo Equipo';
            this.editingId = null;
            new bootstrap.Modal(document.getElementById('equipoModal')).show();
        },
        editEquipo(equipo) {
            this.equipoForm = {
                modelo: equipo.modelo,
                marca: equipo.marca,
                nombre: equipo.nombre,
                tipo_equipo_id: equipo.tipo_equipo_id,
                clasificacion: equipo.clasificacion,
                fecha_fabricacion: equipo.fecha_fabricacion,
                potencia_kw: equipo.potencia_kw,
                velocidad_rpm: equipo.velocidad_rpm,
                proveedor: equipo.proveedor,
                informacion_adicional: equipo.informacion_adicional,
                vigencia_anos: equipo.vigencia_anos,
                requiere_calibracion: equipo.requiere_calibracion
            };
            this.modalTitle = 'Editar Equipo';
            this.editingId = equipo.id;
            new bootstrap.Modal(document.getElementById('equipoModal')).show();
        },
        saveEquipo() {
            const method = this.editingId ? 'put' : 'post';
            const url = this.editingId ? `/api/equipos/${this.editingId}` : '/api/equipos';

            axios[method](url, this.equipoForm)
                .then(response => {
                    this.fetchEquipos();
                    document.getElementById('equipoModal').querySelector('.btn-close').click();
                    Swal.fire('Éxito', 'Equipo guardado correctamente', 'success');
                })
                .catch(error => {
                    Swal.fire('Error', 'Ocurrió un error al guardar', 'error');
                    console.error(error);
                });
        },
        confirmDelete(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminarlo!'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.deleteEquipo(id);
                }
            });
        },
        deleteEquipo(id) {
            axios.delete(`/api/equipos/${id}`)
                .then(response => {
                    this.fetchEquipos();
                    Swal.fire('Eliminado!', 'El equipo ha sido eliminado.', 'success');
                })
                .catch(error => {
                    Swal.fire('Error', 'Ocurrió un error al eliminar', 'error');
                    console.error(error);
                });
        }
    }
};
</script>