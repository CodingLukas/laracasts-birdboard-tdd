<template>
    <modal classes="p-10 bg-card rounded-lg" height="auto" name="new-project">
        <h1 class="font-normal mb-16 text-center text-2xl">Let’s Start Something New</h1>

        <form @submit.prevent="submit">
            <div class="flex">
                <div class="flex-1 mr-4">
                    <div class="mb-4">
                        <label class="text-sm block mb-2" for="title">Title</label>

                        <input
                            v-model="form.title"
                            id="title"
                            class="border p-2 text-xs block w-full rounded"
                            :class="form.errors.title ? 'border-error' : 'border-muted-light'"
                            type="text">

                        <span v-if="form.errors.title" class="text-xs italic text-error"
                              v-text="form.errors.title[0]"></span>
                    </div>

                    <div class="mb-4">
                        <label class="text-sm block mb-2" for="description">Description</label>

                        <textarea
                            id="description"
                            v-model="form.description"
                            :class="form.errors.description ? 'border-error' : 'border-muted-light'"
                            class="border border-muted-light p-2 text-xs block w-full rounded"
                            rows="7"></textarea>

                        <span v-if="form.errors.description" class="text-xs italic text-error"
                              v-text="form.errors.description[0]"></span>
                    </div>
                </div>

                <div class="flex-1 ml-4">
                    <div class="mb-4">
                        <label class="text-sm block mb-2">Need Some Tasks?</label>
                        <input
                            v-for="task in form.tasks"
                            class="border border-muted-light mb-2 p-2 text-xs block w-full rounded"
                            placeholder="Task 1"
                            v-model="task.body"
                            type="text">
                    </div>

                    <button class="inline-flex items-center text-xs" type="button" @click="addTask">
                        <svg class="mr-2" height="18" viewBox="0 0 18 18" width="18" xmlns="http://www.w3.org/2000/svg">
                            <g fill="none" fill-rule="evenodd" opacity=".307">
                                <path d="M-3-3h24v24H-3z" stroke="#000" stroke-opacity=".012" stroke-width="0"></path>
                                <path
                                    d="M9 0a9 9 0 0 0-9 9c0 4.97 4.02 9 9 9A9 9 0 0 0 9 0zm0 16c-3.87 0-7-3.13-7-7s3.13-7 7-7 7 3.13 7 7-3.13 7-7 7zm1-11H8v3H5v2h3v3h2v-3h3V8h-3V5z"
                                    fill="#000"></path>
                            </g>
                        </svg>

                        <span>Add New Task Field</span>
                    </button>
                </div>
            </div>

            <footer class="flex justify-end">
                <button class="button is-outlined mr-4" type="button" @click="$modal.hide('new-project')">Cancel
                </button>
                <button class="button">Create Project</button>
            </footer>
        </form>
    </modal>
</template>

<script>
import BirdboardForm from './BirdboardForm';

export default {
    data() {
        return {
            form: new BirdboardForm({
                title: '',
                description: '',
                tasks: [
                    {body: ''},
                ]
            })
        };
    },

    methods: {
        addTask() {
            this.form.tasks.push({body: ''});
        },

        async submit() {
            if (!this.form.tasks[0].body) {
                delete this.form.originalData.tasks;
            }

            this.form.submit('/projects')
                .then(response => location = response.data.message);
        }
    }
}
</script>
{"mode":"full","isActive":false}
