
<template>
    <form @submit.prevent="$emit('submitted')">
        <div class="px-4 py-5 bg-white sm:p-6"
            :class="hasActions ? 'sm:rounded-tl-md sm:rounded-tr-md' : 'sm:rounded-md'">
            <slot name="form"></slot>
        </div>

        <div class="flex items-center justify-end px-4 py-3 bg-white text-right sm:px-6 sm:rounded-bl-md sm:rounded-br-md" v-if="hasActions">
            <slot name="actions"></slot>
        </div>
    </form>
</template>

<script>
    import { defineComponent } from 'vue'

    export default defineComponent({
        emits: ['submitted'],

        computed: {
            hasActions() {
                return !! this.$slots.actions
            }
        }
    })
</script>
