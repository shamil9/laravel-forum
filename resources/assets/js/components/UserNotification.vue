<template>
    <li class="dropdown" v-if="notifications.length">
        <a @click="markAsRead"
            href="#"
            class="dropdown-toggle"
            data-toggle="dropdown"
            role="button"
            aria-haspopup="true"
            aria-expanded="false"
        >
        <i :class="classes"></i></a>

        <ul class="dropdown-menu">
            <li v-for="notification in notifications">
                {{ notification.data[0] }}
            </li>
        </ul>
    </li>
</template>

<script>
export default {
    props: ['route', 'newNotifications', 'readRoute'],

    computed: {
        classes() {
            return [
                'glyphicon glyphicon-bell',
                this.newNotifications && 'new-notification'
            ]
        }
    },

    data() {
        return {
            notifications: []
        }
    },

    created() {
        window.axios.get(this.route)
            .then(response => this.notifications = response.data)
    },

    methods: {
        markAsRead() {
            window.axios.get(this.readRoute)
                .then(response => this.newNotifications = 0)
            }
    }
}
</script>