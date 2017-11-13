<template>
    <button :class="classes" @click="toggle" v-text="button"></button>
</template>

<script>
    export default {
        props: ['route', 'active'],

        data() {
            return {
                isSubscribed: this.active
            }
        },

        computed: {
            button() {
                return this.isSubscribed ? 'Unsubscribe' : 'Subscribe'
            },

            classes() {
                return ['btn', this.isSubscribed ? 'btn-primary' : 'btn-default']
            }
        },

        methods: {
            toggle() {
                if (this.isSubscribed) this.unsubscribe()
                if (!this.isSubscribed) this.subscribe()
            },

            subscribe() {
                window.axios.post(this.route)
                    .then(() => this.isSubscribed = true)
            },

            unsubscribe() {
                window.axios.delete(this.route)
                    .then(() => this.isSubscribed = false)
            }
        }
    }
</script>