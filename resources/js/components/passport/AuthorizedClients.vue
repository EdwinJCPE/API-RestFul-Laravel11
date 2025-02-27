<style scoped>
    .action-link {
        cursor: pointer;
    }
</style>

<template>
    <div>
        <div v-if="tokens.length > 0">
            <div class="card">
                <div class="card-header">Authorized Applications</div>

                <div class="card-body">
                    <!-- Authorized Tokens -->
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Scopes</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="token in tokens" :key="token.id">
                                <!-- Client Name -->
                                <td class="align-middle">
                                    {{ token.client.name }}
                                </td>

                                <!-- Scopes -->
                                <td class="align-middle">
                                    <span v-if="token.scopes.length > 0">
                                        {{ token.scopes.join(', ') }}
                                    </span>
                                    <span v-else class="text-muted">No scopes assigned</span>
                                </td>

                                <!-- Revoke Button -->
                                <td class="align-middle text-end">
                                    <!-- <a class="action-link text-danger" @click="revoke(token)">
                                        Revoke
                                    </a> -->
                                    <button class="btn btn-danger btn-sm" @click="revoke(token)">
                                        Revoke
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div v-else class="alert alert-warning text-center">
            No authorized applications.
        </div>
    </div>
</template>

<script>
    export default {
        /*
         * The component's data.
         */
        data() {
            return {
                tokens: []
            };
        },

        /**
         * Prepare the component (Vue 2.x and Vue 3.x).
         */
        mounted() {
            this.getTokens();
        },

        methods: {
            /**
             * Get all of the authorized tokens for the user.
             */
            getTokens() {
                axios.get('/oauth/tokens')
                        .then(response => {
                            this.tokens = response.data;
                        });
            },

            /**
             * Revoke the given token.
             */
            revoke(token) {
                axios.delete('/oauth/tokens/' + token.id)
                        .then(response => {
                            this.getTokens();
                        });
            }
        }
    }
</script>
