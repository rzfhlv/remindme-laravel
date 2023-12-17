Vue.component('reminder-component', {
    template: `
        <div class="bg-white lg:w-8/12 md:7/12 w-8/12 shadow-3xl rounded-xl">
            <div class="flex flex-wrap justify-center mt-10">
                <form class="flex flex-col md:flex-row gap-3 mb-10">
                    <div class="flex">
                        <input type="number" v-model="limit" placeholder="Limit"
                            class="w-full md:w-80 px-3 h-10 rounded-l border-2 border-sky-500 focus:outline-none focus:border-sky-500"
                            >
                        <button type="button" @click="get" class="bg-sky-500 text-white rounded-r px-2 md:px-3 py-0 md:py-1">Get</button>
                    </div>
                    <button type="button" @click="add" class="w-full h-10 border-2 border-sky-500 focus:outline-none focus:border-sky-500 text-sky-500 rounded px-2 md:px-3 py-0 md:py-1 tracking-wider">Add</button>
                    <button type="button" @click="logout" class="w-full h-10 border-2 border-sky-500 focus:outline-none focus:border-sky-500 text-sky-500 rounded px-2 md:px-3 py-0 md:py-1 tracking-wider">Logout</button>
                </form>
                <div class="p-4 w-full" v-for="(reminder, index) in reminders">
                    <div class="flex rounded-lg h-full dark:bg-gray-800 bg-teal-400 p-8 flex-col">
                        <div class="flex items-center mb-3">
                            <div
                                class="w-8 h-8 mr-3 inline-flex items-center justify-center rounded-full dark:bg-indigo-500 bg-indigo-500 text-white flex-shrink-0">
                                <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
                                    <path d="M22 12h-4l-3 9L9 3l-3 9H2"></path>
                                </svg>
                            </div>
                            <h2 class="text-white dark:text-white text-lg font-medium">{{ reminder.title }}</h2>
                        </div>
                        <div class="flex flex-col justify-between flex-grow">
                            <p class="leading-relaxed text-base text-white dark:text-gray-300">
                                {{ reminder.description }}
                            </p>
                            <p class="leading-relaxed text-base text-white dark:text-gray-300">
                                Event at: {{ reminder.event_at }}
                            </p>
                        </div>
                        <div class="flex items-center mt-3">
                            <button type="button" @click="edit(reminder)" class="w-full h-10 border-2 border-sky-500 focus:outline-none focus:border-sky-500 text-sky-500 rounded px-2 md:px-3 py-0 md:py-1 tracking-wider">
                                Edit
                            </button>
                        </div>
                        <div class="flex items-center mt-3">
                            <button type="button" @click="delete2(reminder.id)" class="w-full h-10 border-2 border-sky-500 focus:outline-none focus:border-sky-500 text-sky-500 rounded px-2 md:px-3 py-0 md:py-1 tracking-wider">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `,
    props: ['reminders'],
    data() {
        return {
            limit: 10,
        }
    },
    methods: {
        get: async function() {
            let _this = this
            _this.$emit('toggle-loading', true)
            if (_this.limit > 10 || _this.limit == 0) {
                _this.limit = 10
            }
            await axios('/api/reminders?limit='+_this.limit, {headers: { Authorization: 'Bearer ' + localStorage.getItem('token')}})
                .then(function (response) {
                    _this.reminders = []
                    _this.reminders = response.data.data.reminders
                    _this.$emit('toggle-loading', false)
                    _this.$emit('toggle-reminder', true)
                })
                .catch(function (error) {
                    _this.$emit('toggle-loading', false)
                    new Swal({
                        title: "Oops...",
                        html: error,
                        icon: "error"
                    })
                })
        },
        logout: async function() {
            let _this = this
            _this.$emit('toggle-loading', true)
            await axios.post('/api/logout', {headers: { Authorization: 'Bearer ' + localStorage.getItem('token')}})
                .then(function (response) {
                    _this.$emit('toggle-loading', false)
                    window.location.href = '/'
                })
                .catch(function (error) {
                    _this.$emit('toggle-loading', false)
                    new Swal({
                        title: "Oops...",
                        html: error,
                        icon: "error"
                    })
                })
        },
        async add() {
            await this.$emit('toggle-action', 'add')
        },
        edit(data) {
            let payload = {
                id: data.id,
                title: data.title,
                description: data.description,
                remind_at: data.remind_at,
                event_at: data.event_at,
            }
            this.$emit('toggle-set-reminder', payload)
            this.$emit('toggle-action', 'edit')
        },
        async delete2(id) {
            let _this = this
            _this.$emit('toggle-loading', true)
            await axios.delete('/api/reminders/'+id, {headers: { Authorization: 'Bearer ' + localStorage.getItem('token')}})
                .then(function (response) {
                    const index = _this.reminders.findIndex(reminder => reminder.id === id)
                    _this.reminders.splice(index, 1)
                    _this.$emit('toggle-loading', false)
                })
                .catch(function (error) {
                    _this.$emit('toggle-loading', false)
                    new Swal({
                        title: "Oops...",
                        html: error,
                        icon: "error"
                    })
                })
        }
    },
})