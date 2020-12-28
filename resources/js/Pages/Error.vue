<template>
    <header-layout>
        <div>
            {{errors.deny}}
        </div>
    </header-layout>
</template>

<script>
    import HeaderLayout from '@/Layouts/HeaderLayout'
    export default {
        components: {
            HeaderLayout
        },
        props: {
            errors: Object,
        },
    }
</script>