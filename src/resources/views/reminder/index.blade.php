<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RemindMe</title>

    <link href="{{ asset("css/spinner.css") }}" rel="stylesheet">

    <script src="{{ asset("js/vue.js") }}"></script>
    <script src="{{ asset("js/tailwind.js") }}"></script>
    <script src="{{ asset("js/axios.js") }}"></script>
    <script src="{{ asset("js/sweetalert.js") }}"></script>
</head>
<body>
<div class="bg-gray-800 dark:bg-gray-800 h-screen overflow-hidden flex items-center justify-center" id="app">
        <div class="loader-remindme" v-if="loading">
            <img src="{{ asset("images/loading.png") }}" class="bg-loader vertical-center"/>
        </div>]

        <reminder-component
            v-if="reminder"
            :reminders="reminders"
            @toggle-loading="changeLoading"
            @toggle-action="changeAction"
            @toggle-reminder="changeReminder"
            @toggle-set-reminder="setReminder"
        >
        </reminder-component>

        <add-component
            v-if="action === 'add'"
            :reminders="reminders"
            @toggle-loading="changeLoading"
            @toggle-reminder="changeReminder"
            @toggle-set-reminders="setReminders"
        >
        </add-component>
        <edit-component
            v-if="action === 'edit'"
            :reminderdata="reminderdata"
            :reminders="reminders"
            @toggle-loading="changeLoading"
            @toggle-reminder="changeReminder"
            @toggle-set-reminders="setReminders"
        >
        </edit-component>
    </div>

    <script src="{{ asset("js/reminder/index.js") }}"></script>
    <script src="{{ asset("js/reminder/add.js") }}"></script>
    <script src="{{ asset("js/reminder/edit.js") }}"></script>

    <script>
        let app = new Vue({
            el: "#app",
            data() {
                return {
                    loading: false,
                    reminder: true,
                    action: '',
                    reminderdata: null,
                    reminders: [],
                }
            },
            methods: {
                changeLoading: function(data) {
                    this.loading = data
                },
                changeReminder: function(data) {
                    this.reminder = data
                    this.action = ''
                },
                changeAction: function(data) {
                    this.action = data
                    this.reminder = false
                },
                setReminder: function(data) {
                    this.reminderdata = Object.assign({}, data)
                },
                setReminders: function(data) {
                    this.reminders = data
                },
            },
            async mounted() {
                let _this = this
                _this.changeLoading(true)
                await axios.get('/api/reminders', {headers: { Authorization: 'Bearer ' + localStorage.getItem('token')}})
                    .then(function (response) {
                        _this.reminders = response.data.data.reminders
                        _this.changeReminder(true)
                        _this.changeLoading(false)
                    })
                    .catch(function (error) {
                        _this.changeLoading(false)
                        let status = error.response.status
                        if (status === 401 || status === 403) {
                            window.location.href = '/'
                        } else {
                            new Swal({
                                title: "Oops...",
                                html: error,
                                icon: "error"
                            })
                        }
                    })
            } 
        })
    </script>
</body>
</html>