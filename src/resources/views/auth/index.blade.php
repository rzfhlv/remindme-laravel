<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RemindMe</title>

    <link href="{{ asset("css/spinner.css") }}" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12"></script>
    <script src="{{ asset("js/tailwind.js") }}"></script>
    <script src="{{ asset("js/axios.js") }}"></script>
    <script src="{{ asset("js/sweetalert.js") }}"></script>
</head>
<body>
    <div class="bg-gray-800 dark:bg-gray-800 h-screen overflow-hidden flex items-center justify-center" id="app">
        <div class="loader-remindme" v-if="loading">
            <img src="{{ asset("images/loading.png") }}" class="bg-loader vertical-center"/>
        </div>
        <login-component
            v-if="login"
            @toggle-loading="changeLoading"
            @toggle-register="changeRegister"
        >
        </login-component>
        <register-component
            v-if="register"
            @toggle-loading="changeLoading"
            @toggle-login="changeLogin"
        >
        </register-component>
    </div>

    <script src="{{ asset("js/auth/login.js") }}"></script>
    <script src="{{ asset("js/auth/register.js") }}"></script>

    <script>
        let app = new Vue({
            el: "#app",
            data() {
                return {
                    login: true,
                    register: false,
                    loading: false,
                }
            },
            methods: {
                changeLoading: function(data) {
                    this.loading = data
                },
                changeLogin: function(data) {
                    this.login = data
                    this.register = !data
                },
                changeRegister: function(data) {
                    this.register = data
                    this.login = !data
                }
            }, 
        })
    </script>
</body>
</html>