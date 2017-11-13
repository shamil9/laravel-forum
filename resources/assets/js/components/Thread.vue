<script>
    import Reply from './Reply.vue'
    import SubscribeButton from './SubscribeButton.vue'
    export default {
        components: { Reply, SubscribeButton },
        props: ['attributes'],
        data() {
            return {
                repliesCount: this.attributes.replies_count
            }
        }
    }
</script>