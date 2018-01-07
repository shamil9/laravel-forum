<script>
    import Reply from './Reply.vue'
    import SubscribeButton from './SubscribeButton.vue'
    import LockButton from './LockButton.vue'
    import 'at.js'

    export default {
        components: { Reply, SubscribeButton, LockButton },

        props: ['attributes'],

        data() {
            return {
                repliesCount: this.attributes.replies_count,
                locked: this.attributes.locked
            }
        },

        created() {
            window.events.$on('threadUnlocked', () => this.locked = false)
            window.events.$on('threadLocked', () => this.locked = true)
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