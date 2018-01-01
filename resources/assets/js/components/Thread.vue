<script>
    import Reply from './Reply.vue'
    import SubscribeButton from './SubscribeButton.vue'
    import 'at.js'

    export default {
        components: { Reply, SubscribeButton },

        props: ['attributes'],

        data() {
            return {
                repliesCount: this.attributes.replies_count
            }
        },

        created () {
            window.events.$on('markAsBest', id => console.log(id))
        },

        mounted () {
            $('#reply').atwho({
                at: "@",
                delay: 750,
                callbacks: {
                    remoteFilter: function ( query, callback ) {
                        $.getJSON("/api/users", { name: query }, function ( data ) {
                            callback(data)
                        });
                    }
                }
            })
        }
    }
</script>