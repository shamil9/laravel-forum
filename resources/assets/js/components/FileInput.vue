<template>
    <input @change="read" type="file">
</template>

<script>
    export default {
        data () {
            return {
                url: ''
            }
        },

        methods: {
            read ({ target }) {
                var file   = target.files[ 0 ]
                var reader = new FileReader()

                reader.readAsDataURL(file)
                reader.onload = ({ target }) => {
                    window.events.$emit('fileRead', { url: target.result, file })
                }

            },
        }
    }
</script>
