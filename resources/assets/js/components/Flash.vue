<template>
    <div class="alert alert-success alert-flash" role="alert" v-show="isVisible">
        <strong>Success!</strong> {{ this.body }}
    </div>
</template>

<script>
    export default {
        props: ['message'],

        data() {
            return {
                body: this.message,
                isVisible: false
            }
        },

        created() {
            window.events.$on('flash', message => this.flash(message))

            if (this.message) {
                this.isVisible = true
            }
        },

        methods: {
            flash(message) {
                this.show(message)
                this.hide()
            },

            show(message) {
                this.body = message
                this.isVisible = true
            },

            hide() {
                setTimeout(() => this.isVisible = false, 3000)
            }
        }
    }
</script>

<style>
    .alert-flash {
        position: fixed;
        right: 25px;
        bottom: 25px;
    }
</style>