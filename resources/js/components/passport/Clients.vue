<style scoped>
    .action-link {
        cursor: pointer;
    }
</style>

<template>
    <div>
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>
                    OAuth Clients
                </span>

                <a class="action-link" tabindex="-1" @click="showCreateClientForm">
                    Create New Client
                </a>
            </div>

            <div class="card-body">
                <!-- Current Clients -->
                <p class="alert alert-info text-center" v-if="clients.length === 0">
                    You have not created any OAuth clients.
                </p>

                <table class="table table-bordered table-hover" v-if="clients.length > 0">
                    <thead>
                        <tr>
                            <th>Client ID</th>
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Secret</th>
                            <th>Provider</th>
                            <th>Redirect</th>
                            <th>Access</th>
                            <th>Client Password</th>
                            <th>Revoked</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="client in clients" :key="client.id">
                            <!-- ID -->
                            <td class="align-middle">
                                {{ client.id }}
                            </td>

                            <!-- User ID -->
                            <td class="align-middle">
                                {{ client.user_id }}
                            </td>

                            <!-- Name -->
                            <td class="align-middle">
                                {{ client.name }}
                            </td>

                            <!-- Secret -->
                            <td class="align-middle">
                                <code>{{ client.secret ? client.secret : '-' }}</code>
                            </td>

                            <!-- Provider -->
                            <td class="align-middle">
                                {{ client.provider ? client.provider : '-' }}
                            </td>

                            <!-- Redirect -->
                            <td class="align-middle">
                                {{ client.redirect ? client.redirect : '-' }}
                            </td>

                            <!-- Access -->
                            <td class="align-middle">
                                {{ client.personal_access_client ? client.personal_access_client : '-' }}
                            </td>

                            <!-- Client Password -->
                            <td class="align-middle">
                                {{ client.password_client ? client.password_client : '-' }}
                            </td>

                            <!-- Revoked -->
                            <td class="align-middle">
                                {{ client.revoked ? client.revoked : '-' }}
                            </td>

                            <!-- Edit Button -->
                            <td class="align-middle">
                                <a class="action-link" tabindex="-1" @click="edit(client)">
                                    Edit
                                </a>
                            </td>

                            <!-- Delete Button -->
                            <td class="align-middle">
                                <a class="action-link text-danger" @click="destroy(client)">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Create Client Modal -->
        <div class="modal fade" id="modal-create-client" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                            Create Client
                        </h4>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <!-- Form Errors -->
                        <div class="alert alert-danger" v-if="createForm.errors.length > 0">
                            <strong>Whoops!</strong> Something went wrong!
                            <ul>
                                <li v-for="error in createForm.errors" :key="error">
                                    {{ error }}
                                </li>
                            </ul>
                        </div>

                        <!-- Create Client Form -->
                        <form @submit.prevent="store">
                            <!-- Name -->
                            <div class="mb-3">
                                <label class="form-label">Name</label>

                                <input id="create-client-name" type="text" class="form-control"
                                                            @keyup.enter="store" v-model="createForm.name">

                                <span class="form-text text-muted">
                                    Something your users will recognize and trust.
                                </span>
                            </div>

                            <!-- Redirect URL -->
                            <div class="mb-3">
                                <label class="form-label">Redirect URL</label>

                                <input type="text" class="form-control" name="redirect"
                                                @keyup.enter="store" v-model="createForm.redirect">

                                <span class="form-text text-muted">
                                    Your application's authorization callback URL.
                                </span>
                            </div>

                            <!-- Confidential -->
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" v-model="createForm.confidential">
                                <label class="form-check-label">Confidential</label>

                                <span class="form-text text-muted">
                                    Require the client to authenticate with a secret. Confidential clients can hold credentials in a secure way without exposing them to unauthorized parties. Public applications, such as native desktop or JavaScript SPA applications, are unable to hold secrets securely.
                                </span>
                            </div>
                        </form>
                    </div>

                    <!-- Modal Actions -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                        <button type="button" class="btn btn-primary" @click="store">
                            Create
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Client Modal -->
        <div class="modal fade" id="modal-edit-client" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                            Edit Client
                        </h4>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <!-- Form Errors -->
                        <div class="alert alert-danger" v-if="editForm.errors.length > 0">
                            <strong>Whoops!</strong> Something went wrong!
                            <ul>
                                <li v-for="error in editForm.errors" :key="error">
                                    {{ error }}
                                </li>
                            </ul>
                        </div>

                        <!-- Edit Client Form -->
                        <form @submit.prevent="update">
                            <!-- Name -->
                            <div class="mb-3">
                                <label class="form-label">Name</label>

                                <input id="edit-client-name" type="text" class="form-control"
                                                            @keyup.enter="update" v-model="editForm.name">

                                <span class="form-text text-muted">
                                    Something your users will recognize and trust.
                                </span>
                            </div>

                            <!-- Redirect URL -->
                            <div class="mb-3">
                                <label class="form-label">Redirect URL</label>

                                <input type="text" class="form-control" name="redirect"
                                                @keyup.enter="update" v-model="editForm.redirect">

                                <span class="form-text text-muted">
                                    Your application's authorization callback URL.
                                </span>
                            </div>
                        </form>
                    </div>

                    <!-- Modal Actions -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                        <!-- <button type="button" class="btn btn-primary" @click="update">
                            Save Changes
                        </button> -->
                        <button type="button" class="btn btn-primary" @click.prevent="update">
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Client Secret Modal -->
        <div class="modal fade" id="modal-client-secret" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                            Client Secret
                        </h4>

                        <button type="button" class="btn-close" data-bs-dismiss="modal"  aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <p>
                            Here is your new client secret. This is the only time it will be shown so don't lose it!
                            You may now use this secret to make API requests.
                        </p>

                        <input type="text" class="form-control" v-model="clientSecret">
                    </div>

                    <!-- Modal Actions -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Modal } from 'bootstrap';

    export default {
        /*
         * The component's data.
         */
        data() {
            return {
                clients: [],

                clientSecret: null,

                createForm: {
                    errors: [],
                    name: '',
                    redirect: '',
                    confidential: true
                },

                editForm: {
                    errors: [],
                    name: '',
                    redirect: ''
                }
            };
        },

        /**
         * Prepare the component (Vue 2.x and Vue 3.x).
         */
        mounted() {
            this.getClients();

            // // Agregar evento para que el input del modal de creación reciba el foco al abrirse
            // const createModalElement = document.getElementById('modal-create-client');
            // if (createModalElement) {
            //     createModalElement.addEventListener('shown.bs.modal', () => {
            //         document.getElementById('create-client-name')?.focus();
            //     });
            // }

            // // Agregar evento para que el input del modal de edición reciba el foco al abrirse
            // const editModalElement = document.getElementById('modal-edit-client');
            // if (editModalElement) {
            //     editModalElement.addEventListener('shown.bs.modal', () => {
            //         document.getElementById('edit-client-name')?.focus();
            //     });
            // }

            // Agregar eventos para que el input de los modales reciba el foco al abrirse
            this.setModalFocus('modal-create-client', 'create-client-name');
            this.setModalFocus('modal-edit-client', 'edit-client-name');
        },

        methods: {
            /**
             * Get all of the OAuth clients for the user.
             */
            getClients() {
                axios.get('/oauth/clients')
                        .then(response => {
                            this.clients = response.data;
                        });
            },

            /**
             * Show the form for creating new clients.
             */
            showCreateClientForm() {
                // const modalElement = document.getElementById('modal-create-client');
                // if (modalElement) {
                //     const modal = new Modal(modalElement);
                //     modal.show();
                // }

                this.showModal('modal-create-client');
            },

            /**
             * Create a new OAuth client for the user.
             */
            store() {
                this.persistClient(
                    'post',
                    '/oauth/clients',
                    this.createForm,
                    'modal-create-client'
                );
            },

            /**
             * Edit the given client.
             */
            edit(client) {
                this.editForm.id = client.id;
                this.editForm.name = client.name;
                this.editForm.redirect = client.redirect;

                this.showModal('modal-edit-client');
            },

            /**
             * Update the client being edited.
             */
            update() {
                this.persistClient(
                    'put',
                    '/oauth/clients/' + this.editForm.id,
                    this.editForm,
                    'modal-edit-client'
                );
            },

            /**
             * Persist the client to storage using the given form.
             */
            persistClient(method, uri, form, modalId) {
                form.errors = [];

                axios[method](uri, form)
                    .then(response => {
                        this.getClients();

                        form.name = '';
                        form.redirect = '';
                        form.errors = [];

                        // Cerrar el modal después de la acción
                        this.closeModal(modalId);

                        if (response.data.plainSecret) {
                            this.showClientSecret(response.data.plainSecret);
                        }
                    })
                    .catch(error => {
                        form.errors = error.response?.data?.errors
                        ? Object.values(error.response.data.errors).flat()
                        : ['Something went wrong. Please try again.'];
                    });
            },

            /**
             * Show the given client secret to the user.
             */
            showClientSecret(clientSecret) {
                this.clientSecret = clientSecret;
                this.showModal('modal-client-secret');
            },

            /**
             * Destroy the given client.
             */
            destroy(client) {
                axios.delete('/oauth/clients/' + client.id)
                        .then(response => {
                            this.getClients();
                        });
            },

            /**
             * Abre un modal por su ID
             * @param {string} modalId - ID del modal a abrir
             */
            showModal(modalId) {
                const modalElement = document.getElementById(modalId);
                if (modalElement) {
                    const modalInstance = new Modal(modalElement);
                    modalInstance.show();
                }
            },

            /**
             * Cierra un modal por su ID
             * @param {string} modalId - ID del modal a cerrar
             */
            closeModal(modalId) {
                const modalElement = document.getElementById(modalId);
                if (modalElement) {
                    const modalInstance = Modal.getInstance(modalElement) || new Modal(modalElement);
                    modalInstance.hide();
                }
            },
            /**
             * Configura el foco en un input cuando se abre un modal
             * @param {string} modalId - ID del modal
             * @param {string} inputId - ID del input que recibirá el foco
             */
            setModalFocus(modalId, inputId) {
                const modalElement = document.getElementById(modalId);
                if (modalElement) {
                    modalElement.addEventListener('shown.bs.modal', () => {
                        document.getElementById(inputId)?.focus();
                    });
                }
            }
        }
    }
</script>
