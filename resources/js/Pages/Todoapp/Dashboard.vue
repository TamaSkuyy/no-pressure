<template >
    <div class="gradient-custom-2 flex h-screen">
        <div class="container mx-auto p-4 mask-custom text-white">
            <h1 class="text-3xl font-bold mb-4 text-center">To-Do List</h1>
            <form @submit.prevent="addTask">
                <div class="flex mb-4">
                    <input type="text" v-model="form.newTask" placeholder="Enter a new task"
                        class="border border-gray-300 p-2 rounded-md flex-1 mr-2 text-black focus:outline-none" />
                    <input type="text" v-model="form.dueDate" placeholder="Enter due date"
                        class="border border-gray-300 p-2 rounded-md flex-1 text-black focus:outline-none" ref="dateInput"
                        @focus="changeInputType('date')" @blur="dueDate === '' ? changeInputType('text') : null" />
                    <button type="submit"
                        class="classWithPad bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 focus:outline-none">
                        Add Task
                    </button>
                </div>
            </form>
            <table class="table mt-4 w-full">
                <tbody>
                    <tr v-for="(task, index) in tasks_data" :key="task.id" :class="{
                        'border-b border-gray-300 hover:background-black': true,
                        'text-green-500': completedTasks.includes(task.id)
                    }">
                        <td class="px-4 py-2">
                            <button @click="update_completness(task.id)" class="focus:outline-none rounded-full p-2">
                                <span v-if="completedTasks.includes(task.id)" class="text-green-500">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M16.707 4.293a1 1 0 010 1.414l-9 9a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L8 13.586l8.293-8.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </span>
                                <span v-else class="text-white-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </span>
                            </button>
                        </td>
                        <td class="px-4 py-2">
                            <span :class="{
                                'line-through': completedTasks.includes(task.id),
                                'text-white-700': !completedTasks.includes(task.id)
                            }">
                                {{ task.task }} (Due: {{ task.due_date }})
                            </span>
                        </td>
                        <td class="px-4 py-2">
                            <button @click.prevent="removeTask(task.id)"
                                class="text-red-500 hover:text-red-700 focus:outline-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M16.293 5.293a1 1 0 0 0-1.414 0L10 9.586 5.707 5.293a1 1 0 1 0-1.414 1.414L8.586 11l-4.293 4.293a1 1 0 1 0 1.414 1.414L10 12.414l4.293 4.293a1 1 0 0 0 1.414-1.414L11.414 11l4.293-4.293a1 1 0 0 0 0-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        </td>

                    </tr>
                </tbody>
            </table>

            <p class="text-white-600 mt-2">
                {{ completedTasks.length }} Completed /
                {{ tasks_data.length - completedTasks.length }} Not Finished
            </p>
        </div>
    </div>
</template>
  
<script>
import { reactive } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { useForm } from '@inertiajs/inertia-vue3'

export default {
    setup() {
        const form = reactive({
            newTask: '',
            dueDate: ''
        })

        function addTask() {
            Inertia.post('/tasks/store', form)
        }

        // function update_completness(id){
        //     Inertia.put('/tasks-complete/' + id);
        // }

        function removeTask(id) {
            if (confirm("Are you sure you want to Delete")) {
                Inertia.delete('/tasks/destroy/' + id);
            }
        }

        return { form, addTask, removeTask }
    },
    created() {
        this.filterCompletedTasks();
    },
    data() {
        return {
            completedTasks: []
        };
    },
    props: {
        canLogin: Boolean,
        canRegister: Boolean,
        laravelVersion: String,
        phpVersion: String,
        tasks_data: {
            Type: Object,
            default: () => { }
        }
    },
    methods: {
        changeInputType(type) {
            this.$nextTick(() => {
                const input = this.$refs.dateInput;
                input.type = type;
            });
        },
        toggleComplete(index) {
            if (this.completedTasks.includes(index)) {
                const taskIndex = this.completedTasks.indexOf(index);
                this.completedTasks.splice(taskIndex, 1);
            } else {
                this.completedTasks.push(index);
            }
        },
        filterCompletedTasks() {
            this.completedTasks = this.tasks_data
                .filter(task => task.completed == 1)
                .map(task => task.id)
                ;
        },
        update_completness(id) {
            // Inertia.put('/tasks-complete/' + id);
            Inertia.visit('/tasks-complete/' + id, {
                method: 'PUT',
                onSuccess: () => {
                    console.log("Data refreshed successfully.");
                },
                onError: () => {
                    console.log("Error refreshing data.");
                }
            });
            // console.log(this.tasks_data);
            // console.log(this.completedTasks);
            // // this.completedTasks = [];
            // // this.completedTasks = this.tasks_data
            // //     .filter(task => task.completed == 1)
            // //     .map(task => task.id)
            // //     ;
            // // console.log(this.completedTasks);
        }
    }
};

</script>
  
<style>
.container {
    max-width: 600px;
    margin: 0 auto;
}

input[type="text"] {
    transition: border-color 0.2s;
}

input[type="text"]:focus {
    border-color: #60a5fa;
}

.table {
    border-collapse: collapse;
    width: 100%;
}

.table td,
.table th {
    padding: 0.75rem;
    text-align: left;
}

.table tr:nth-child(even) {
    background-color: #2b2c2c;
}

.line-through {
    text-decoration: line-through;
}

.rounded-full {
    border-radius: 9999px;
}

.background-black {
    background-color: #2b2c2c;
}

.text-3xl {
    font-size: 1.875rem;
}

.text-green-500 {
    color: #34d399;
}

.text-white-500 {
    color: #ededed;
}

.text-white-600 {
    color: #d1d2d5;
}

.text-red-500 {
    color: #ef4444;
}

.text-red-700 {
    color: #ed4343;
}

.bg-blue-500 {
    background-color: #3b82f6;
}

.hover:bg-blue-600:hover {
    background-color: #2563eb;
}

.text-white {
    color: #ffffff;
}

.classWithPad {
    margin-left: 10px;
}

.gradient-custom-2 {
    /* fallback for old browsers */
    background: #7e40f6;

    /* Chrome 10-25, Safari 5.1-6 */
    background: -webkit-linear-gradient(to right,
            rgba(126, 64, 246, 1),
            rgba(80, 139, 252, 1));

    /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
    background: linear-gradient(to right,
            rgba(126, 64, 246, 1),
            rgba(80, 139, 252, 1));
}

.mask-custom {
    background: rgba(24, 24, 16, 0.2);
    border-radius: 2em;
    backdrop-filter: blur(25px);
    border: 2px solid rgba(255, 255, 255, 0.05);
    background-clip: padding-box;
    box-shadow: 10px 10px 10px rgba(46, 54, 68, 0.03);
}

</style>  