<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                User
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg py-4">
                    <div class="flex items-center justify-between mx-5">
                        <Link
                            :href="route('users.create')"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition"
                        >
                            Create user
                        </Link>
                        <jet-input
                            type="text"
                            class="block mb-4 w-60"
                            v-model="form.search"
                            placeholder="Search..."
                        />
                    </div>
                    <div v-if="message" class="bg-green-500 px-5 py-4 mx-5 rounded text-white">
                        {{ message }}
                    </div>
                    <div class="flex flex-col">
                        <div class="my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                                <div class="overflow-hidden sm:rounded-lg">
                                    <table class="min-w-full">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">
                                                    User Info
                                                </th>

                                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-900 uppercase tracking-wider">
                                                    Actions
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="user in data.data" :key="user.id">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 h-10 w-10">
                                                            <img
                                                                class="h-10 w-10 rounded-full"
                                                                :src="user.profile_photo_url ? user.profile_photo_url : ''"
                                                                :alt="user.name ? user.name : ''"
                                                            >
                                                        </div>
                                                        <div class="ml-4">
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ user.name }}
                                                            </div>
                                                            <div class="text-sm text-gray-500">
                                                                {{ user.email }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right">
                                                    <Link
                                                        :href="route('users.edit', user.id)"
                                                        class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition"
                                                    >
                                                        Edit
                                                    </Link>
                                                    <Link
                                                        :href="route('user.edit-password', user.id)"
                                                        class="inline-flex items-center px-4 py-2 ml-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition"
                                                    >
                                                        Change password
                                                    </Link>
                                                    <Link
                                                        :href="route('users.destroy', user.id)"
                                                        class="inline-flex items-center px-4 py-2 ml-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition"
                                                    >
                                                        Delete
                                                    </Link>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <jet-pagination class="m-5" :links="data.links" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import { reactive, watchEffect } from "vue";
    import { pickBy } from "lodash";
    import { Inertia } from "@inertiajs/inertia";
    import AppLayout from '@/Layouts/AppLayout'
    import JetInput from "@/Jetstream/Input";
    import JetPagination from "@/Custom/Pagination";
    import { Link } from '@inertiajs/inertia-vue3';
    export default {
        components: {
            AppLayout,
            JetInput,
            JetPagination,
            Link,
        },
        props: ['data', 'filters'],
        data() {
            const message = this.$page.props.flash.message
            return {
                message
            }
        },
        setup(props) {
            const form = reactive({
                search: props.filters.search,
                page: props.filters.page,
            });

            watchEffect(() => {
                const query = pickBy(form);
                Inertia.replace(
                    route("users.index", Object.keys(query).length ? query : {})
                );
            });
            return { form };
        },
    }
</script>
