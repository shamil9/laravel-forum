<script>
    import favorite from './Favorite.vue'

    export default {
        props: ['attributes', 'route'],

        components: { favorite },

        data() {
            return {
                isEditing: false,
                body: this.attributes.body,
                isBest: false
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

            toggleBest () {
                this.isBest = !this.isBest
                window.events.$emit('markAsBest', this.attributes.id)
            }
        }
    }
</script>