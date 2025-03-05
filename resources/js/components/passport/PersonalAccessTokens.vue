<style scoped>
    .action-link {
        cursor: pointer;
    }
</style>

<template>
    <div>
        <div>
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>
                        Personal Access Tokens
                    </span>

                    <!-- <a class="action-link" tabindex="-1" @click="showCreateTokenForm"> -->
                    <a class="action-link" tabindex="-1" v-on:click="showCreateTokenForm">
                        Create New Token
                    </a>
                </div>

                <div class="card-body">
                    <!-- No Tokens Notice -->
                    <p class="alert alert-info text-center" v-if="tokens.length === 0">
                        You have not created any personal access tokens.
                    </p>

                    <!-- Personal Access Tokens -->
                    <table class="table table-bordered table-hover" v-if="tokens.length > 0">
                        <thead>
                            <tr>
                                <th>User ID</th>
                                <th>Client ID</th>
                                <th>Name</th>
                                <th>Scopes</th>
                                <th>Revoked</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="token in tokens" :key="token.id">
                                <!-- User ID -->
                                <td class="align-middle">
                                    {{ token.user_id }}
                                </td>

                                <!-- Client ID -->
                                <td class="align-middle">
                                    {{ token.client_id }}
                                </td>

                                <!-- Client Name -->
                                <td class="align-middle">
                                    {{ token.name }}
                                </td>

                                <!-- Scopes -->
                                <td class="align-middle">
                                    {{ token.scopes ? token.scopes : '-' }}
                                </td>

                                <!-- Revoked -->
                                <td class="align-middle">
                                    {{ token.revoked ? token.revoked : '-' }}
                                </td>

                                <!-- Delete Button -->
                                <td class="align-middle">
                                    <a class="action-link text-danger" @click="revoke(token)">
                                        Delete
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Create Token Modal -->
        <div class="modal fade" id="modal-create-token" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                            Create Token
                        </h4>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <!-- Form Errors -->
                        <div class="alert alert-danger" v-if="form.errors.length > 0">
                            <strong>Whoops!</strong> Something went wrong!
                            <ul>
                                <li v-for="error in form.errors" :key="error">
                                    {{ error }}
                                </li>
                            </ul>
                        </div>

                        <!-- Create Token Form -->
                        <form @submit.prevent="store">
                            <!-- Name -->
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" id="create-token-name" name="name" v-model="form.name">
                            </div>

                            <!-- Scopes -->
                            <div class="mb-3" v-if="scopes.length > 0">
                                <label class="form-label">Scopes</label>
                                <div v-for="scope in scopes" :key="scope.id" class="form-check">
                                    <input type="checkbox"
                                        class="form-check-input"
                                        @click="toggleScope(scope.id)"
                                        :checked="scopeIsAssigned(scope.id)">
                                    <label class="form-check-label">{{ scope.id }}</label>
                                </div>
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

        <!-- Access Token Modal -->
        <div class="modal fade" id="modal-access-token" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                            Personal Access Token
                        </h4>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <p>
                            Here is your new personal access token. This is the only time it will be shown so don't lose it!
                            You may now use this token to make API requests.
                        </p>

                        <textarea class="form-control" rows="10">{{ accessToken }}</textarea>
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
                accessToken: null,

                tokens: [],
                scopes: [],

                form: {
                    name: '',
                    scopes: [],
                    errors: []
                }
            };
        },

        /**
         * Prepare the component (Vue 2.x and Vue 3.x).
         */
        mounted() {
            this.getTokens();
            this.getScopes();

            const modalElement = document.getElementById('modal-create-token');
            if (modalElement) {
                modalElement.addEventListener('shown.bs.modal', () => {
                    document.getElementById('create-token-name')?.focus();
                });
            }
        },

        methods: {
            /**
             * Get all of the personal access tokens for the user.
             */
            getTokens() {
                axios.get('/oauth/personal-access-tokens')
                        .then(response => {
                            this.tokens = response.data;
                        });
            },

            /**
             * Get all of the available scopes.
             */
            getScopes() {
                axios.get('/oauth/scopes')
                        .then(response => {
                            this.scopes = response.data;
                        });
            },

            /**
             * Show the form for creating new tokens.
             */
            showCreateTokenForm() {
                const modalElement = document.getElementById('modal-create-token');
                if (modalElement) {
                    const modal = new Modal(modalElement);
                    modal.show();
                }
            },

            /**
             * Create a new personal access token.
             */
            store() {
                this.accessToken = null;

                this.form.errors = [];

                axios.post('/oauth/personal-access-tokens', this.form)
                        .then(response => {
                            this.form.name = '';
                            this.form.scopes = [];
                            this.form.errors = [];

                            this.tokens.push(response.data.token);

                            this.showAccessToken(response.data.accessToken);
                        })
                        .catch(error => {
                             // Verificar si error.response existe para evitar errores
                            this.form.errors = error.response?.data?.errors
                                ? Object.values(error.response.data.errors).flat()
                                : ['Something went wrong. Please try again.'];
                        });
            },

            /**
             * Toggle the given scope in the list of assigned scopes.
             */
            toggleScope(scope) {
                if (this.scopeIsAssigned(scope)) {
                    this.form.scopes = this.form.scopes.filter(s => s !== scope);
                } else {
                    this.form.scopes.push(scope);
                }
            },

            /**
             * Determine if the given scope has been assigned to the token.
             */
            scopeIsAssigned(scope) {
                return this.form.scopes.includes(scope);
            },

            /**
             * Show the given access token to the user.
             */
            showAccessToken(accessToken) {
                // Cerrar el modal de creación de token
                const createTokenModal = document.getElementById('modal-create-token');
                if (createTokenModal) {
                    const modalInstance = Modal.getInstance(createTokenModal) || new Modal(createTokenModal);
                    modalInstance.hide();
                }

                // Guardar el token en la variable de Vue
                this.accessToken = accessToken;

                // Mostrar el modal de acceso al token
                const accessTokenModal = document.getElementById('modal-access-token');
                if (accessTokenModal) {
                    const modalInstance = new Modal(accessTokenModal);
                    modalInstance.show();
                }
            },

            /**
             * Revoke the given token.
             */
            revoke(token) {
                axios.delete('/oauth/personal-access-tokens/' + token.id)
                        .then(response => {
                            this.getTokens();
                        });
            }
        }
    }
</script>
