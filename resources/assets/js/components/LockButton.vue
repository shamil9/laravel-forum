<template>
    <button :class="classes" v-text="state" @click.prevent="toggle"></button>
</template>

<script>
    export default {
        computed: {
            state() {
                return this.$parent.locked ? 'Unlock' : 'Lock'
            },

            classes() {
                return this.$parent.locked ? 'btn btn-success' : 'btn btn-danger'
            }
        },

        methods: {
            toggle() {
                if (this.$parent.locked)
                    return window.axios.post(`/threads/${this.$parent.attributes.slug}/unlock`)
                            .catch(({data}) => console.log(data))
                            .then(({data}) => {
                                window.events.$emit('threadUnlocked')
                                flash('Thread unlocked')
                            })


                    window.axios.post(`/threads/${this.$parent.attributes.slug}/lock`)
                        .catch(({data}) => console.log(data))
                        .then(({data}) => {
                            window.events.$emit('threadLocked')
                            flash('Thread locked')
                        })
            }
        }
    }
</script>