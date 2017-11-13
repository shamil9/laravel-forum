<script>
    import favorite from './Favorite.vue'
    export default {
        props: ['attributes', 'route'],

        components: { favorite },

        data() {
            return {
                isEditing: false,
                body: this.attributes.body
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
                }
        }
    }
</script>