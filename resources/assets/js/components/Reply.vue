<script>
    import favorite from './Favorite.vue'

    export default {
        props: [ 'attributes', 'route', 'best' ],

        components: { favorite },

        data() {
            return {
                isEditing: false,
                body: this.attributes.body,
                isBest: this.best
            }
        },

        created () {
            window.events.$on('markAsBest', id => this.isBest = (id === this.attributes.id))

            window.events.$on('unMarkAsBest', () => this.isBest = false)

            const id = this.$parent.attributes.best_reply_id
            if ( id === this.attributes.id ) {
                this.isBest = (id === this.attributes.id)
            }
        },

        computed: {
            icon () {
                return this.isBest ? 'best-reply__active' : 'best-reply'
            },

            panel () {
                return this.isBest ? 'panel panel-success' : 'panel panel-default'
            }
        },

        methods: {
            update() {
                window.axios.patch(this.route, { body: this.body })
                    .then((response) => {
                        flash('Reply updated')
                    })
                    .catch((error) => {
                        flash('Error!')
                    })

                this.isEditing = false
            },

            destroy() {
                window.axios.delete(this.route)
                    .then((response) => {
                        flash('Reply deleted!')
                        this.$el.remove()
                    })
                    .catch((error) => {
                        flash('Error!')
                    })
            },

            markAsBest (route) {
                window.axios.post(route)
                    .then((response) => {
                        flash('Reply marked as best!')
                        this.isBest = true
                        window.events.$emit('markAsBest', this.attributes.id)
                    })
                    .catch((error) => {
                        flash('Error!')
                    })
            },

            unMarkAsBest (route) {
                window.axios.delete(route)
                    .then((response) => {
                        flash('Best reply removed')
                        window.events.$emit('unMarkAsBest', this.attributes.id)
                    })
                    .catch((error) => {
                        flash('Error!')
                    })
            }
        }
    }
</script>