<template>
    <app-layout>
        <template #header>
            <Link
                :href="route('users.index')"
                class="inline font-semibold text-xl text-gray-800 leading-tight"
            >
                User
            </Link>
            <div class="inline">
                <svg xmlns="http://www.w3.org/2000/svg" class="inline mx-3 h-5 w-5 align-sub" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </div>
            <h2 class="inline font-semibold text-xl text-gray-500 leading-tight">
                Change password
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg py-4">
                    <jet-form-section @submitted="updatePassword">

                        <template #form>
                            <div class="grid grid-rows-2 grid-flow-col gap-4 mx-80">

                                <div class="col-span-2">
                                    <jet-label for="password" value="Password" />
                                    <jet-input id="password" type="password" class="mt-1 block w-full" v-model="form.password" required autocomplete="new-password" />
                                    <jet-input-error :message="form.errors.password" class="mt-2" />
                                </div>

                                <div class="col-span-2">
                                    <jet-label for="password_confirmation" value="Confirm Password" />
                                    <jet-input id="password_confirmation" type="password" class="mt-1 block w-full" v-model="form.password_confirmation" required autocomplete="new-password" />
                                    <jet-input-error :message="form.errors.password_confirmation" class="mt-2" />
                                </div>

                            </div>
                        </template>

                        <template #actions>
                            <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                                Saved.
                            </jet-action-message>

                            <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Save
                            </jet-button>
                        </template>

                    </jet-form-section>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import JetButton from '@/Jetstream/Button.vue'
    import JetFormSection from '@/Custom/Form.vue'
    import JetInput from '@/Jetstream/Input.vue'
    import JetInputError from '@/Jetstream/InputError.vue'
    import JetLabel from '@/Jetstream/Label.vue'
    import JetActionMessage from '@/Jetstream/ActionMessage.vue'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'
    import { Link } from '@inertiajs/inertia-vue3'

    export default {
        components: {
            AppLayout,
            JetActionMessage,
            JetButton,
            JetFormSection,
            JetInput,
            JetInputError,
            JetLabel,
            JetSecondaryButton,
            Link,
        },
        props: ['data'],
        data() {
            return {
                form: this.$inertia.form({
                    _method: 'PUT',
                    password: '',
                    password_confirmation: '',
                }),
            }
        },
        methods: {
            updatePassword() {
                this.form.put(route('user.change-password', this.data.id), {
                    errorBag: 'updatePassword',
                    preserveScroll: true,
                    onSuccess: () => this.form.reset(),
                    onError: () => {
                        if (this.form.errors.password) {
                            this.form.reset('password', 'password_confirmation')
                        }
                    }
                })
            },
        },
    }
</script>
