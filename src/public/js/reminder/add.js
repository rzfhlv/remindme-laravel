Vue.component('add-component', {
    template: `
        <div class="bg-white lg:w-6/12 md:7/12 w-8/12 shadow-3xl rounded-xl">
            <div class="bg-gray-800 shadow shadow-gray-200 absolute left-1/2 transform -translate-x-1/2 -translate-y-1/2 rounded-full p-4 md:p-8">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="#FFF">
                    <path d="M0 3v18h24v-18h-24zm6.623 7.929l-4.623 5.712v-9.458l4.623 3.746zm-4.141-5.929h19.035l-9.517 7.713-9.518-7.713zm5.694 7.188l3.824 3.099 3.83-3.104 5.612 6.817h-18.779l5.513-6.812zm9.208-1.264l4.616-3.741v9.348l-4.616-5.607z"/>
                </svg>
            </div>
            <form class="p-12 md:p-24">
                <div class="flex items-center text-lg mb-6 md:mb-8">
                    <svg class="absolute ml-3" width="24" viewBox="0 0 24 24">
                    <path d="M20.822 18.096c-3.439-.794-6.64-1.49-5.09-4.418 4.72-8.912 1.251-13.678-3.732-13.678-5.082 0-8.464 4.949-3.732 13.678 1.597 2.945-1.725 3.641-5.09 4.418-3.073.71-3.188 2.236-3.178 4.904l.004 1h23.99l.004-.969c.012-2.688-.092-4.222-3.176-4.935z"/>
                    </svg>
                    <input v-model="reminder.title" type="text" id="name" class="bg-gray-200 rounded pl-12 py-2 md:py-4 focus:outline-none w-full" placeholder="Title" />
                </div>
                <div class="flex items-center text-lg mb-6 md:mb-8">
                    <svg class="absolute ml-3" width="24" viewBox="0 0 24 24">
                    <path d="M20.822 18.096c-3.439-.794-6.64-1.49-5.09-4.418 4.72-8.912 1.251-13.678-3.732-13.678-5.082 0-8.464 4.949-3.732 13.678 1.597 2.945-1.725 3.641-5.09 4.418-3.073.71-3.188 2.236-3.178 4.904l.004 1h23.99l.004-.969c.012-2.688-.092-4.222-3.176-4.935z"/>
                    </svg>
                    <input v-model="reminder.description" type="text" id="email" class="bg-gray-200 rounded pl-12 py-2 md:py-4 focus:outline-none w-full" placeholder="Description" />
                </div>
                <div class="flex items-center text-lg mb-6 md:mb-8">
                    <svg class="absolute ml-3" viewBox="0 0 24 24" width="24">
                    <path d="M20.822 18.096c-3.439-.794-6.64-1.49-5.09-4.418 4.72-8.912 1.251-13.678-3.732-13.678-5.082 0-8.464 4.949-3.732 13.678 1.597 2.945-1.725 3.641-5.09 4.418-3.073.71-3.188 2.236-3.178 4.904l.004 1h23.99l.004-.969c.012-2.688-.092-4.222-3.176-4.935z"/>
                    </svg>
                    <input v-model="reminder.remind_at" type="text" id="remind_at" class="bg-gray-200 rounded pl-12 py-2 md:py-4 focus:outline-none w-full" placeholder="Remind At" />
                </div>
                <div class="flex items-center text-lg mb-6 md:mb-8">
                    <svg class="absolute ml-3" viewBox="0 0 24 24" width="24">
                    <path d="M20.822 18.096c-3.439-.794-6.64-1.49-5.09-4.418 4.72-8.912 1.251-13.678-3.732-13.678-5.082 0-8.464 4.949-3.732 13.678 1.597 2.945-1.725 3.641-5.09 4.418-3.073.71-3.188 2.236-3.178 4.904l.004 1h23.99l.004-.969c.012-2.688-.092-4.222-3.176-4.935z"/>
                    </svg>
                    <input v-model="reminder.event_at" type="text" id="event_at" class="bg-gray-200 rounded pl-12 py-2 md:py-4 focus:outline-none w-full" placeholder="Event At" />
                </div>
                <button type="button" class="bg-gradient-to-b from-gray-700 to-gray-900 font-medium p-2 md:p-4 text-white uppercase w-full rounded" @click="add">Add</button>
            </form>
        </div>
    `,
    props: ['reminders'],
    data() {
        return {
            reminder: {
                title: '',
                description: '',
                remind_at: '',
                event_at: '',
            }
        }
    },
    methods: {
        add: async function () {
            let _this = this
            _this.$emit('toggle-loading', true) 
            axios.post('/api/reminders', _this.reminder)
                .then(function (response) {
                    _this.reminders.push(response.data.data)
                    _this.$emit('toggle-set-reminders', _this.reminders)
                    _this.$emit('toggle-reminder', true)
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
    }
})