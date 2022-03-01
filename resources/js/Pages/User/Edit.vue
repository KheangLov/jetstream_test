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
                Update
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg py-4">
                    <jet-form-section @submitted="updateProfileInformation">

                        <template #form>
                            <div class="grid grid-rows-3 grid-flow-col gap-4">
                                <div class="col-span-2">
                                </div>

                                <!-- Profile Photo -->
                                <div class="row-span-3" v-if="$page.props.jetstream.managesProfilePhotos">
                                    <!-- Profile Photo File Input -->
                                    <input
                                        type="file"
                                        class="hidden"
                                        ref="photo"
                                        @change="updatePhotoPreview"
                                    />

                                    <jet-label for="photo" value="Photo" />

                                    <!-- New Profile Photo Preview -->
                                    <div class="mt-2" v-show="photoPreview">
                                        <span class="block rounded-full w-20 h-20 bg-cover bg-no-repeat bg-center"
                                            :style="`background-image: url(\'${photoPreview}\');`">
                                        </span>
                                    </div>

                                    <jet-secondary-button class="mt-2 mr-2" type="button" @click.prevent="selectNewPhoto">
                                        Select A New Photo
                                    </jet-secondary-button>

                                    <jet-secondary-button type="button" class="mt-2" @click.prevent="deletePhoto" v-if="photoPreview">
                                        Remove Photo
                                    </jet-secondary-button>

                                    <jet-input-error :message="form.errors.photo" class="mt-2" />
                                </div>

                                <!-- Name -->
                                <div class="col-span-2">
                                    <jet-label for="name" value="Name" />
                                    <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" autocomplete="name" />
                                    <jet-input-error :message="form.errors.name" class="mt-2" />
                                </div>

                                <!-- Email -->
                                <div class="col-span-2">
                                    <jet-label for="email" value="Email" />
                                    <jet-input id="email" type="email" class="mt-1 block w-full" v-model="form.email" />
                                    <jet-input-error :message="form.errors.email" class="mt-2" />
                                </div>

                                <div class="col-span-2">
                                </div>

                                <div class="col-span-2">
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
                    name: this.data.name,
                    email: this.data.email,
                    photo: this.data.profile_photo_path,
                }),

                photoPreview: this.data.profile_photo_url,
            }
        },
        methods: {
            updateProfileInformation() {
                if (this.$refs.photo) {
                    this.form.photo = this.$refs.photo.files[0]
                }

                this.form.put(route('users.update', this.data.id), {
                    errorBag: 'updateProfileInformation',
                    preserveScroll: true,
                    onSuccess: () => (this.clearPhotoFileInput()),
                });
            },

            selectNewPhoto() {
                this.$refs.photo.click();
            },

            updatePhotoPreview() {
                const photo = this.$refs.photo.files[0];

                if (! photo) return;

                const reader = new FileReader();

                reader.onload = (e) => {
                    this.photoPreview = e.target.result;
                };

                reader.readAsDataURL(photo);
            },

            deletePhoto() {
                this.$inertia.delete(route('current-user-photo.destroy'), {
                    preserveScroll: true,
                    onSuccess: () => {
                        this.photoPreview = null;
                        this.clearPhotoFileInput();
                    },
                });
            },

            clearPhotoFileInput() {
                if (this.$refs.photo?.value) {
                    this.$refs.photo.value = null;
                }
            },
        },
    }
</script>
