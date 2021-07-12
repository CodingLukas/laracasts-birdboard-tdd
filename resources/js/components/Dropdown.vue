<template>
    <div class="dropdown relative">
        <div :aria-expanded="isOpen"
             aria-haspopup="true"
             class="dropdown-toggle"
             @click.prevent="isOpen = !isOpen"
        >
            <slot name="trigger"></slot>
        </div>

        <div v-show="isOpen"
             :class="align === 'left' ? 'pin-l' : 'pin-r'"
             :style="{ width }"
             class="dropdown-menu absolute bg-card py-2 rounded shadow mt-2"
        >
            <slot></slot>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        width: {default: 'auto'},
        align: {default: 'left'}
    },
    data() {
        return {isOpen: false}
    },
    watch: {
        isOpen(isOpen) {
            if (isOpen) {
                document.addEventListener('click', this.closeIfClickedOutside);
            }
        }
    },
    methods: {
        closeIfClickedOutside(event) {
            if (!event.target.closest('.dropdown')) {
                this.isOpen = false;
                document.removeEventListener('click', this.closeIfClickedOutside);
            }
        }
    }
}
</script>
