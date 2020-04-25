const app = new Vue({
    data: {
        notifications: [],
        loading: true,
        callbackEl: true
    },
    created() {
        this.loading = false;

        let callback = document.getElementById('callback');

        if (callback !== null) {
            this.callbackEl = true;
        } else {
            this.callbackEl = false;
        }
    },
    methods: {

        //Notification
        showNotification(params) {
            this.addNotification(params);
            // {
            //     title: "",
            //     text: "",
            //     type: "success info warning danger",
            //     timeout: 500
            // }
        },

        addNotification(notification) {
            this.notifications.push(notification)
        },
        removeNotification(notification) {
            this.notifications.splice(this.notifications.indexOf(notification), 1);
        },
    },
}).$mount('#app')