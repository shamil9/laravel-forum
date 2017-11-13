<template>
    <button @click="toggle" type="submit" :class="classes">
        <span class="glyphicon glyphicon-heart"></span>
        <span v-text="count"></span>
    </button>
</template>
<script>
    export default {
        props: ['route', 'reply'],

        data() {
            return {
                count: this.reply.favoritesCount,
                isFavorited: this.reply.isFavorited
            }
        },

        computed: {
            classes() {
                return ['btn', this.isFavorited ? 'btn-primary' : 'btn-default']
            }
        },

        methods: {
            toggle() {
                this.isFavorited ? this.remove() : this.add()
            },

            remove() {
                window.axios.delete(this.route)
                    .then(response => {
                        this.isFavorited = false
                        this.count--
                    })
                    .error(error => console.log(error))
            },

            add() {
                window.axios.post(this.route)
                    .then(response => {
                        this.isFavorited = true
                        this.count++
                    })
                    .error(error => console.log(error))
            }
        }
    }
</script>